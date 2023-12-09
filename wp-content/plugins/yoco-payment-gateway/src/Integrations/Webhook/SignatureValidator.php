<?php

namespace Yoco\Integrations\Webhook;

use Yoco\Helpers\Logger;
use Yoco\Installations\InstallationsManager;
use Yoco\Integrations\Webhook\Vendors\WebhookSignatureValidator;

use function Yoco\yoco;

class SignatureValidator extends WebhookSignatureValidator {

	public function __construct() {
		parent::__construct( $this->getSecret() );
	}

	public function validate( string $webhookPayload, array $webhookHeaders ): bool {
		try {
			$this->verify( $webhookPayload, $webhookHeaders );

			return true;
		} catch ( \Throwable $th ) {
			yoco( Logger::class )->logError( sprintf( __( 'Failed to verify webhook signature. %s', 'yoco_wc_payment_gateway' ), $th->getMessage() ) );
			return false;
		}
	}

	public function getSecret(): string {
		$settings = get_option( 'woocommerce_class_yoco_wc_payment_gateway_settings', null );

		if ( ! isset( $settings['mode'] ) ) {
			yoco( Logger::class )->logError( __( 'Invalid yoco gateway settings. Missing mode.', 'yoco_wc_payment_gateway' ) );
			return '';
		}

		/**
		 * @var InstallationsManager $installation
		 */
		$installation = yoco( InstallationsManager::class );

		if ( ! $installation->hasWebhookSecret( $settings['mode'] ) ) {
			yoco( Logger::class )->logError( __( 'Failed to verify signature. Webhook secret is empty.', 'yoco_wc_payment_gateway' ) );
		}

		return $installation->getWebhookSecret( $settings['mode'] );
	}
}
