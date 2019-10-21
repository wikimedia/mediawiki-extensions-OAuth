<?php

namespace MediaWiki\Extensions\OAuth;

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
use MediaWiki\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;

/**
 * Page that handles OAuth consumer authorization and token exchange
 */
class SpecialMWOAuth extends \UnlistedSpecialPage {
	/** @var LoggerInterface */
	protected $logger;

	public function __construct() {
		parent::__construct( 'OAuth' );
		$this->logger = LoggerFactory::getInstance( 'OAuth' );
	}

	public function doesWrites() {
		return true;
	}

	public function getLocalName() {
		// Force the canonical name when OAuth headers are present,
		// otherwise SpecialPageFactory redirects and breaks the signature.
		if ( MWOAuthUtils::hasOAuthHeaders( $this->getRequest() ) ) {
			return $this->getName();
		}
		return parent::getLocalName();
	}

	public function execute( $subpage ) {
		global $wgMWOAuthSecureTokenTransfer, $wgMWOAuthReadOnly, $wgBlockDisablesLogin;

		$this->setHeaders();

		$user = $this->getUser();
		$request = $this->getRequest();
		$format = $request->getVal( 'format', 'raw' );

		try {
			if ( $wgMWOAuthReadOnly &&
				!in_array( $subpage, [ 'verified', 'grants', 'identify' ] )
			) {
				throw new MWOAuthException( 'mwoauth-db-readonly' );
			}

			switch ( $subpage ) {
				case 'initiate':
					$oauthServer = MWOAuthUtils::newMWOAuthServer();
					$oauthRequest = MWOAuthRequest::fromRequest( $request );
					$this->logger->debug( __METHOD__ . ": Consumer " .
						"'{$oauthRequest->getConsumerKey()}' getting temporary credentials" );
					// fetch_request_token does the version, freshness, and sig checks
					$token = $oauthServer->fetch_request_token( $oauthRequest );
					$this->returnToken( $token, $format );
					break;
				case 'authorize':
				case 'authenticate':
					$format = 'html'; // for exceptions
					$requestToken = $request->getVal( 'requestToken',
						$request->getVal( 'oauth_token' ) );
					$consumerKey = $request->getVal( 'consumerKey',
						$request->getVal( 'oauth_consumer_key' ) );
					$this->logger->debug( __METHOD__ . ": doing '$subpage' with " .
						"'$requestToken' '$consumerKey' for '{$user->getName()}'" );
					// TODO? Test that $requestToken exists in memcache
					if ( $user->isAnon() ) {
						$query = [];
						// Redirect to login page
						$query['returnto'] = $this->getPageTitle( $subpage )->getPrefixedText();
						$query['returntoquery'] = wfArrayToCgi( [
							'oauth_token'        => $requestToken,
							'oauth_consumer_key' => $consumerKey
						] );
						$loginPage = \SpecialPage::getTitleFor( 'Userlogin' );
						$url = $loginPage->getLocalURL( $query );
						$this->getOutput()->redirect( $url );
					} else {
						if ( $request->wasPosted() && $request->getCheck( 'cancel' ) ) {
							// Show acceptance cancellation confirmation
							$this->showCancelPage();
						} else {
							// Show form and redirect on submission for authorization
							$this->handleAuthorizationForm(
								$requestToken, $consumerKey, $subpage === 'authenticate'
							);
						}
					}
					break;
				case 'token':
					$oauthServer = MWOAuthUtils::newMWOAuthServer();
					$oauthRequest = MWOAuthRequest::fromRequest( $request );

					$isRsa = $oauthRequest->get_parameter( "oauth_signature_method" ) === 'RSA-SHA1';

					// We want to use HTTPS when returning the credentials. But
					// for RSA we don't need to return a token secret, so HTTP is ok.
					if ( $wgMWOAuthSecureTokenTransfer && !$isRsa
						&& $request->detectProtocol() == 'http'
						&& substr( wfExpandUrl( '/', PROTO_HTTPS ), 0, 8 ) === 'https://'
					) {
						$redirUrl = str_replace(
							'http://', 'https://', $request->getFullRequestURL()
						);
						$this->getOutput()->redirect( $redirUrl );
						$this->getOutput()->addVaryHeader( 'X-Forwarded-Proto' );
						break;
					}

					$consumerKey = $oauthRequest->get_parameter( 'oauth_consumer_key' );
					$this->logger->debug( "/token: '{$consumerKey}' getting temporary credentials" );
					$token = $oauthServer->fetch_access_token( $oauthRequest );
					if ( $isRsa ) {
						// RSA doesn't use the token secret, so don't return one.
						$token->secret = '__unused__';
					}
					$this->returnToken( $token, $format );
					break;
				case 'verified':
					$format = 'html'; // for exceptions
					$verifier = $request->getVal( 'oauth_verifier' );
					$requestToken = $request->getVal( 'oauth_token' );
					if ( !$verifier || !$requestToken ) {
						throw new MWOAuthException( 'mwoauth-bad-request-missing-params', [
							\Message::rawParam( \Linker::makeExternalLink(
								'https://www.mediawiki.org/wiki/Help:OAuth/Errors#E001',
								'E001',
								true
							) )
						] );
					}
					$this->getOutput()->addSubtitle( $this->msg( 'mwoauth-desc' )->escaped() );
					$this->showResponse(
						$this->msg( 'mwoauth-verified',
							wfEscapeWikiText( $verifier ),
							wfEscapeWikiText( $requestToken )
						)->parse(),
						$format
					);
					break;
				case 'grants':
					// Backwards compatibility
					$listGrants = \SpecialPage::getTitleFor( 'ListGrants' );
					$this->getOutput()->redirect( $listGrants->getFullURL() );
					break;
				case 'identify':
					$format = 'json'; // we only return JWT, so we assume json
					$server = MWOAuthUtils::newMWOAuthServer();
					$oauthRequest = MWOAuthRequest::fromRequest( $request );
					// verify_request throws an exception if anything isn't verified
					list( $consumer, $token ) = $server->verify_request( $oauthRequest );
					/** @var MWOAuthConsumer $consumer */
					/** @var MWOAuthToken $token */

					$wiki = wfWikiID();
					$dbr = MWOAuthUtils::getCentralDB( DB_REPLICA );
					$access = MWOAuthConsumerAcceptance::newFromToken( $dbr, $token->key );
					$localUser = MWOAuthUtils::getLocalUserFromCentralId( $access->getUserId() );
					if ( !$localUser || !$localUser->isLoggedIn() ) {
						throw new MWOAuthException( 'mwoauth-invalid-authorization-invalid-user', [
							\Message::rawParam( \Linker::makeExternalLink(
								'https://www.mediawiki.org/wiki/Help:OAuth/Errors#E008',
								'E008',
								true
							) )
						] );
					} elseif ( $localUser->isLocked() ||
						$wgBlockDisablesLogin && $localUser->isBlocked()
					) {
						throw new MWOAuthException( 'mwoauth-invalid-authorization-blocked-user' );
					}
					// Access token is for this wiki
					if ( $access->getWiki() !== '*' && $access->getWiki() !== $wiki ) {
						throw new MWOAuthException(
							'mwoauth-invalid-authorization-wrong-wiki',
							[ $wiki ]
						);
					} elseif ( !$consumer->isUsableBy( $localUser ) ) {
						throw new MWOAuthException( 'mwoauth-invalid-authorization-not-approved',
							$consumer->getName() );
					}

					// We know the identity of the user who granted the authorization
					$this->outputJWT( $localUser, $consumer, $oauthRequest, $format, $access );
					break;
				case 'rest_redirect':
					$query = $this->getRequest()->getQueryValues();
					$restUrl = $query['rest_url'];
					unset( $query['title'] );
					unset( $query['rest_url'] );

					$target = wfExpandUrl( $restUrl );

					$this->getOutput()->redirect( wfAppendQuery( $target, $query ) );
					break;
				default:
					$format = $request->getVal( 'format', 'html' );
					$dbr = MWOAuthUtils::getCentralDB( DB_REPLICA );
					$cmrAc = MWOAuthConsumerAccessControl::wrap(
						MWOAuthConsumer::newFromKey(
							$dbr,
							$request->getVal( 'oauth_consumer_key', null )
						),
						$this->getContext()
					);

					if ( !$cmrAc || !$cmrAc->userCanAccess( 'userId' ) ) {
						$this->showError(
							$this->msg( 'mwoauth-bad-request-invalid-action' )->rawParams(
								\Linker::makeExternalLink(
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
								MWOAuthUtils::getCentralUserTalk( $owner )
							)->rawParams( \Linker::makeExternalLink(
								'https://www.mediawiki.org/wiki/Help:OAuth/Errors#E003',
								'E003',
								true
							) ),
							$format
						);
					}
			}
		} catch ( MWOAuthException $exception ) {
			$this->logger->warning( __METHOD__ . ": Exception " . $exception->getMessage(),
				[ 'exception' => $exception ] );
			$this->showError( $this->msg( $exception->msg, $exception->params ), $format );
		} catch ( OAuthException $exception ) {
			$this->logger->warning( __METHOD__ . ": Exception " . $exception->getMessage(),
				[ 'exception' => $exception ] );
			$this->showError(
				$this->msg( 'mwoauth-oauth-exception', $exception->getMessage() ),
				$format
			);
		}

		$this->getOutput()->addModuleStyles( 'ext.MWOAuth.styles' );
	}

	protected function showCancelPage() {
		$request = $this->getRequest();
		$consumerKey = $request->getVal( 'consumerKey', $request->getVal( 'oauth_consumer_key' ) );
		$dbr = MWOAuthUtils::getCentralDB( DB_REPLICA );
		$cmrAc = MWOAuthConsumerAccessControl::wrap(
			MWOAuthConsumer::newFromKey( $dbr, $consumerKey ),
			$this->getContext()
		);
		if ( !$cmrAc ) {
			throw new MWOAuthException( 'mwoauth-invalid-consumer-key' );
		}

		$this->getOutput()->addSubtitle( $this->msg( 'mwoauth-desc' )->escaped() );
		$this->getOutput()->addWikiMsg(
			'mwoauth-acceptance-cancelled',
			$cmrAc->getName()
		);
		$this->getOutput()->addReturnTo( \Title::newMainPage() );
	}

	/**
	 * Make statements about the user, and sign the json with
	 * a key shared with the Consumer.
	 * @param \User $user the user who is the subject of this request
	 * @param MWOAuthConsumer $consumer
	 * @param MWOAuthRequest $request
	 * @param string $format the format of the response: raw, json, or html
	 * @param MWOAuthConsumerAcceptance $access
	 */
	protected function outputJWT( $user, $consumer, $request, $format, $access ) {
		$grants = $access->getGrants();
		$userStatementProvider = UserStatementProvider::factory( $user, $consumer, $grants );

		$statement = $userStatementProvider->getUserStatement();
		// String value used to associate a Client session with an ID Token, and to mitigate
		// replay attacks. The value is passed through unmodified from the Authorization Request.
		$statement['nonce'] = $request->get_parameter( 'oauth_nonce' );
		$JWT = JWT::encode( $statement, $consumer->secret );
		$this->showResponse( $JWT, $format );
	}

	protected function handleAuthorizationForm( $requestToken, $consumerKey, $authenticate ) {
		$this->getOutput()->addSubtitle( $this->msg( 'mwoauth-desc' )->escaped() );

		$user = $this->getUser();

		$dbr = MWOAuthUtils::getCentralDB( DB_REPLICA ); // @TODO: lazy handle
		$oauthServer = MWOAuthUtils::newMWOAuthServer();

		if ( !$consumerKey ) {
			$consumerKey = $oauthServer->getConsumerKey( $requestToken );
		}

		$cmrAc = MWOAuthConsumerAccessControl::wrap(
			MWOAuthConsumer::newFromKey( $dbr, $consumerKey ),
			$this->getContext()
		);
		if ( !$cmrAc || !$cmrAc->userCanAccess( [ 'name', 'userId', 'grants' ] ) ) {
			throw new MWOAuthException( 'mwoauthserver-bad-consumer-key', [
				\Message::rawParam( \Linker::makeExternalLink(
					'https://www.mediawiki.org/wiki/Help:OAuth/Errors#E006',
					'E006',
					true
				) )
			] );
		} elseif ( !$cmrAc->getDAO()->isUsableBy( $user ) ) {
			throw new MWOAuthException(
				'mwoauthserver-bad-consumer',
				[
					$cmrAc->getName(),
					MWOAuthUtils::getCentralUserTalk( $cmrAc->getUserName() ),
				]
			);
		}

		// Check if this user has authorized grants for this consumer previously
		$existing = $oauthServer->getCurrentAuthorization( $user, $cmrAc->getDAO(), wfWikiID() );

		// If only authentication was requested, and the existing authorization
		// matches, and the only grants are 'mwoauth-authonly' or 'mwoauth-authonlyprivate',
		// then don't bother prompting the user about it.
		if ( $existing && $authenticate &&
			$existing->getWiki() === $cmrAc->getDAO()->getWiki() &&
			$existing->getGrants() === $cmrAc->getDAO()->getGrants() &&
			 !array_diff( $existing->getGrants(), [ 'mwoauth-authonly', 'mwoauth-authonlyprivate' ] )
		) {
			$callback = $oauthServer->authorize(
				$consumerKey,
				$requestToken,
				$user,
				false
			);
			$this->getOutput()->redirect( $callback );
			return;
		}

		$this->getOutput()->addModuleStyles(
			[ 'mediawiki.ui', 'mediawiki.ui.button', 'ext.MWOAuth.Styles' ]
		);
		$this->getOutput()->addModules( 'ext.MWOAuth.AuthorizeDialog' );

		$control = new MWOAuthConsumerAcceptanceSubmitControl( $this->getContext(), [], $dbr );
		$form = \HTMLForm::factory( 'table',
			$control->registerValidators( [
				'action' => [
					'type'    => 'hidden',
					'default' => 'accept',
				],
				'confirmUpdate' => [
					'type'    => 'hidden',
					'default' => $existing ? 1 : 0,
				],
				'consumerKey' => [
					'name'    => 'consumerKey',
					'type'    => 'hidden',
					'default' => $consumerKey,
				],
				'requestToken' => [
					'name'    => 'requestToken',
					'type'    => 'hidden',
					'default' => $requestToken,
				]
			] ),
			$this->getContext()
		);
		$form->setSubmitCallback(
			function ( array $data, \IContextSource $context ) use ( $control ) {
				if ( $context->getRequest()->getCheck( 'cancel' ) ) { // sanity
					throw new \MWException( 'Received request for a form cancellation.' );
				}
				$control->setInputParameters( $data );
				return $control->submit();
			}
		);
		$form->setId( 'mw-mwoauth-authorize-form' );

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
		$grantsText = \MWGrants::getGrantsWikiText( $cmrAc->getGrants(), $this->getLanguage() );
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
		$form->addHeaderText( $this->msg( $msgKey, $params )->parseAsBlock() );
		$form->addHeaderText( $this->msg( 'mwoauth-form-legal' )->text() );

		$form->suppressDefaultSubmit();
		$form->addButton( [
			'name' => 'accept',
			'value' => $this->msg( 'mwoauth-form-button-approve' )->text(),
			'id' => 'mw-mwoauth-accept',
			'attribs' => [
				'class' => 'mw-mwoauth-authorize-button mw-ui-button mw-ui-progressive'
			]
		] );
		$form->addButton( [
			'name' => 'cancel',
			'value' => $this->msg( 'mwoauth-form-button-cancel' )->text(),
			'attribs' => [
				'class' => 'mw-mwoauth-authorize-button mw-ui-button mw-ui-quiet'
			]
		] );

		$form->addFooterText( $this->getSkin()->privacyLink() );

		$this->getOutput()->addHTML(
			'<div id="mw-mwoauth-authorize-dialog" class="mw-ui-container">' );
		$status = $form->show();
		$this->getOutput()->addHTML( '</div>' );
		if ( $status instanceof \Status && $status->isOK() ) {
			// Redirect to callback url
			// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
			$this->getOutput()->redirect( $status->value['result']['callbackUrl'] );
		}
	}

	/**
	 * @param \Message $message to return to the user
	 * @param string $format the format of the response: html, raw, or json
	 */
	private function showError( $message, $format ) {
		if ( $format == 'raw' ) {
			$this->showResponse( 'Error: ' . $message->escaped(), 'raw' );
		} elseif ( $format == 'json' ) {
			$error = \FormatJson::encode( [
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
			$this->showResponse( \FormatJson::encode( $token ), 'json' );
		} elseif ( $format == 'html' ) {
			$html = \Html::element(
				'li',
				[],
				'oauth_token = ' . OAuthUtil::urlencode_rfc3986( $token->key )
			);
			$html .= \Html::element(
				'li',
				[],
				'oauth_token_secret = ' . OAuthUtil::urlencode_rfc3986( $token->secret )
			);
			$html .= \Html::element(
				'li',
				[],
				'oauth_callback_confirmed = true'
			);
			$html = \Html::rawElement( 'ul', [], $html );
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
			$this->getOutput()->disable();
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
		} elseif ( $format == 'html' ) { // html
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
}
