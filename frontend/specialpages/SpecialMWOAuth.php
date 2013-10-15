<?php
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
class SpecialMWOAuth extends UnlistedSpecialPage {
	function __construct() {
		parent::__construct( 'MWOAuth' );
	}

	public function execute( $subpage ) {
		global $wgMWOAuthSecureTokenTransfer;

		$this->setHeaders();

		$user = $this->getUser();
		$request = $this->getRequest();
		$format = $request->getVal( 'format', 'raw' );

		try {
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
						$query['returnto'] = $this->getTitle( 'authorize' )->getPrefixedText();
						$query['returntoquery'] = wfArrayToCgi( array(
							'oauth_token'        => $requestToken,
							'oauth_consumer_key' => $consumerKey
						) );
						$loginPage = SpecialPage::getTitleFor( 'UserLogin' );
						$url = $loginPage->getLocalURL( $query );
						$this->getOutput()->redirect( $url );
					} else {
						if ( $request->wasPosted() && $request->getCheck( 'cancel' ) ) {
							// Show acceptance cancellation confirmation
							$this->getOutput()->addSubtitle(
								$this->msg( 'mwoauth-desc' )->escaped() );
							$this->getOutput()->addWikiMsg( 'mwoauth-acceptance-cancelled' );
							$this->getOutput()->addReturnTo( Title::newMainPage() );
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
						throw new MWOAuthException( 'mwoauth-bad-request' );
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
				default:
					$format = $request->getVal( 'format', 'html' );
					$this->showError( 'mwoauth-bad-request', $format );
			}
		} catch ( MWOAuthException $exception ) {
			wfDebugLog( 'OAuth', __METHOD__ . ": Exception " . $exception->getMessage() );
			$this->showError( $exception->getMessage(), $format );
		} catch ( OAuthException $exception ) {
			wfDebugLog( 'OAuth', __METHOD__ . ": Exception " . $exception->getMessage() );
			$this->showError( $exception->getMessage(), $format );
		}

		$this->getOutput()->addModuleStyles( 'ext.MWOAuth.BasicStyles' );
	}

	protected function handleAuthorizationForm( $requestToken, $consumerKey ) {
		$this->getOutput()->addSubtitle( $this->msg( 'mwoauth-desc' )->escaped() );

		$user = $this->getUser();
		$lang = $this->getLanguage();

		$dbr = MWOAuthUtils::getCentralDB( DB_SLAVE ); // @TODO: lazy handle
		$oauthServer = MWOAuthUtils::newMWOAuthServer();

		$cmr = MWOAuthDAOAccessControl::wrap(
			MWOAuthConsumer::newFromKey( $dbr, $consumerKey ),
			$this->getContext()
		);
		if ( !$cmr ) {
			throw new MWOAuthException( 'mwoauth-bad-request' );
		} elseif ( $cmr->get( 'stage' ) !== MWOAuthConsumer::STAGE_APPROVED
			&& !$cmr->getDAO()->isPendingAndOwnedBy( $user )
		) {
			throw new MWOAuthException( 'mwoauth-invalid-authorization-not-approved' );
		}

		$this->getOutput()->addModuleStyles( array( 'mediawiki.ui', 'ext.MWOAuth.AuthorizeForm' ) );
		$this->getOutput()->addModules( 'ext.MWOAuth.AuthorizeDialog' );

		// Check if this user has authorized grants for this consumer previously
		$existing = $oauthServer->getCurrentAuthorization( $user, $cmr->getDAO() );

		$control = new MWOAuthConsumerAcceptanceSubmitControl( $this->getContext(), array(), $dbr );
		$form = new HTMLForm(
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
			function( array $data, IContextSource $context ) use ( $control ) {
				if ( $context->getRequest()->getCheck( 'cancel' ) ) { // sanity
					throw new MWException( 'Received request for a form cancellation.' );
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
			$cmr->get( 'userId', 'MWOAuthUtils::getCentralUserNameFromId' ),
		);
		if ( $cmr->get( 'wiki' ) === '*' ) {
			$msgKey .= '-allwikis';
		} else {
			$msgKey .= '-onewiki';
			$params[] = $cmr->get( 'wiki', 'MWOAuthUtils::getWikiIdName' );
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
		$form->addFooterText( wfMessage( 'mwoauth-form-privacypolicy-link' )->parse() );

		$this->getOutput()->addHtml(
			'<div id="mw-mwoauth-authorize-dialog" class="mw-ui-container">' );
		$status = $form->show();
		$this->getOutput()->addHtml( '</div>' );
		if ( $status instanceof Status && $status->isOk() ) {
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
			$s .= "*<strong>" . wfMessage( "mwoauth-grant-group-$group" )->text() . "</strong>\n";
			$s .= ":" . $this->getLanguage()->semicolonList(
				array_map( 'MWOAuthUtils::grantName', $grants ) ) . "\n";
		}
		return "$s\n";
	}

	/**
	 * @param string $message message key to return to the user
	 * @param string $format the format of the response: html, raw, or json
	 */
	private function showError( $message, $format ) {
		if ( $format == 'raw' ) {
			$this->showResponse( 'Error: ' . wfMessage( $message )->escaped(), 'raw' );
		} elseif ( $format == 'json' ) {
			$error = FormatJSON::encode( array( 'error' => wfMessage( $message )->escaped() ) );
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
			$this->showResponse( FormatJSON::encode( $token ), 'json' );
		} elseif ( $format == 'html' ) {
			$html = Html::element(
				'li',
				array(),
				'oauth_token = ' . OAuthUtil::urlencode_rfc3986( $token->key )
			);
			$html .= Html::element(
				'li',
				array(),
				'oauth_token_secret = ' . OAuthUtil::urlencode_rfc3986( $token->secret )
			);
			$html .= Html::element(
				'li',
				array(),
				'oauth_callback_confirmed = true'
			);
			$html = Html::rawElement( 'ul', array(), $html );
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
}
