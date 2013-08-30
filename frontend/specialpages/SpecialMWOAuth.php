<?php

class SpecialMWOAuth extends UnlistedSpecialPage {
	function __construct() {
		parent::__construct( 'MWOAuth' );
	}

	public function execute( $subpage ) {
		$this->setHeaders();
		$request = $this->getRequest();
		$format = $request->getVal( 'format', 'raw' );
		if ( !in_array( $subpage, array( 'initiate', 'authorize', 'token' ) ) ) {
			$this->showError( 'oauth-client-invalidrequest', $format );
		}

		try {
			$oauthServer = MWOAuthUtils::newMWOAuthServer();
			switch ( $subpage ) {
				case 'initiate':
					$OAuthRequest = MWOAuthRequest::fromRequest( $request );
					wfDebugLog( 'OAuth', __METHOD__ . ": Consumer '{$OAuthRequest->getConsumerKey()}' getting temporary credentials" );
					// fetch_request_token does the version, freshness, and sig checks
					$token = $oauthServer->fetch_request_token( $OAuthRequest );
					$this->returnToken( $token, $format );
					break;
				case 'authorize':
					//TODO: most of the "controller" logic should be move somewhere else
					$format = $request->getVal( 'format', 'html' );
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

					// Check to make sure this user is the same user
					// on the central wiki
					$centralId = MWOAuthUtils::getCentralIdFromLocalUser( $mwUser );
					if ( !$centralId ) {
						// For now, just abort and give them hints to fix in
						// the error message. TODO: if we can fix the issue with
						// a few redirects, do that here.
						throw new MWOAuthException( 'mwoauth-authorize-form-invalid-user' );
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
							$mwUser,
							$request->getCheck( 'grants-update' )
						);
						// Redirect to callback url
						$this->doRedirect( $callback );
					} else {
						$dbr = MWOAuthUtils::getCentralDB( DB_SLAVE );
						$consumer = MWOAuthDAOAccessControl::wrap(
							MWOAuthConsumer::newFromKey( $dbr, $consumerKey ),
							$this->getContext()
						);
						if ( !$consumer ) {
							throw new MWOAuthException( 'mwoauth-bad-request' );
						}
						if ( $consumer->get( 'stage' ) !== MWOAuthConsumer::STAGE_APPROVED
							&& !$consumer->getDAO()->isPendingAndOwnedBy( $mwUser )
						) {
							throw new MWOAuthException( 'mwoauth-invalid-authorization-not-approved' );
						}
						// Check if this user has authorized grants for this consumer previously
						$existing = $oauthServer->getCurrentAuthorization(
							$mwUser,
							$consumer->getDAO()
						);

						$formParams = array(
							'consumerKey' => $consumerKey,
							'requestToken' => $requestToken,
							'grants' => $consumer->get( 'grants' ),
							'existing' => $existing,
							'description' => array (
								'user' => MWOAuthUtils::getCentralUserNameFromId(
									$consumer->get( 'userId' ) ),
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

	private function showAuthorizeForm( $params ) {
		$out = $this->getOutput();

		$out->addSubtitle( $this->msg( 'mwoauth-desc' )->escaped() );
		if ( !$params['existing'] ) {
			$out->addHTML( Html::element( 'p', array(), $this->msg( 'mwoauth-form-description' )->text() ) );
		} else {
			// User has already authorized this consumer
			$lang = $this->getLanguage();
			$grants = $params['existing']->get( 'grants');
			$grantList = is_null( $grants ) ? $this->msg( 'mwoauth-grants-nogrants' )->text() : $lang->commaList( $grants );
			$out->addWikiMsg( 'mwoauth-form-existing',
				$grantList,
				$params['existing']->get( 'wiki'),
				$params['existing']->get( 'accepted' ) );
		}
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
		if ( $params['existing'] ) {
			// Checkbox to allow the user to update their permission to match the Consumer's request
			$fields['mwoauth-form-confirmation-update'] = Xml::check( 'grants-update', false );
		}
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
	 * @return string
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
			foreach ( $grants as $grant ) {
				$list .= Html::element(
					'li',
					array(),
					MWOAuthUtils::grantName( $grant )
				);
			}
			$html .= Html::rawElement( 'ul', array(), $list );
		}
		return $html;
	}

	/**
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
	 * @param OAuthToken $token
	 * @param string $format the format of the response: json, xml, or html
	 */
	private function returnToken( OAuthToken $token, $format  ) {
		if ( $format == 'raw' ) {
			$return = 'oauth_token=' . OAuthUtil::urlencode_rfc3986( $token->key );
			$return .= '&oauth_token_secret=' . OAuthUtil::urlencode_rfc3986( $token->secret );
			$return .= '&oauth_callback_confirmed=true';
			$this->showResponse( $return, 'raw' );
		} elseif ( $format == 'json' ) {
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
