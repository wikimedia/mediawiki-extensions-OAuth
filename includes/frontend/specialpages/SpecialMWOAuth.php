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
use MediaWiki\Extensions\OAuth\Control\ConsumerAcceptanceSubmitControl;
use MediaWiki\Extensions\OAuth\Control\ConsumerAccessControl;
use MediaWiki\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;

/**
 * Page that handles OAuth consumer authorization and token exchange
 */
class SpecialMWOAuth extends \UnlistedSpecialPage {
	/** @var LoggerInterface */
	protected $logger;

	/** @var int Defaults to OAuth1 */
	protected $oauthVersion = MWOAuthConsumer::OAUTH_VERSION_1;

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

			$this->determineOAuthVersion( $request );
			switch ( $subpage ) {
				case 'initiate':
					$this->assertOAuthVersion( MWOAuthConsumer::OAUTH_VERSION_1 );
					$oauthServer = MWOAuthUtils::newMWOAuthServer();
					$oauthRequest = MWOAuthRequest::fromRequest( $request );
					$this->logger->debug( __METHOD__ . ": Getting temporary credentials" );
					// fetch_request_token does the version, freshness, and sig checks
					$token = $oauthServer->fetch_request_token( $oauthRequest );
					$this->returnToken( $token, $format );
					break;
				case 'approve':
					$this->assertOAuthVersion( MWOAuthConsumer::OAUTH_VERSION_2 );
					$format = 'html';
					$clientId = $request->getVal( 'client_id', '' );
					$this->logger->debug( __METHOD__ . ": doing '$subpage' for OAuth2 with " .
						"client_id '$clientId' for '{$user->getName()}'" );
					if ( $user->isAnon() ) {
						// Should not happen, as user login status will already be checked at this point
						// Just redirect back to REST, it will then redirect to login
						return $this->redirectToREST();
					}
					if ( $request->wasPosted() && $request->getCheck( 'cancel' ) ) {
						$this->showCancelPage( $clientId );
					} else {
						$this->handleAuthorizationForm(
							null, $clientId, true
						);
					}

					break;
				case 'authorize':
				case 'authenticate':
					$this->assertOAuthVersion( MWOAuthConsumer::OAUTH_VERSION_1 );
					$format = 'html'; // for exceptions

					$requestToken = $request->getVal( 'requestToken',
						$request->getVal( 'oauth_token' ) );
					$consumerKey = $request->getVal( 'consumerKey',
						$request->getVal( 'oauth_consumer_key' ) );
					$this->logger->debug( __METHOD__ . ": doing '$subpage' with " .
						"'$requestToken' '$consumerKey' for '{$user->getName()}'" );

					// TODO? Test that $requestToken exists in memcache
					if ( $user->isAnon() ) {
						// Login required on provider wiki
						$this->requireLogin( 'mwoauth-login-required-reason' );
					} else {
						if ( $request->wasPosted() && $request->getCheck( 'cancel' ) ) {
							// Show acceptance cancellation confirmation
							$this->showCancelPage( $consumerKey );
						} else {
							// Show form and redirect on submission for authorization
							$this->handleAuthorizationForm(
								$requestToken, $consumerKey, $subpage === 'authenticate'
							);
						}
					}
					break;
				case 'token':
					$this->assertOAuthVersion( MWOAuthConsumer::OAUTH_VERSION_1 );
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

					$token = $oauthServer->fetch_access_token( $oauthRequest );
					if ( $isRsa ) {
						// RSA doesn't use the token secret, so don't return one.
						$token->secret = '__unused__';
					}
					$this->returnToken( $token, $format );
					break;
				case 'verified':
					$this->assertOAuthVersion( MWOAuthConsumer::OAUTH_VERSION_1 );
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
					$this->assertOAuthVersion( MWOAuthConsumer::OAUTH_VERSION_1 );
					// Backwards compatibility
					$listGrants = \SpecialPage::getTitleFor( 'ListGrants' );
					$this->getOutput()->redirect( $listGrants->getFullURL() );
					break;
				case 'identify':
					$this->assertOAuthVersion( MWOAuthConsumer::OAUTH_VERSION_1 );
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
				case '':
					$output = $this->getOutput();
					$this->addHelpLink( 'Help:OAuth' );
					$output->addWikiMsg( 'mwoauth-nosubpage-explanation' );
					break;
				default:
					$format = $request->getVal( 'format', 'html' );
					$dbr = MWOAuthUtils::getCentralDB( DB_REPLICA );
					$cmrAc = ConsumerAccessControl::wrap(
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

	/**
	 * @param string $consumerKey
	 * @throws MWOAuthException
	 */
	protected function showCancelPage( $consumerKey ) {
		$dbr = MWOAuthUtils::getCentralDB( DB_REPLICA );
		$cmrAc = ConsumerAccessControl::wrap(
			MWOAuthConsumer::newFromKey( $dbr, $consumerKey ),
			$this->getContext()
		);
		if ( !$cmrAc ) {
			throw new MWOAuthException( 'mwoauth-invalid-consumer-key' );
		}

		if ( $cmrAc->getOAuthVersion() === MWOAuthConsumer::OAUTH_VERSION_2 ) {
			// Respond to client with user approval denied error
			$this->redirectToREST( [
				'approval_cancel' => 1
			] );
			return;
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

		$oauthServer = MWOAuthUtils::newMWOAuthServer();

		if ( !$consumerKey && $this->oauthVersion === MWOAuthConsumer::OAUTH_VERSION_1 ) {
			$consumerKey = $oauthServer->getConsumerKey( $requestToken );
		}

		$cmrAc = ConsumerAccessControl::wrap(
			MWOAuthConsumer::newFromKey( MWOAuthUtils::getCentralDB( DB_REPLICA ), $consumerKey ),
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
		} elseif (
			!$cmrAc->getDAO()->isUsableBy( $user ) ||
			$cmrAc->getDAO()->getOAuthVersion() !== $this->oauthVersion
		) {
			throw new MWOAuthException(
				'mwoauthserver-bad-consumer',
				[
					$cmrAc->getName(),
					MWOAuthUtils::getCentralUserTalk( $cmrAc->getUserName() ),
				]
			);
		}

		$existing = $cmrAc->getDAO()->getCurrentAuthorization( $user, wfWikiID() );

		// If only authentication was requested, and the existing authorization
		// matches, and the only grants are 'mwoauth-authonly' or 'mwoauth-authonlyprivate',
		// then don't bother prompting the user about it.
		if ( $existing && $authenticate &&
			$existing->getWiki() === $cmrAc->getDAO()->getWiki() &&
			$existing->getGrants() === $cmrAc->getDAO()->getGrants() &&
			 !array_diff( $existing->getGrants(), [ 'mwoauth-authonly', 'mwoauth-authonlyprivate' ] )
		) {
			if ( $this->oauthVersion === MWOAuthConsumer::OAUTH_VERSION_2 ) {
				$this->redirectToREST();
			} else {
				$callback = $cmrAc->getDAO()->authorize(
					$user, false, $cmrAc->getDAO()->getGrants(), $requestToken
				);
				$this->getOutput()->redirect( $callback );
			}
			return;
		}

		$this->getOutput()->addModuleStyles(
			[ 'mediawiki.ui', 'mediawiki.ui.button', 'ext.MWOAuth.Styles' ]
		);
		$this->getOutput()->addModules( 'ext.MWOAuth.AuthorizeDialog' );

		$control = new ConsumerAcceptanceSubmitControl(
			$this->getContext(), [], MWOAuthUtils::getCentralDB( DB_MASTER ), $this->oauthVersion
		);

		$form = \HTMLForm::factory( 'table',
			$control->registerValidators( $this->getRequestValidators( [
				'existing' => $existing,
				'consumerKey' => $consumerKey,
				'requestToken' => $requestToken
			] ) ),
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
		$grants = $cmrAc->getGrants();
		if ( $this->oauthVersion === MWOAuthConsumer::OAUTH_VERSION_2 ) {
			$grants = $this->getRequestedGrants( $cmrAc );
		}

		$grantsText = \MWGrants::getGrantsWikiText( $grants, $this->getLanguage() );
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
			if ( $this->oauthVersion === MWOAuthConsumer::OAUTH_VERSION_2 ) {
				$this->redirectToREST( [
					'approval_pass' => true
				] );
			} else {
				// Redirect to callback url
				// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
				$this->getOutput()->redirect( $status->value['result']['callbackUrl'] );
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

		$this->getOutput()->disable();
		$this->getOutput()->getRequest()->response()->header(
			'Location: ' . "$expanded?{$returnToQuery}"
		);
	}

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
		if ( $this->oauthVersion === MWOAuthConsumer::OAUTH_VERSION_2 ) {
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
	 * @return array
	 */
	private function getRequestedGrants( $cmrAc ) {
		$allowed = $cmrAc->getGrants();
		$requested = explode( ' ', $this->getRequest()->getText( 'scope', '' ) );

		return array_intersect( $requested, $allowed );
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

	/**
	 * Get the requested OAuth version from the request
	 *
	 * @param \WebRequest $request
	 * @return string
	 */
	private function determineOAuthVersion( \WebRequest $request ) {
		$this->oauthVersion = $request->getInt( 'oauth_version', MWOAuthConsumer::OAUTH_VERSION_1 );

		return $this->oauthVersion;
	}

	/**
	 * @param string $allowed Allowed version
	 * @throws MWOAuthException
	 */
	private function assertOAuthVersion( $allowed ) {
		if ( $this->oauthVersion !== $allowed ) {
			throw new MWOAuthException(
				'mwoauth-oauth-unsupported-version',
				$this->oauthVersion
			);
		}
	}
}
