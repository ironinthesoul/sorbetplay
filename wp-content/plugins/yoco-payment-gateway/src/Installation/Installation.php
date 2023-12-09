<?php

namespace Yoco\Installation;

use Exception;
use Yoco\Helpers\Logger;
use Yoco\Core\Constants;

use function Yoco\yoco;

class Installation {

	private ?array $settings = null;

	public function getSettings(): array {
		if ( null === $this->settings ) {
			$settings       = wp_parse_args(
				get_option( 'woocommerce_class_yoco_wc_payment_gateway_settings' ),
				array(
					'live_secret_key' => '',
					'test_secret_key' => '',
				)
			);
			$this->settings = wp_parse_args( $this->getPostedData(), $settings );
		}

		return $this->settings;
	}

	public function isEnabled() {
		return isset( $this->getSettings()['enabled'] ) ? wc_string_to_bool( $this->getSettings()['enabled'] ) : '';
	}

	public function getMode() {
		return isset( $this->getSettings()['mode'] ) ? $this->getSettings()['mode'] : '';
	}

	public function getSecretKey() {
		return isset( $this->getSettings()[ $this->getMode() . '_secret_key' ] ) ? $this->getSettings()[ $this->getMode() . '_secret_key' ] : '';
	}

	public function getApiUrl(): string {
		return $this->getInstallationApiUrl();
	}

	public function getApiBearer(): string {
		return 'Bearer ' . $this->getSecretKey();
	}

	public function getIdMetaKey(): string {

		return 'yoco_payment_gateway_installation_' . $this->getMode() . '_id';
	}

	public function getWebhookSecretMetaKey(): string {
		return 'yoco_payment_gateway_' . $this->getMode() . '_webhook_secret';
	}

	public function saveId( string $id ): void {
		$key        = $this->getIdMetaKey();
		$current_id = get_option( $key );

		if ( $current_id === $id ) {
			return;
		}

		$updated = update_option( $key, $id );

		if ( false === $updated ) {
			yoco( Logger::class )->logError( __( 'Failed to save Webhook Secret option.', 'yoco_wc_payment_gateway' ) );

			throw new Exception( __( 'Failed to save Webhook Secret option.', 'yoco_wc_payment_gateway' ) );
		}
	}

	public function getId() {
		return get_option( $this->getIdMetaKey() );
	}

	public function saveWebhookSecret( string $secret ): void {
		$key            = $this->getWebhookSecretMetaKey();
		$current_secret = get_option( $key );

		if ( $current_secret === $secret ) {
			return;
		}

		$updated = update_option( $key, $secret );

		if ( false === $updated ) {
			yoco( Logger::class )->logError( __( 'Failed to save installation ID option.', 'yoco_wc_payment_gateway' ) );

			throw new Exception( __( 'Failed to save installation ID option.', 'yoco_wc_payment_gateway' ) );
		}
	}

	public function getWebhookSecret() {
		return get_option( $this->getWebhookSecretMetaKey() );
	}

	private function getInstallationApiUrl(): string {
		/**
		 * @var Constants $constants
		 */
		$constants = yoco( Constants::class );

		if ( $constants->hasInstallationApiUrl() ) {
			return $constants->getInstallationApiUrl();
		}

		return '';
	}

	private function getPostedData() {
		if ( ! isset( $_POST ) || ! is_array( $_POST ) ) {
			return;
		}

		$data = array();

		foreach ( $_POST as $key => $value ) {
			if ( false === strpos( $key, 'woocommerce_class_yoco_wc_payment_gateway' ) ) {
				continue;
			}

			$data[ str_replace( 'woocommerce_class_yoco_wc_payment_gateway_', '', $key ) ] = sanitize_text_field( $value );
		}

		return $data;
	}
}
