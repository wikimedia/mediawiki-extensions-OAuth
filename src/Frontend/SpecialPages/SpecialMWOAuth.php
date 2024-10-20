<?php

namespace MediaWiki\Extension\OAuth\Frontend\SpecialPages;

/**
 * (c) Chris Steipp, Aaron Schulz 2013, GPL
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

use Firebase\JWT\JWT;
use MediaWiki\Context\IContextSource;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\ConsumerAcceptance;
use MediaWiki\Extension\OAuth\Backend\MWOAuthException;
use MediaWiki\Extension\OAuth\Backend\MWOAuthRequest;
use MediaWiki\Extension\OAuth\Backend\MWOAuthToken;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Control\ConsumerAcceptanceSubmitControl;
use MediaWiki\Extension\OAuth\Control\ConsumerAccessControl;
use MediaWiki\Extension\OAuth\Lib\OAuthException;
use MediaWiki\Extension\OAuth\Lib\OAuthToken;
use MediaWiki\Extension\OAuth\Lib\OAuthUtil;
use MediaWiki\Extension\OAuth\UserStatementProvider;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Json\FormatJson;
use MediaWiki\Linker\Linker;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Message\Message;
use MediaWiki\Permissions\GrantsLocalization;
use MediaWiki\Request\WebRequest;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\SpecialPage\UnlistedSpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\Utils\UrlUtils;
use MediaWiki\WikiMap\WikiMap;
use MWException;
use OOUI;
use OOUI\HtmlSnippet;
use Psr\Log\LoggerInterface;
use SkinFactory;

/**
 * Page that handles OAuth consumer authorization and token exchange
 */
class SpecialMWOAuth extends UnlistedSpecialPage {
	protected LoggerInterface $logger;
	private GrantsLocalization $grantsLocalization;
	private SkinFactory $skinFactory;
	private UrlUtils $urlUtils;

	/** @var int Defaults to OAuth1 */
	protected $oauthVersion = Consumer::OAUTH_VERSION_1;

	public function __construct(
		GrantsLocalization $grantsLocalization,
		SkinFactory $skinFactory,
		UrlUtils $urlUtils
	) {
		parent::__construct( 'OAuth' );
		$this->logger = LoggerFactory::getInstance( 'OAuth' );
		$this->grantsLocalization = $grantsLocalization;
		$this->skinFactory = $skinFactory;
		$this->urlUtils = $urlUtils;
	}

	public function doesWrites() {
		return true;
	}

	public function getLocalName() {
		// Force the canonical name when OAuth headers are present,
		// otherwise SpecialPageFactory redirects and breaks the signature.
		if ( Utils::hasOAuthHeaders( $this->getRequest() ) ) {
			return $this->getName();
		}
		return parent::getLocalName();
	}

	public function execute( $subpage ) {
		if ( $this->getRequest()->getRawVal( 'display' ) === 'popup' ) {
			// Replace the default skin with a "micro-skin" that omits most of the interface. (T362706)
			// In the future, we might allow normal skins to serve this mode too, if they advise that
			// they support it by setting a skin option, so that colors and fonts could stay consistent.
			$this->getContext()->setSkin( $this->skinFactory->makeSkin( 'authentication-popup' ) );
		}

		$this->setHeaders();

		$user = $this->getUser();
		$request = $this->getRequest();

		$output = $this->getOutput();
		$output->disallowUserJs();

		$config = $this->getConfig();

		// 'raw' for plaintext, 'html' or 'json'.
		// For the initiate and token endpoints, 'raw' also handles the formatting required by
		// https://oauth.net/core/1.0a/#response_parameters
		// Other than that, there is no reason to use anything but JSON, but the others are
		// supported for B/C.
		$format = $request->getVal( 'format', 'raw' );
		'@phan-var string $format';

		try {
			if ( $config->get( 'MWOAuthReadOnly' ) &&
				!in_array( $subpage, [ 'verified', 'grants', 'identify' ] )
			) {
				throw new MWOAuthException( 'mwoauth-db-readonly' );
			}

			$this->determineOAuthVersion( $request );
			switch ( $subpage ) {
				// Return a request token (first leg of 3-legged OAuth 1 handshake).
				case 'initiate':
					$this->assertOAuthVersion( Consumer::OAUTH_VERSION_1 );
					$oauthServer = Utils::newMWOAuthServer();
					$oauthRequest = MWOAuthRequest::fromRequest( $request );
					$this->logger->debug( __METHOD__ . ": Getting temporary credentials" );
					// fetch_request_token does the version, freshness, and sig checks
					$token = $oauthServer->fetch_request_token( $oauthRequest );
					$this->returnToken( $token, $format );
					break;

				// Show an authorization dialog to the user where they can grant permissions
				// to the app. Used in OAuth 2, by the oauth2/authorize REST endpoint.
				case 'approve':
					$this->assertOAuthVersion( Consumer::OAUTH_VERSION_2 );
					$format = 'html';
					$clientId = $request->getVal( 'client_id', '' );
					$this->logger->debug( __METHOD__ . ": doing '$subpage' for OAuth2 with " .
						"client_id '$clientId' for '{$user->getName()}'" );
					if ( !$user->isNamed() ) {
						// Should not happen, as user login status will already be checked at this point
						// Just redirect back to REST, it will then redirect to login
						$this->redirectToREST();
						return;
					}
					if ( $request->wasPosted() && $request->getCheck( 'cancel' ) ) {
						$this->showCancelPage( $clientId );
					} else {
						$this->handleAuthorizationForm(
							null, $clientId, true
						);
					}

					break;

				// Show an authorization dialog to the user where they can grant permissions
				// to the app (second leg of 3-legged OAuth 1 handshake).
				// 'authenticate' might skip user interaction if the grants are
				// limited and the user already authorized in the past.
				case 'authorize':
				case 'authenticate':
					$this->assertOAuthVersion( Consumer::OAUTH_VERSION_1 );
					$format = 'html';

					$requestToken = $request->getVal( 'requestToken',
						$request->getVal( 'oauth_token' ) );
					$consumerKey = $request->getVal( 'consumerKey',
						$request->getVal( 'oauth_consumer_key' ) );
					$this->logger->debug( __METHOD__ . ": doing '$subpage' with " .
						"'$requestToken' '$consumerKey' for '{$user->getName()}'" );

					$this->requireNamedUser( 'mwoauth-named-account-required-reason' );

					// TODO? Test that $requestToken exists in memcache
					if ( $request->wasPosted() && $request->getCheck( 'cancel' ) ) {
						// Show acceptance cancellation confirmation
						$this->showCancelPage( $consumerKey );
					} else {
						// Show form and redirect on submission for authorization
						$this->handleAuthorizationForm(
							$requestToken, $consumerKey, $subpage === 'authenticate'
						);
					}
					break;

				// Return an access token (third leg of 3-legged OAuth 1 handshake).
				case 'token':
					$this->assertOAuthVersion( Consumer::OAUTH_VERSION_1 );
					$oauthServer = Utils::newMWOAuthServer();
					$oauthRequest = MWOAuthRequest::fromRequest( $request );

					$isRsa = $oauthRequest->get_parameter( "oauth_signature_method" ) === 'RSA-SHA1';

					// We want to use HTTPS when returning the credentials. But
					// for RSA we don't need to return a token secret, so HTTP is ok.
					if ( $config->get( 'MWOAuthSecureTokenTransfer' ) && !$isRsa
						&& $request->detectProtocol() == 'http'
						&& substr( wfExpandUrl( '/', PROTO_HTTPS ), 0, 8 ) === 'https://'
					) {
						$redirUrl = str_replace(
							'http://', 'https://', $request->getFullRequestURL()
						);
						$output->redirect( $redirUrl );
						$output->addVaryHeader( 'X-Forwarded-Proto' );
						break;
					}

					$token = $oauthServer->fetch_access_token( $oauthRequest );
					if ( $isRsa ) {
						// RSA doesn't use the token secret, so don't return one.
						$token->secret = '__unused__';
					}
					$this->returnToken( $token, $format );
					break;

				// Can be used as a return URL for non-web-based OAuth 1 applications which cannot
				// provide their own return URL. It just displays the parameters that would
				// normally be passed to the application via the return URL.
				case 'verified':
					$this->assertOAuthVersion( Consumer::OAUTH_VERSION_1 );
					$format = 'html';
					$verifier = $request->getVal( 'oauth_verifier' );
					$requestToken = $request->getVal( 'oauth_token' );
					if ( !$verifier || !$requestToken ) {
						throw new MWOAuthException( 'mwoauth-bad-request-missing-params', [
							Message::rawParam( Linker::makeExternalLink(
								'https://www.mediawiki.org/wiki/Help:OAuth/Errors#E001',
								'E001',
								true
							) )
						] );
					}
					$output->addSubtitle( $this->msg( 'mwoauth-desc' )->escaped() );
					$this->showResponse(
						$this->msg( 'mwoauth-verified',
							wfEscapeWikiText( $verifier ),
							wfEscapeWikiText( $requestToken )
						)->parse(),
						$format
					);
					break;

				// Was used to list grants and their descriptions. With grants now in core,
				// it just redirects to Special:ListGrants.
				case 'grants':
					$this->assertOAuthVersion( Consumer::OAUTH_VERSION_1 );
					// Backwards compatibility
					$listGrants = SpecialPage::getTitleFor( 'ListGrants' );
					$output->redirect( $listGrants->getFullURL() );
					break;

				// Return a JWT with the identity of the user, based on the OAuth signature.
				// Our homegrown OIDC equivalent for OAuth 1.
				case 'identify':
					$this->assertOAuthVersion( Consumer::OAUTH_VERSION_1 );
					// we only return JWT, so we assume json
					$format = 'json';
					$server = Utils::newMWOAuthServer();
					$oauthRequest = MWOAuthRequest::fromRequest( $request );
					// verify_request throws an exception if anything isn't verified
					[ $consumer, $token ] = $server->verify_request( $oauthRequest );
					/** @var Consumer $consumer */
					/** @var MWOAuthToken $token */

					$wiki = WikiMap::getCurrentWikiId();
					$dbr = Utils::getCentralDB( DB_REPLICA );
					$access = ConsumerAcceptance::newFromToken( $dbr, $token->key );
					$localUser = Utils::getLocalUserFromCentralId( $access->getUserId() );
					if ( !$localUser || !$localUser->isNamed() ) {
						throw new MWOAuthException( 'mwoauth-invalid-authorization-invalid-user', [
							Message::rawParam( Linker::makeExternalLink(
								'https://www.mediawiki.org/wiki/Help:OAuth/Errors#E008',
								'E008',
								true
							) ),
							'consumer' => $consumer->getConsumerKey(),
							'consumer_name' => $consumer->getName(),
							'cmra_id' => $access->getId(),
						] );
					} elseif ( $localUser->isLocked() ||
						( $config->get( 'BlockDisablesLogin' ) && $localUser->getBlock() )
					) {
						throw new MWOAuthException( 'mwoauth-invalid-authorization-blocked-user', [
							'consumer' => $consumer->getConsumerKey(),
							'consumer_name' => $consumer->getName(),
							'user_name' => $localUser->getName(),
						] );
					}
					// Access token is for this wiki
					if ( $access->getWiki() !== '*' && $access->getWiki() !== $wiki ) {
						throw new MWOAuthException(
							'mwoauth-invalid-authorization-wrong-wiki',
							[
								'request_wiki' => $wiki,
								'consumer' => $consumer->getConsumerKey(),
								'consumer_name' => $consumer->getName(),
								'consumer_wiki' => $access->getWiki(),
							]
						);
					} elseif ( !$consumer->isUsableBy( $localUser ) ) {
						throw new MWOAuthException( 'mwoauth-invalid-authorization-not-approved', [
							'consumer_name' => $consumer->getName(),
							'consumer' => $consumer->getConsumerKey(),
							'user_name' => $localUser->getName(),
						] );
					}

					// We know the identity of the user who granted the authorization
					$this->outputJWT( $localUser, $consumer, $oauthRequest, $format, $access );
					break;

				// Redirect to the REST API. A hack to reuse the OAuth 1 codebase and entry
				// point for OAuth 2 workflows.
				case 'rest_redirect':
					$query = $this->getRequest()->getQueryValues();
					if ( !array_key_exists( 'rest_url', $query ) ) {
						throw new OAuthException( 'Invalid redirect' );
					}
					$restUrl = $query['rest_url'];
					// make sure there's no way to change the domain
					if ( $restUrl[0] !== '/' ) {
						$restUrl = '/' . $restUrl;
					}
					$target = ( $this->urlUtils->getServer( PROTO_CURRENT ) ?? '' ) . $restUrl;

					$output->redirect( $target );
					break;

				case '':
					$this->addHelpLink( 'Help:OAuth' );
					$output->addWikiMsg( 'mwoauth-nosubpage-explanation' );
					break;

				// Typo in app code? Show an error.
				default:
					$format = $request->getVal( 'format', 'html' );
					'@phan-var string $format';
					$dbr = Utils::getCentralDB( DB_REPLICA );
					$cmrAc = ConsumerAccessControl::wrap(
						Consumer::newFromKey(
							$dbr,
							$request->getVal( 'oauth_consumer_key', null )
						),
						$this->getContext()
					);

					if ( !$cmrAc || !$cmrAc->userCanAccess( 'userId' ) ) {
						$this->showError(
							$this->msg( 'mwoauth-bad-request-invalid-action' )->rawParams(
								Linker::makeExternalLink(
									'https://www.mediawiki.org/wiki/Help:OAuth/Errors#E002',
									'E002',
									true
								)
							),
							$format
						);
					} else {
						$owner = $cmrAc->getUserName( $this->getUser() );
						$this->showError(
							$this->msg( 'mwoauth-bad-request-invalid-action-contact',
								Utils::getCentralUserTalk( $owner )
							)->rawParams( Linker::makeExternalLink(
								'https://www.mediawiki.org/wiki/Help:OAuth/Errors#E003',
								'E003',
								true
							) ),
							$format
						);
					}
			}
		} catch ( MWOAuthException $exception ) {
			$this->logger->warning( __METHOD__ . ": Exception " . $exception->getNormalizedMessage(),
				[ 'exception' => $exception ] + $exception->getMessageContext() );
			$this->showError( $this->msg( $exception->getMessageObject() ), $format );
		} catch ( OAuthException $exception ) {
			$this->logger->warning( __METHOD__ . ": Exception " . $exception->getMessage(),
				[ 'exception' => $exception ] );
			$this->showError(
				$this->msg( 'mwoauth-oauth-exception', $exception->getMessage() ),
				$format
			);
		}

		$output->addModuleStyles( 'ext.MWOAuth.styles' );
	}

	/**
	 * @param string $consumerKey
	 * @throws MWOAuthException
	 */
	protected function showCancelPage( $consumerKey ) {
		$dbr = Utils::getCentralDB( DB_REPLICA );
		$cmrAc = ConsumerAccessControl::wrap(
			Consumer::newFromKey( $dbr, $consumerKey ),
			$this->getContext()
		);
		if ( !$cmrAc ) {
			throw new MWOAuthException( 'mwoauth-invalid-consumer-key', [
				'consumer' => $consumerKey,
			] );
		}

		if ( $cmrAc->getOAuthVersion() === Consumer::OAUTH_VERSION_2 ) {
			// Respond to client with user approval denied error
			$this->redirectToREST( [
				'approval_cancel' => 1
			] );
			return;
		}

		$output = $this->getOutput();

		$output->addSubtitle( $this->msg( 'mwoauth-desc' )->escaped() );
		$output->addWikiMsg(
			'mwoauth-acceptance-cancelled',
			$cmrAc->getName()
		);
		if ( $this->getRequest()->getRawVal( 'display' ) !== 'popup' ) {
			$output->addReturnTo( Title::newMainPage() );
		}
	}

	/**
	 * Make statements about the user, and sign the json with
	 * a key shared with the Consumer.
	 * @param User $user the user who is the subject of this request
	 * @param Consumer $consumer
	 * @param MWOAuthRequest $request
	 * @param string $format the format of the response: raw, json, or html
	 * @param ConsumerAcceptance $access
	 */
	protected function outputJWT( $user, $consumer, $request, $format, $access ) {
		$grants = $access->getGrants();
		$userStatementProvider = UserStatementProvider::factory( $user, $consumer, $grants );

		$statement = $userStatementProvider->getUserStatement();
		// String value used to associate a Client session with an ID Token, and to mitigate
		// replay attacks. The value is passed through unmodified from the Authorization Request.
		$statement['nonce'] = $request->get_parameter( 'oauth_nonce' );
		$JWT = JWT::encode( $statement, $consumer->secret, 'HS256' );
		$this->showResponse( $JWT, $format );
	}

	/**
	 * @param string|null $requestToken
	 * @param string|null $consumerKey
	 * @param bool $authenticate
	 */
	protected function handleAuthorizationForm( $requestToken, $consumerKey, $authenticate ) {
		$output = $this->getOutput();

		$output->addSubtitle( $this->msg( 'mwoauth-desc' )->escaped() );
		$user = $this->getUser();

		$oauthServer = Utils::newMWOAuthServer();

		if ( !$consumerKey && $requestToken && $this->oauthVersion === Consumer::OAUTH_VERSION_1 ) {
			$consumerKey = $oauthServer->getConsumerKey( $requestToken );
		}

		$cmrAc = ConsumerAccessControl::wrap(
			Consumer::newFromKey( Utils::getCentralDB( DB_REPLICA ), $consumerKey ),
			$this->getContext()
		);

		if ( !$cmrAc || !$cmrAc->userCanAccess( [ 'name', 'userId', 'grants' ] ) ) {
			throw new MWOAuthException( 'mwoauthserver-bad-consumer-key', [
				Message::rawParam( Linker::makeExternalLink(
					'https://www.mediawiki.org/wiki/Help:OAuth/Errors#E006',
					'E006',
					true
				) ),
				'consumer' => $consumerKey,
			] );
		} elseif ( $cmrAc->getDAO()->getOAuthVersion() !== $this->oauthVersion ) {
			throw new MWOAuthException(
				'mwoauthserver-bad-consumer-version',
				[
					Utils::getCentralUserTalk( $cmrAc->getUserName() ),
					Message::rawParam( Linker::makeExternalLink(
						'https://www.mediawiki.org/wiki/Help:OAuth/Errors#E012',
						'E012',
						true
					) )
				]
			);
		} elseif ( !$cmrAc->getDAO()->isUsableBy( $user ) ) {
			throw new MWOAuthException(
				'mwoauthserver-bad-consumer',
				[
					$cmrAc->getName(),
					Utils::getCentralUserTalk( $cmrAc->getUserName() ),
				]
			);
		}

		$existing = $cmrAc->getDAO()->getCurrentAuthorization( $user, WikiMap::getCurrentWikiId() );

		// If only authentication was requested, and the existing authorization
		// matches, and the only grants are 'mwoauth-authonly' or 'mwoauth-authonlyprivate',
		// then don't bother prompting the user about it.
		if ( $existing && $authenticate &&
			$existing->getWiki() === $cmrAc->getDAO()->getWiki() &&
			$existing->getGrants() === $cmrAc->getDAO()->getGrants() &&
			 !array_diff( $existing->getGrants(), [ 'mwoauth-authonly', 'mwoauth-authonlyprivate' ] )
		) {
			if ( $this->oauthVersion === Consumer::OAUTH_VERSION_2 ) {
				$this->redirectToREST( [
					'approval_pass' => true
				] );
			} else {
				$callback = $cmrAc->getDAO()->authorize(
					$user, false, $cmrAc->getDAO()->getGrants(), $requestToken
				);
				$output->redirect( $callback );
			}
			return;
		}

		$control = new ConsumerAcceptanceSubmitControl(
			$this->getContext(), [], Utils::getCentralDB( DB_PRIMARY ), $this->oauthVersion
		);

		$form = HTMLForm::factory( 'ooui',
			$control->registerValidators( $this->getRequestValidators( [
				'existing' => $existing,
				'consumerKey' => $consumerKey,
				'requestToken' => $requestToken
			] ) ),
			$this->getContext()
		);
		$form->setSubmitCallback(
			static function ( array $data, IContextSource $context ) use ( $control ) {
				if ( $context->getRequest()->getCheck( 'cancel' ) ) {
					throw new MWException( 'Received request for a form cancellation.' );
				}
				$control->setInputParameters( $data );
				return $control->submit();
			}
		);
		$form->setId( 'mw-mwoauth-authorize-form' );
		$form->addHiddenField( 'display', $this->getRequest()->getRawVal( 'display' ) );

		// Possible messages are:
		// * mwoauth-form-description-allwikis
		// * mwoauth-form-description-onewiki
		// * mwoauth-form-description-allwikis-nogrants
		// * mwoauth-form-description-onewiki-nogrants
		// * mwoauth-form-description-allwikis-privateinfo
		// * mwoauth-form-description-onewiki-privateinfo
		// * mwoauth-form-description-allwikis-privateinfo-norealname
		// * mwoauth-form-description-onewiki-privateinfo-norealname
		$msgKey = 'mwoauth-form-description';
		$params = [
			$this->getUser()->getName(),
			$cmrAc->getName(),
			$cmrAc->getUserName(),
		];
		if ( $cmrAc->getWiki() === '*' ) {
			$msgKey .= '-allwikis';
		} else {
			$msgKey .= '-onewiki';
			$params[] = $cmrAc->getWikiName();
		}
		$grants = $cmrAc->getGrants();
		if ( $this->oauthVersion === Consumer::OAUTH_VERSION_2 ) {
			$grants = $this->getRequestedGrants( $cmrAc );
		}

		$grantsText = $this->grantsLocalization->getGrantsWikiText( $grants, $this->getLanguage() );
		if ( $grantsText === "\n" ) {
			if ( in_array( 'mwoauth-authonlyprivate', $cmrAc->getGrants(), true ) ) {
				$msgKey .= '-privateinfo';
				if ( !$this->useRealNames() ) {
					// If the wiki does not use real names, don't mention them in the authorization
					// dialog to avoid scaring users. The wiki where the authorization dialog is
					// shown and the wiki where the user is actually identified might be different;
					// there's not much we can do about that here so it is left to the wiki
					// administrator to set up the farm in a non-misleading way.
					$msgKey .= '-norealname';
				}
			} else {
				$msgKey .= '-nogrants';
			}
		} else {
			$params[] = $grantsText;
		}
		$form->addHeaderHtml( $this->msg( $msgKey, $params )->parseAsBlock() );
		$form->addHeaderHtml( $this->msg( 'mwoauth-form-legal' )->text() );

		$form->suppressDefaultSubmit();
		$form->addButton( [
			'name' => 'accept',
			'value' => $this->msg( 'mwoauth-form-button-approve' )->text(),
			'id' => 'mw-mwoauth-accept',
			'attribs' => [
				'class' => 'mw-mwoauth-authorize-button'
			],
			'flags' => [ 'primary', 'progressive' ],
		] );
		$form->addButton( [
			'name' => 'cancel',
			'value' => $this->msg( 'mwoauth-form-button-cancel' )->text(),
			'attribs' => [
				'class' => 'mw-mwoauth-authorize-button'
			],
			'framed' => false,
		] );

		if ( $this->getRequest()->getRawVal( 'display' ) !== 'popup' ) {
			// If not in popup mode, duplicate the "Privacy policy" link from the site footer for
			// easy access, as the overlaid dialog would cover it. In popup mode, the site footer
			// with the link is easily accessible below the form, so don't duplicate it.
			// This should be replaced with the app's own privacy policy link eventually (T64686).
			$form->addFooterHtml( $this->makePrivacyLink() );
		}

		$out = $this->getOutput();
		$out->enableOOUI();
		$out->addModuleStyles( 'ext.MWOAuth.AuthorizeForm' );
		if ( $this->getRequest()->getRawVal( 'display' ) !== 'popup' ) {
			$out->addModules( 'ext.MWOAuth.AuthorizeDialog' );
		}

		$form->prepareForm();
		$status = $form->tryAuthorizedSubmit();

		$out->addHtml( new OOUI\PanelLayout( [
			'id' => 'mw-mwoauth-authorize-panel',
			'expanded' => false,
			'content' => new HtmlSnippet( $form->getHTML( $status ) ),
		] ) );

		if ( $status instanceof Status && $status->isOK() ) {
			if ( $this->oauthVersion === Consumer::OAUTH_VERSION_2 ) {
				$this->redirectToREST( [
					'approval_pass' => true
				] );
			} else {
				// Redirect to callback url
				// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
				$output->redirect( $status->value['result']['callbackUrl'] );
			}
		}
	}

	private function redirectToREST( $queryAppend = [] ) {
		$redirectParams = [
			'returnto' => $this->getRequest()->getText(
				'returnto', $this->getRequest()->getText( 'returnto' )
			),
			'returntoquery' => wfCgiToArray(
				$this->getRequest()->getText(
					'returntoquery', $this->getRequest()->getText( 'returntoquery' )
				)
			)
		];

		$expanded = wfExpandUrl( $redirectParams['returnto'] );
		if ( !$expanded ) {
			return;
		}

		$returnToQuery = array_merge(
			$redirectParams['returntoquery'],
			$queryAppend
		);
		$returnToQuery = wfArrayToCgi( $returnToQuery );

		$output = $this->getOutput();
		$output->disable();
		$output->getRequest()->response()->header(
			'Location: ' . "$expanded?{$returnToQuery}"
		);
	}

	/**
	 * @param array $data
	 * @return array[]
	 */
	private function getRequestValidators( $data = [] ) {
		$validators = [
			'action' => [
				'type'    => 'hidden',
				'default' => 'accept',
			],
			'confirmUpdate' => [
				'type'    => 'hidden',
				'default' => $data['existing'] ? 1 : 0,
			],
			'oauth_version' => [
				'name' => 'oauth_version',
				'type' => 'hidden',
				'default' => $this->oauthVersion
			],
		];
		if ( $this->oauthVersion === Consumer::OAUTH_VERSION_2 ) {
			$validators += [
				'client_id' => [
					'name' => 'client_id',
					'type' => 'hidden',
					'default' => $this->getRequest()->getText( 'client_id' )
				],
				'scope' => [
					'name' => 'scope',
					'type' => 'hidden',
					'default' => $this->getRequest()->getText( 'scope' )
				],
				'returnto' => [
					'name' => 'returnto',
					'type'    => 'hidden',
					'default' => $this->getRequest()->getText( 'returnto' )
				],
				'returntoquery' => [
					'name'    => 'returntoquery',
					'type'    => 'hidden',
					'default' => $this->getRequest()->getText( 'returntoquery' )
				],
			];
		} else {
			$validators += [
				'consumerKey' => [
					'name'    => 'consumerKey',
					'type'    => 'hidden',
					'default' => $data['consumerKey']
				],
				'requestToken' => [
					'name'    => 'requestToken',
					'type'    => 'hidden',
					'default' => $data['requestToken'],
				],
			];
		}

		return $validators;
	}

	/**
	 * OAuth 2.0 only
	 * Get only the grants (scopes) that were actually requested (and are allowed)
	 *
	 * @param ConsumerAccessControl $cmrAc
	 * @return string[]
	 */
	private function getRequestedGrants( $cmrAc ) {
		$allowed = $cmrAc->getGrants();
		$requested = explode( ' ', $this->getRequest()->getText( 'scope', '' ) );

		return array_intersect( $requested, $allowed );
	}

	/**
	 * @param Message $message to return to the user
	 * @param string $format the format of the response: html, raw, or json
	 */
	private function showError( $message, $format ) {
		if ( $format == 'raw' ) {
			$this->showResponse( 'Error: ' . $message->escaped(), 'raw' );
		} elseif ( $format == 'json' ) {
			$error = FormatJson::encode( [
				'error' => $message->getKey(),
				'message' => $message->text(),
			] );
			$this->showResponse( $error, 'json' );
		} elseif ( $format == 'html' ) {
			$this->getOutput()->showErrorPage( 'mwoauth-error', $message );
		}
	}

	/**
	 * @param OAuthToken $token
	 * @param string $format the format of the response: html, raw, or json
	 */
	private function returnToken( OAuthToken $token, $format ) {
		if ( $format == 'raw' ) {
			$return = 'oauth_token=' . OAuthUtil::urlencode_rfc3986( $token->key );
			$return .= '&oauth_token_secret=' . OAuthUtil::urlencode_rfc3986( $token->secret );
			$return .= '&oauth_callback_confirmed=true';
			$this->showResponse( $return, 'raw' );
		} elseif ( $format == 'json' ) {
			$this->showResponse( FormatJson::encode( $token ), 'json' );
		} elseif ( $format == 'html' ) {
			$html = Html::element(
				'li',
				[],
				'oauth_token = ' . OAuthUtil::urlencode_rfc3986( $token->key )
			);
			$html .= Html::element(
				'li',
				[],
				'oauth_token_secret = ' . OAuthUtil::urlencode_rfc3986( $token->secret )
			);
			$html .= Html::element(
				'li',
				[],
				'oauth_callback_confirmed = true'
			);
			$html = Html::rawElement( 'ul', [], $html );
			$this->showResponse( $html, 'html' );
		}
	}

	/**
	 * @param string $data html or string to pass back to the user. Already escaped.
	 * @param string $format the format of the response: raw, json, or html
	 * @param-taint $data escaped
	 */
	private function showResponse( $data, $format ) {
		$out = $this->getOutput();
		if ( $format == 'raw' || $format == 'json' ) {
			$out->disable();
			// Cancel output buffering and gzipping if set
			wfResetOutputBuffers();
			// We must not allow the output to be Squid cached
			$response = $this->getRequest()->response();
			$response->header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', 0 ) . ' GMT' );
			$response->header( 'Cache-Control: no-cache, no-store, max-age=0, must-revalidate' );
			$response->header( 'Pragma: no-cache' );
			$response->header( 'Content-length: ' . strlen( $data ) );
			if ( $format == 'json' ) {
				$response->header( 'Content-type: application/json' );
			} else {
				$response->header( 'Content-type: text/plain' );
			}
			print $data;
		} elseif ( $format == 'html' ) {
			$out->addHTML( $data );
		}
	}

	/**
	 * Check whether the wiki is configured to use/show real names.
	 * We assume that either all or none of the OAuth wikis in a farm use real names.
	 * @return bool
	 */
	private function useRealNames() {
		$config = $this->getContext()->getConfig();
		return !in_array( 'realname', $config->get( 'HiddenPrefs' ), true );
	}

	/**
	 * Get the requested OAuth version from the request
	 *
	 * @param WebRequest $request
	 * @return int
	 */
	private function determineOAuthVersion( WebRequest $request ) {
		$this->oauthVersion = $request->getInt( 'oauth_version', Consumer::OAUTH_VERSION_1 );

		return $this->oauthVersion;
	}

	/**
	 * @param int $allowed Allowed version
	 * @throws MWOAuthException
	 */
	private function assertOAuthVersion( $allowed ) {
		if ( $this->oauthVersion !== $allowed ) {
			throw new MWOAuthException(
				'mwoauth-oauth-unsupported-version',
				[ $this->oauthVersion ]
			);
		}
	}

	private function makePrivacyLink() {
		// If the link description has been disabled in the default language,
		if ( $this->msg( 'privacy' )->inContentLanguage()->isDisabled() ) {
			// then it is disabled, for all languages.
			$title = null;
		} else {
			// Otherwise, we display the link for the user, described in their
			// language (which may or may not be the same as the default language),
			// but we make the link target be the one site-wide page.
			$title = Title::newFromText( $this->msg( 'privacypage' )->inContentLanguage()->text() );
		}

		if ( !$title ) {
			return '';
		}

		return $this->getLinkRenderer()->makeKnownLink(
			$title,
			$this->msg( 'privacy' )->text()
		);
	}
}
