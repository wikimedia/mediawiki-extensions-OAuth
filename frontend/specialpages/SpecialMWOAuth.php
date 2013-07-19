<?php

class SpecialMWOAuth extends UnlistedSpecialPage {
	function __construct() {
		parent::__construct( 'MWOAuth' );
	}

	public function execute( $subpage ) {

		$request = $this->getRequest();
		$format = $request->getVal( 'format', 'raw' );
		if ( !in_array( $subpage, array( 'initiate', 'authorize', 'token' ) ) ) {
			$this->showError( wfMessage( 'oauth-client-invalidrequest' ), $format );
		}

		try {

			$store = $this->getStorage();
			$oauthServer = new MWOAuthServer( $store );
			$oauthServer->add_signature_method( new OAuthSignatureMethod_HMAC_SHA1() );
			$oauthServer->add_signature_method( new MWOAuthSignatureMethod_RSA_SHA1( $store ) );

			switch ( $subpage ) {
				case 'initiate':
					$OAuthRequest = MWOAuthRequest::fromRequest( $request );
					wfDebugLog( 'OAuth', __METHOD__ . ": Consumer '{$OAuthRequest->getConsumerKey()}' getting temporary credentials" );
					// fetch_request_token does the version, freshness, and sig checks
					$token = $oauthServer->fetch_request_token( $OAuthRequest );
					$this->returnToken( $token, $format );
					break;
				case 'authorize':
					$mwUser = $this->getUser();
					$requestToken = $request->getVal( 'oauth_token', false ); //oauth_token
					$consumerKey = $request->getVal( 'oauth_consumer_key', false ); //oauth_key
					wfDebugLog( 'OAuth', __METHOD__ . ": doing 'authorize' with '$requestToken' '$consumerKey' for '{$mwUser->getName()}'" );
					if ( !$requestToken || !$consumerKey ) {
						throw new MWOAuthException( 'mwoauth-bad-request' );
					}
					// TODO? Test that $requestToken exists in memcache

					if ( $mwUser->isAnon() ) {
						//redirect to login
						$query['returnto'] = $this->getTitle( 'authorize' )->getPrefixedText();
						$query['returntoquery'] = wfArrayToCgi( array(
							'oauth_token' => $requestToken,
							'oauth_consumer_key' => $consumerKey
						) );
						$loginPage = SpecialPage::getTitleFor( 'UserLogin' );
						$url = $loginPage->getLocalURL( $query );
						$this->doRedirect( $url );
						return;
					}

					if ( $request->getVal( 'doAuthorize', false ) ) {
						// Require POST
						if ( !$request->wasPosted() ) {
							throw new MWOAuthException( 'mwoauth-not-posted' );
						}

						// Check csrf token
						$CSRFToken = $request->getVal( 'formToken', false );
						if ( !$mwUser->matchEditToken( $CSRFToken, 'OAuth:Authorize' ) ) {
							throw new MWOAuthException( 'mwoauth-bad-csrf-token' );
						}
						// Create Grant
						$callback = $oauthServer->authorize(
							$consumerKey,
							$requestToken,
							$mwUser
						);
						// Redirect to callback url
						$this->doRedirect( $callback );
					} else {
						$consumer = MWOAuthDAOAccessControl::wrap(
								MWOAuthConsumer::newFromKey( MWOAuthUtils::getCentralDB( DB_SLAVE ), $consumerKey ),
								$this->getContext()
						);
						if ( !$consumer ) {
							throw new MWOAuthException( 'mwoauth-bad-request' );
						}
						$formParams = array(
							'consumerKey' => $consumerKey,
							'requestToken' => $requestToken,
							'grants' => $consumer->get( 'grants' ),
							'description' => array (
								'user' => User::newFromId( $consumer->get( 'userId') )->getName(),
								'name' => $consumer->get( 'name'),
								'version' => $consumer->get( 'version'),
								'description' => $consumer->get( 'description'),
								'wiki' => $consumer->get( 'wiki'),
							)
						);
						$this->showAuthorizeForm( $formParams );
					}
					break;
				case 'token':
					$OAuthRequest = MWOAuthRequest::fromRequest( $request );
					wfDebugLog( 'OAuth', "/token: '{$OAuthRequest->get_parameter( 'oauth_consumer_key' )}' getting temporary credentials" );
					$token = $oauthServer->fetch_access_token( $OAuthRequest );
					$this->returnToken( $token, $format );

					break;
				default:
					throw new OAuthException( 'mwoauth-invalid-method' );
			}

		} catch ( MWOAuthException $exception ) {
			wfDebugLog( 'OAuth', __METHOD__ . ": Exception " . $exception->getMessage() );
			$this->showError( $exception->getMessage(), $format );
		} catch ( OAuthException $exception ) {
			wfDebugLog( 'OAuth', __METHOD__ . ": Exception " . $exception->getMessage() );
			$this->showError( $exception->getMessage(), $format );
		}
	}

	private function doRedirect( $url ) {
		$output = $this->getOutput();
		$output->redirect( $url );
	}

	private function getStorage() {
		global $wgMemc; //TODO instance of config
		return new MWOAuthDataStore( $wgMemc, wfGetDB( DB_MASTER ) );
	}

	private function showAuthorizeForm( $params ) {
		$out = $this->getOutput();
		$user = $this->getUser();

		$out->addSubtitle( $this->msg( 'mwoauth-desc' )->escaped() );
		$out->addHTML( Html::element( 'p', array(), $this->msg( 'mwoauth-form-description' )->text() ) );
		$out->addHTML( Html::element( 'p', array(), $this->msg( 'mwoauth-form-legal' )->text() ) );

		$out->addHTML( Html::element( 'p', array(), $this->msg( 'mwoauth-authorize-form' )->text() ) );
		$description = '';
		foreach ( $params['description'] as $descKey => $descVal ) {
			/* Messages:
			'mwoauth-authorize-form-user'
			'mwoauth-authorize-form-name'
			'mwoauth-authorize-form-description'
			'mwoauth-authorize-form-version'
			'mwoauth-authorize-form-wiki'*/
			$description .= Html::element(
				'li',
				array(),
				$this->msg( 'mwoauth-authorize-form-' . $descKey, $descVal )->text()
			);
		}
		$out->addHTML( Html::rawElement( 'ul', array(), $description ) );

		$out->addHTML( $this->getGrantsHtml( $params['grants'] ) );

		$fields['mwoauth-form-confirmation'] = Xml::submitButton( $this->msg( 'mwoauth-form-button-approve' )->text() );
		$form = Xml::buildForm( $fields );
		$form = Xml::fieldset( null, $form );

		$form .= Html::hidden( 'oauth_consumer_key', $params['consumerKey'] );
		$form .= Html::hidden( 'oauth_token', $params['requestToken'] );
		$form .= Html::hidden( 'formToken', $this->getUser()->getEditToken( 'OAuth:Authorize' ) );
		$form .= Html::hidden( 'doAuthorize', '1' );

		$form = Xml::tags( 'form',
			array(
				'action' => $this->getTitle( 'authorize' )->getFullURL(),
				'method' => 'post'
			),
			$form
		);

		$out->addHTML( $form );
	}

	/**
	 * @param Array $grants list of grants (null is also allowed for no permissions)
	 */
	private function getGrantsHtml( $grants ) {
		// TODO: dom / styling
		$html = Html::element(
			'p',
			array(),
			$this->msg( 'mwoauth-grants-heading' )->text()
		);

		if ( $grants === array() || is_null( $grants ) ) {
			$html .= Html::rawElement(
				'ul',
				array(),
				Html::element(
					'li',
					array(),
					$this->msg( 'mwoauth-grants-nogrants' )->text()
				)
			);
		} else {
			$list = '';
			foreach ( $grants as $grant) {
				// Give grep a chance to find the usages:
				// mwoauth-grants-editpages, mwoauth-grants-editmyinterface, mwoauth-grants-editinterface,
				// mwoauth-grants-movepages, mwoauth-grants-createpages, mwoauth-grants-deletepages,
				// mwoauth-grants-upload
				$list .= Html::element(
					'li',
					array(),
					$this->msg( "mwoauth-grants-$grant" )->text()
				);
			}
			$html .= Html::rawElement( 'ul', array(), $list );
		}
		return $html;
	}

	/**
	 *
	 *
	 * @param string $message message key to return to the user
	 * @param string $format the format of the response: json, xml, or html
	 */
	private function showError( $message, $format ) {
		if ( $format == 'html' ) {
			$this->getOutput()->showErrorPage( 'mwoauth-error', $message );
		} elseif ( $format == 'raw' ) {
			$this->showResponse( 'Error: ' . wfMessage( $message )->escaped(), 'raw' );
		} elseif ( $format == 'json' ) {
			$error = json_encode( array( 'error' => wfMessage( $message )->escaped() ) );
			$this->showResponse( $error, 'raw' );
		}
	}

	/**
	 *
	 *
	 * @param array $response values to give back to the client
	 * @param string $format the format of the response: json, xml, or html
	 */
	private function returnToken( OAuthToken $token, $format  ) {
		if ( $format == 'raw' ) {
			$return = 'oauth_token=' . OAuthUtil::urlencode_rfc3986( $token->key );
			$return .= '&oauth_token_secret=' . OAuthUtil::urlencode_rfc3986( $token->secret );
			$this->showResponse( $return, 'raw' );
		} elseif ( $format == 'json' ) {
			#unset( $token->code );
			#unset( $token->accessTokenKey );
			$this->showResponse( FormatJSON::encode( $token ), 'raw' );
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
			$html = Html::rawElement( 'ul', array(), $html );
			$this->showResponse( $html, 'html' );
		}
	}


	/**
	 *
	 *
	 * @param string $response html or string to pass back to the user. Already escaped.
	 * @param string $format the format of the response: raw, or otherwise
	 */
	private function showResponse( $response, $format  ) {
		$out = $this->getOutput();
		if ( $format == 'raw' ) {
			$out->setArticleBodyOnly( true );
			$out->enableClientCache( false );
			$out->preventClickjacking();
			$out->clearHTML();
			$out->addHTML( $response );
		} else {
			$out->addHtml( $response );
		}
	}
}
