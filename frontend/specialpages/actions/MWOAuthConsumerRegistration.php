<?php
/*
 (c) Aaron Schulz 2013, GPL

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
 * Page that has registration request form and consumer update form
 */
class MWOAuthConsumerRegistration extends SpecialPage {
	public function __construct() {
		parent::__construct( 'MWOAuthConsumerRegistration', 'mwoauthproposeconsumer' );
	}

	public function execute( $par ) {
		$request = $this->getRequest();

		$block = $this->getUser()->getBlock();
		if ( $block ) {
			throw new UserBlockedError( $block );
		} elseif ( wfReadOnly() ) {
			throw new ReadOnlyError();
		}

		$this->setHeaders();
		$this->getOutput()->disallowUserJs();

		switch ( $par ) {
		case 'propose':
			if ( !$this->getUser()->isAllowed( 'mwoauthproposeconsumer' ) ) {
				throw new PermissionsError( 'mwoauthproposeconsumer' );
			}

			$form = new HTMLForm(
				array(
					'name' => array(
						'type' => 'text',
						'label-message' => 'mwoauth-consumer-name',
						'size' => '45',
						'required' => true
					),
					'version' => array(
						'type' => 'text',
						'label-message' => 'mwoauth-consumer-version',
						'required' => true,
						'default' => "1.0"
					),
					'description' => array(
						'type' => 'textarea',
						'label-message' => 'mwoauth-consumer-description',
						'required' => true,
						'rows' => 5
					),
					'callbackUrl' => array(
						'type' => 'text',
						'label-message' => 'mwoauth-consumer-callbackurl',
						'required' => true
					),
					'email' => array(
						'type' => 'text',
						'label-message' => 'mwoauth-consumer-email',
						'required' => true
					),
					'wiki' => array(
						'type' => 'text',
						'label-message' => 'mwoauth-consumer-wiki',
						'required' => true,
						'default' => '*'
					),
					'grants' => array(
						'type' => 'textarea',
						'label-message' => 'mwoauth-consumer-grantsneeded',
						'required' => true,
						'default' => FormatJSON::encode( MWOAuthConsumer::newGrants() ),
						'rows' => 5
					),
					'restrictions' => array(
						'type' => 'textarea',
						'label-message' => 'mwoauth-consumer-restrictions',
						'required' => true,
						'default' => FormatJSON::encode( MWOAuthConsumer::newRestrictions() ),
						'rows' => 5
					),
					'rsaKey' => array(
						'type' => 'textarea',
						'label-message' => 'mwoauth-consumer-rsakey',
						'required' => false,
						'default' => '',
						'rows' => 5
					)
				),
				$this->getContext()
			);
			$form->setSubmitCallback( function( array $data, IContextSource $context ) {
				$data['action'] = 'propose';
				$controller = new MWOAuthConsumerSubmitControl( $context, $data );
				return $controller->submit();
			} );
			$form->setWrapperLegendMsg( 'mwoauthconsumerregistration-propose-legend' );
			$form->setSubmitTextMsg( 'mwoauthconsumerregistration-propose-submit' );
			$form->addPreText(
				$this->msg( 'mwoauthconsumerregistration-propose-text' )->parseAsBlock() );

			$status = $form->show();
			if ( $status instanceof Status && $status->isOk() ) {
				$this->getOutput()->addWikiMsg( 'mwoauthconsumerregistration-proposed',
					$status->value['result']->get( 'consumerKey' ),
					$status->value['result']->get( 'secretKey' ) );
				$this->getOutput()->returnToMain();
			}
			break;
		case 'update':
			if ( !$this->getUser()->isAllowed( 'mwoauthupdateconsumer' ) ) {
				throw new PermissionsError( 'mwoauthupdateconsumer' );
			}

			$form = new HTMLForm(
				array(
					'name' => array(
						'type' => 'text',
						'label-message' => 'mwoauth-consumer-name',
						'size' => '45',
						'required' => true
					),
					'consumerKey' => array(
						'type' => 'text',
						'label-message' => 'mwoauth-consumer-key',
						'size' => '40',
						'required' => true
					),
					'restrictions' => array(
						'type' => 'textarea',
						'label-message' => 'mwoauth-consumer-restrictions',
						'required' => true,
						'rows' => 5
					),
					'secretKey' => array(
						'type' => 'text',
						'label-message' => 'mwoauth-consumer-secretkey',
						'size' => '32',
						'required' => false
					),
					'rsaKey' => array(
						'type' => 'textarea',
						'label-message' => 'mwoauth-consumer-rsakey',
						'required' => false,
						'default' => '',
						'rows' => 5
					),
					'reason' => array(
						'type' => 'text',
						'label-message' => 'mwoauth-consumer-reason',
						'required' => true
					)
				),
				$this->getContext()
			);
			$form->setSubmitCallback( function( array $data, IContextSource $context ) {
				$data['action'] = 'update';
				$controller = new MWOAuthConsumerSubmitControl( $context, $data );
				return $controller->submit();
			} );
			$form->setWrapperLegendMsg( 'mwoauthconsumerregistration-update-legend' );
			$form->setSubmitTextMsg( 'mwoauthconsumerregistration-update-submit' );
			$form->addPreText(
				$this->msg( 'mwoauthconsumerregistration-update-text' )->parseAsBlock() );

			$status = $form->show();
			if ( $status instanceof Status && $status->isOk() ) {
				$this->getOutput()->addWikiMsg( 'mwoauthconsumerregistration-updated' );
				$this->getOutput()->returnToMain();
			}
			break;
		default:
			$this->getOutput()->addWikiMsg( 'mwoauthconsumerregistration-maintext' );
		}
	}
}
