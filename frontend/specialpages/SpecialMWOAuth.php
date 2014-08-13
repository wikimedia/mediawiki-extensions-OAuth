<?php

namespace MediaWiki\Extensions\OAuth;

/*
 (c) Chris Steipp, Aaron Schulz 2013, GPL

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License along
 with this program; if not, write to the Free Software Foundation, Inc.,
 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 http://www.gnu.org/copyleft/gpl.html
*/

/**
 * Page that handles OAuth consumer authorization and token exchange
 */
class SpecialMWOAuth extends \UnlistedSpecialPage {
	function __construct() {
		parent::__construct( 'OAuth' );
	}

	public function execute( $subpage ) {
		global $wgMWOAuthSecureTokenTransfer, $wgMWOAuthReadOnly;

		$this->setHeaders();

		$user = $this->getUser();
		$request = $this->getRequest();
		$format = $request->getVal( 'format', 'raw' );

		try {
			if ( $wgMWOAuthReadOnly && !in_array( $subpage, array( 'verified', 'grants', 'identify' ) ) ) {
				throw new MWOAuthException( 'mwoauth-db-readonly' );
			}

			switch ( $subpage ) {
				case 'initiate':
					$oauthServer = MWOAuthUtils::newMWOAuthServer();
					$oauthRequest = MWOAuthRequest::fromRequest( $request );
					wfDebugLog( 'OAuth', __METHOD__ . ": Consumer " .
						"'{$oauthRequest->getConsumerKey()}' getting temporary credentials" );
					// fetch_request_token does the version, freshness, and sig checks
					$token = $oauthServer->fetch_request_token( $oauthRequest );
					$this->returnToken( $token, $format );
					break;
				case 'authorize':
					$format = 'html'; // for exceptions
					$requestToken = $request->getVal( 'requestToken',
						$request->getVal( 'oauth_token' ) );
					$consumerKey = $request->getVal( 'consumerKey',
						$request->getVal( 'oauth_consumer_key' ) );
					wfDebugLog( 'OAuth', __METHOD__ . ": doing 'authorize' with " .
						"'$requestToken' '$consumerKey' for '{$user->getName()}'" );
					// TODO? Test that $requestToken exists in memcache
					if ( $user->isAnon() ) {
						// Redirect to login page
						$query['returnto'] = $this->getPageTitle( 'authorize' )->getPrefixedText();
						$query['returntoquery'] = wfArrayToCgi( array(
							'oauth_token'        => $requestToken,
							'oauth_consumer_key' => $consumerKey
						) );
						$loginPage = \SpecialPage::getTitleFor( 'UserLogin' );
						$url = $loginPage->getLocalURL( $query );
						$this->getOutput()->redirect( $url );
					} else {
						if ( $request->wasPosted() && $request->getCheck( 'cancel' ) ) {
							// Show acceptance cancellation confirmation
							$this->showCancelPage();
						} else {
							// Show form and redirect on submission for authorization
							$this->handleAuthorizationForm( $requestToken, $consumerKey );
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
						$redirUrl = str_replace( 'http://', 'https://', $request->getFullRequestURL() );
						$this->getOutput()->redirect( $redirUrl );
						$this->getOutput()->addVaryHeader( 'X-Forwarded-Proto' );
						break;
					}

					$consumerKey = $oauthRequest->get_parameter( 'oauth_consumer_key' );
					wfDebugLog( 'OAuth', "/token: '{$consumerKey}' getting temporary credentials" );
					$token = $oauthServer->fetch_access_token( $oauthRequest );
					if ( $isRsa ) {
						// RSA doesn't use the token secret, so don't return one.
						$token->secret = '__unused__';
					}
					$this->returnToken( $token, $format );
					break;
				case 'verified':
					$format = 'html'; // for exceptions
					$verifier = $request->getVal( 'oauth_verifier', false );
					$requestToken = $request->getVal( 'oauth_token', false );
					if ( !$verifier || !$requestToken ) {
						throw new MWOAuthException( 'mwoauth-bad-request-missing-params' );
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
					$this->showGrantRightsTables();
					break;
				case 'identify':
					$format = 'json'; // we only return JWT, so we assume json
					$server = MWOAuthUtils::newMWOAuthServer();
					$oauthRequest = MWOAuthRequest::fromRequest( $request );
					// verify_request throws an exception if anything isn't verified
					list( $consumer, $token ) = $server->verify_request( $oauthRequest );

					$wiki = wfWikiID();
					$dbr = MWOAuthUtils::getCentralDB( DB_SLAVE );
					$access = MWOAuthConsumerAcceptance::newFromToken( $dbr, $token->key );
					// Access token is for this wiki
					if ( $access->get( 'wiki' ) !== '*' && $access->get( 'wiki' ) !== $wiki ) {
						throw new MWOAuthException(
							'mwoauth-invalid-authorization-wrong-wiki',
							array( $wiki )
						);
					}
					$localUser = MWOAuthUtils::getLocalUserFromCentralId( $access->get( 'userId' ) );
					if ( !$localUser || !$localUser->isLoggedIn() ) {
						throw new MWOAuthException( 'mwoauth-invalid-authorization-invalid-user' );
					}

					// We know the identity of the user who granted the authorization
					$this->outputJWT( $localUser, $consumer, $oauthRequest, $format, $access );
					break;
				default:
					$format = $request->getVal( 'format', 'html' );
					$dbr = MWOAuthUtils::getCentralDB( DB_SLAVE );
					$cmr = MWOAuthDAOAccessControl::wrap(
						MWOAuthConsumer::newFromKey(
							$dbr,
							$request->getVal( 'oauth_consumer_key', null )
						),
						$this->getContext()
					);

					if ( !$cmr ) {
						$this->showError(
							wfMessage( 'mwoauth-bad-request-invalid-action' ),
							$format
						);
					} else {
						$owner = MWOAuthUtils::getCentralUserNameFromId( $cmr->get( 'userId' ), $this->getUser() );
						$this->showError(
							wfMessage( 'mwoauth-bad-request-invalid-action-contact',
								MWOAuthUtils::getCentralUserTalk( $owner )
							),
							$format
						);
					}
			}
		} catch ( MWOAuthException $exception ) {
			wfDebugLog( 'OAuth', __METHOD__ . ": Exception " . $exception->getMessage() );
			$this->showError( wfMessage( $exception->msg, $exception->params ), $format );
		} catch ( OAuthException $exception ) {
			wfDebugLog( 'OAuth', __METHOD__ . ": Exception " . $exception->getMessage() );
			$this->showError(
				wfMessage( 'mwoauth-oauth-exception', $exception->getMessage() ),
				$format
			);
		}

		$this->getOutput()->addModuleStyles( 'ext.MWOAuth.BasicStyles' );
	}

	protected function showCancelPage() {
		$request = $this->getRequest();
		$consumerKey = $request->getVal( 'consumerKey', $request->getVal( 'oauth_consumer_key' ) );
		$dbr = MWOAuthUtils::getCentralDB( DB_SLAVE );
		$cmr = MWOAuthDAOAccessControl::wrap(
			MWOAuthConsumer::newFromKey( $dbr, $consumerKey ),
			$this->getContext()
		);

		$this->getOutput()->addSubtitle( $this->msg( 'mwoauth-desc' )->escaped() );
		$this->getOutput()->addWikiMsg(
			'mwoauth-acceptance-cancelled',
			$cmr->get( 'name' )
		);
		$this->getOutput()->addReturnTo( \Title::newMainPage() );
	}

	/**
	 * Make statements about the user, and sign the json with
	 * a key shared with the Consumer.
	 * @param \User $user the user who is the subject of this request
	 * @param OAuthConsumer $consumer
	 * @param MWOAuthConsumerAcceptance $access
	 */
	protected function outputJWT( $user, $consumer, $request, $format, $access ) {
		global $wgCanonicalServer;
		$statement = array();

		// Include some of the OpenID Connect attributes
		// http://openid.net/specs/openid-connect-core-1_0.html (draft 14)
		// Issuer Identifier for the Issuer of the response.
		$statement['iss'] = $wgCanonicalServer;
		// Subject identifier. A locally unique and never reassigned identifier.
		$statement['sub'] = MWOAuthUtils::getCentralIdFromLocalUser( $user );
		// Audience(s) that this ID Token is intended for.
		$statement['aud'] = $consumer->key;
		// Expiration time on or after which the ID Token MUST NOT be accepted for processing.
		$statement['exp'] = wfTimestamp() + 100;
		// Time at which the JWT was issued.
		$statement['iat'] = (int)wfTimestamp();
		// String value used to associate a Client session with an ID Token, and to mitigate
		// replay attacks. The value is passed through unmodified from the Authorization Request.
		$statement['nonce'] = $request->get_parameter( 'oauth_nonce' );
		// TODO: Add auth_time, if we start tracking last login timestamp

		// Include some MediaWiki info about the user
		if ( !$user->isHidden() ) {
			$statement['username'] = $user->getName();
			$statement['editcount'] = intval( $user->getEditCount() );
			$statement['confirmed_email'] = $user->isEmailConfirmed();
			$statement['blocked'] = $user->isBlocked();
			$statement['registered'] = $user->getRegistration();
			$statement['groups'] = $user->getEffectiveGroups();
			$statement['rights'] = array_values( array_unique( $user->getRights() ) );
			$statement['grants'] = $access->get( 'grants' );
		}

		$JWT = \JWT::encode( $statement, $consumer->secret );
		$this->showResponse( $JWT, $format );
	}

	protected function handleAuthorizationForm( $requestToken, $consumerKey ) {
		$this->getOutput()->addSubtitle( $this->msg( 'mwoauth-desc' )->escaped() );

		$user = $this->getUser();
		$lang = $this->getLanguage();

		$dbr = MWOAuthUtils::getCentralDB( DB_SLAVE ); // @TODO: lazy handle
		$oauthServer = MWOAuthUtils::newMWOAuthServer();

		if ( !$consumerKey ) {
			$consumerKey = $oauthServer->getConsumerKey( $requestToken );
		}

		$cmr = MWOAuthDAOAccessControl::wrap(
			MWOAuthConsumer::newFromKey( $dbr, $consumerKey ),
			$this->getContext()
		);
		if ( !$cmr ) {
			throw new MWOAuthException( 'mwoauthserver-bad-consumer-key' );
		} elseif ( $cmr->get( 'stage' ) !== MWOAuthConsumer::STAGE_APPROVED
			&& !$cmr->getDAO()->isPendingAndOwnedBy( $user )
		) {
			throw new MWOAuthException(
				'mwoauthserver-bad-consumer',
				array(
					$cmr->get( 'name' ),
					MWOAuthUtils::getCentralUserTalk(
						MWOAuthUtils::getCentralUserNameFromId( $cmr->get( 'userId' ) )
					)
				)
			);
		}

		$this->getOutput()->addModuleStyles( array( 'mediawiki.ui', 'mediawiki.ui.button', 'ext.MWOAuth.AuthorizeForm' ) );
		$this->getOutput()->addModules( 'ext.MWOAuth.AuthorizeDialog' );

		// Check if this user has authorized grants for this consumer previously
		$existing = $oauthServer->getCurrentAuthorization( $user, $cmr->getDAO(), wfWikiId() );

		$control = new MWOAuthConsumerAcceptanceSubmitControl( $this->getContext(), array(), $dbr );
		$form = new \HTMLForm(
			$control->registerValidators( array(
				'action' => array(
					'type'    => 'hidden',
					'default' => 'accept',
				),
				'confirmUpdate' => array(
					'type'    => 'hidden',
					'default' => $existing ? 1 : 0,
				),
				'consumerKey' => array(
					'name'    => 'consumerKey',
					'type'    => 'hidden',
					'default' => $consumerKey,
				),
				'requestToken' => array(
					'name'    => 'requestToken',
					'type'    => 'hidden',
					'default' => $requestToken,
				)
			) ),
			$this->getContext()
		);
		$form->setSubmitCallback(
			function( array $data, \IContextSource $context ) use ( $control ) {
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
		$msgKey = 'mwoauth-form-description';
		$params = array(
			$this->getUser()->getName(),
			$cmr->get( 'name' ),
			$cmr->get( 'userId', 'MediaWiki\Extensions\OAuth\MWOAuthUtils::getCentralUserNameFromId' ),
		);
		if ( $cmr->get( 'wiki' ) === '*' ) {
			$msgKey .= '-allwikis';
		} else {
			$msgKey .= '-onewiki';
			$params[] = $cmr->get( 'wiki', 'MediaWiki\Extensions\OAuth\MWOAuthUtils::getWikiIdName' );
		}
		$grantsText = $this->getGrantsWikiText( $cmr->get( 'grants' ) );
		if ( $grantsText === "\n" ) {
			$msgKey .= '-nogrants';
		} else {
			$params[] = $grantsText;
		}
		$form->addHeaderText( $this->msg( $msgKey, $params )->parseAsBlock() );
		$form->addHeaderText( $this->msg( 'mwoauth-form-legal' )->text() );

		$form->suppressDefaultSubmit();
		$form->addButton( 'cancel',
			wfMessage( 'mwoauth-form-button-cancel' )->text(), null,
			array( 'class' => 'mw-mwoauth-authorize-button mw-ui-button' ) );
		$form->addButton( 'accept',
			wfMessage( 'mwoauth-form-button-approve' )->text(), null,
			array( 'class' => 'mw-mwoauth-authorize-button mw-ui-button mw-ui-constructive' ) );

		$privacyMessage = MWOAuthUtils::getSiteMessage( 'mwoauth-form-privacypolicy-link' );
		$form->addFooterText( wfMessage( $privacyMessage )->parse() );

		$this->getOutput()->addHtml(
			'<div id="mw-mwoauth-authorize-dialog" class="mw-ui-container">' );
		$status = $form->show();
		$this->getOutput()->addHtml( '</div>' );
		if ( $status instanceof \Status && $status->isOk() ) {
			// Redirect to callback url
			$this->getOutput()->redirect( $status->value['result']['callbackUrl'] );
		}
	}

	/**
	 * @param Array $grants list of grants (null is also allowed for no permissions)
	 * @return string Wikitext
	 */
	private function getGrantsWikiText( $grants ) {
		$s = '';
		foreach ( MWOAuthUtils::getGrantGroups( $grants ) as $group => $grants ) {
			if ( $group === 'hidden' ) {
				continue; // implicitly granted
			}
			$s .= "*<span class=\"mw-mwoauth-authorize-form-grantgroup\">" .
				wfMessage( "mwoauth-grant-group-$group" )->text() . "</span>\n";
			$s .= ":" . $this->getLanguage()->semicolonList(
				array_map( 'MediaWiki\Extensions\OAuth\MWOAuthUtils::grantName', $grants ) ) . "\n";
		}
		return "$s\n";
	}

	/**
	 * @param \Message $message to return to the user
	 * @param string $format the format of the response: html, raw, or json
	 */
	private function showError( $message, $format ) {
		if ( $format == 'raw' ) {
			$this->showResponse( 'Error: ' .$message->escaped(), 'raw' );
		} elseif ( $format == 'json' ) {
			$error = \FormatJSON::encode( array( 'error' => $message->getKey() ) );
			$this->showResponse( $error, 'json' );
		} elseif ( $format == 'html' ) {
			$this->getOutput()->showErrorPage( 'mwoauth-error', $message );
		}
	}

	/**
	 * @param OAuthToken $token
	 * @param string $format the format of the response: html, raw, or json
	 */
	private function returnToken( OAuthToken $token, $format  ) {
		if ( $format == 'raw' ) {
			$return = 'oauth_token=' . OAuthUtil::urlencode_rfc3986( $token->key );
			$return .= '&oauth_token_secret=' . OAuthUtil::urlencode_rfc3986( $token->secret );
			$return .= '&oauth_callback_confirmed=true';
			$this->showResponse( $return, 'raw' );
		} elseif ( $format == 'json' ) {
			$this->showResponse( \FormatJSON::encode( $token ), 'json' );
		} elseif ( $format == 'html' ) {
			$html = \Html::element(
				'li',
				array(),
				'oauth_token = ' . OAuthUtil::urlencode_rfc3986( $token->key )
			);
			$html .= \Html::element(
				'li',
				array(),
				'oauth_token_secret = ' . OAuthUtil::urlencode_rfc3986( $token->secret )
			);
			$html .= \Html::element(
				'li',
				array(),
				'oauth_callback_confirmed = true'
			);
			$html = \Html::rawElement( 'ul', array(), $html );
			$this->showResponse( $html, 'html' );
		}
	}

	/**
	 * @param string $data html or string to pass back to the user. Already escaped.
	 * @param string $format the format of the response: raw, json, or html
	 */
	private function showResponse( $data, $format  ) {
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
			$out->addHtml( $data );
		}
	}

	protected function showGrantRightsTables() {
		global $wgMWOAuthGrantPermissions;

		$out = $this->getOutput();
		$out->addModuleStyles( 'mediawiki.special' );

		$out->addWikiMsg( 'mwoauth-listgrantrights-summary' );

		$out->addHTML(
			\Html::openElement( 'table',
				array( 'class' => 'wikitable mw-oauth-listgrouprights-table' ) ) .
				'<tr>' .
				\Html::element( 'th', null, $this->msg( 'mwoauth-listgrants-grant' )->text() ) .
				\Html::element( 'th', null, $this->msg( 'mwoauth-listgrants-rights' )->text() ) .
				'</tr>'
		);

		foreach ( $wgMWOAuthGrantPermissions as $grant => $rights ) {
			$descs = array();
			$rights = array_filter( $rights ); // remove ones with 'false'
			foreach ( $rights as $permission => $granted ) {
				$descs[] = $this->msg(
					'listgrouprights-right-display',
					\User::getRightDescription( $permission ),
					'<span class="mw-oaith-listgrantrights-right-name">' . $permission . '</span>'
				)->parse();
			}
			if ( !count( $descs ) ) {
				$grantCellHtml = '';
			} else {
				sort( $descs );
				$grantCellHtml = '<ul><li>' . implode( "</li>\n<li>", $descs ) . '</li></ul>';
			}

			$id = \Sanitizer::escapeId( $grant );
			$out->addHTML( \Html::rawElement( 'tr', array( 'id' => $id ),
				"<td>" . wfMessage( "mwoauth-grant-$grant" )->escaped() . "</td>" .
				"<td>" . $grantCellHtml . '</td>'
			) );
		}

		$out->addHTML( \Html::closeElement( 'table' ) );
	}
}
