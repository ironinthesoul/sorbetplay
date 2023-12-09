<?php

namespace Yoco\Integrations\Yoco\Webhooks\Processors;

use WC_Order;
use WP_REST_Response;
use Yoco\Helpers\Logger;
use Yoco\Integrations\Yoco\Requests\Refund;
use Yoco\Integrations\Yoco\Webhooks\Models\WebhookPayload;

use function Yoco\yoco;

class RefundSucceededWebhookProcessor extends WebhookProcessor {

	private ?WC_Order $order = null;

	public function process( WebhookPayload $payload ): WP_REST_Response {
		if ( null === $this->order = $this->getOrderByCheckoutId( $payload->getCheckoutId() ) ) {
			return $this->sendFailResponse( 403 );
		}

		if ( 'refunded' === $this->order->get_status() ) {
			yoco( Logger::class )->logInfo( sprintf( __( 'Order is already refunded, no need to update the order', 'yoco_wc_payment_gateway' ) ) );
			return $this->sendSuccessResponse();
		}

		try {
			$request = new Refund();
			$refund  = $request->refund( $this->order );

			if ( null === $refund ) {
				return $this->sendSuccessResponse();
			} elseif ( 'completed' === $refund->get_status() ) {
				do_action( 'yoco_payment_gateway/payment/completed', $this->order, $payload );

				return $this->sendSuccessResponse();
			} else {
				yoco( Logger::class )->logError( __( sprintf( 'Failed to complete refund of order #%s - wrong order status.', $this->order->get_id() ), 'yoco_wc_payment_gateway' ) );
				return $this->sendFailResponse( 403 );
			}
		} catch ( \Throwable $th ) {
			yoco( Logger::class )->logError( __( sprintf( 'Failed to complete refund of order #%s.', $this->order->get_id() ), 'yoco_wc_payment_gateway' ) );
			return $this->sendFailResponse( 403 );
		}
	}
}
