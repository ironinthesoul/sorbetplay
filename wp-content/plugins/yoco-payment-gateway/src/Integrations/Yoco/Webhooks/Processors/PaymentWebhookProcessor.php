<?php

namespace Yoco\Integrations\Yoco\Webhooks\Processors;

use WC_Order;
use WP_REST_Response;
use Yoco\Helpers\Logger;
use Yoco\Integrations\Yoco\Webhooks\Models\WebhookPayload;

use function Yoco\yoco;

class PaymentWebhookProcessor extends WebhookProcessor {

	private ?WC_Order $order = null;

	public function process( WebhookPayload $payload ): WP_REST_Response {
		if ( null === $this->order = $this->getOrderByCheckoutId( $payload->getCheckoutId() ) ) {
			return $this->sendFailResponse( 404 );
		}

		if ( true === $this->order->update_status( 'processing' ) ) {
			do_action( 'yoco_payment_gateway/payment/completed', $this->order, $payload );
			return $this->sendSuccessResponse();
		} else {
			yoco( Logger::class )->logError( __( sprintf( 'Failed to complete payment of order #%s.', $this->order->get_id() ), 'yoco_wc_payment_gateway' ) );
			return $this->sendFailResponse( 500 );
		}
	}
}
