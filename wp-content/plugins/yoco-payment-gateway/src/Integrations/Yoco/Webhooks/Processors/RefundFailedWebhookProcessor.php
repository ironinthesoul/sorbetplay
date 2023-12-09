<?php

namespace Yoco\Integrations\Yoco\Webhooks\Processors;

use WC_Order;
use WP_REST_Response;
use Yoco\Gateway\Notes;
use Yoco\Integrations\Yoco\Webhooks\Models\WebhookPayload;

use function Yoco\yoco;

class RefundFailedWebhookProcessor extends WebhookProcessor {

	private ?WC_Order $order = null;

	public function process( WebhookPayload $payload ): WP_REST_Response {
		if ( null === $this->order = $this->getOrderByCheckoutId( $payload->getCheckoutId() ) ) {
			return $this->sendFailResponse( 403, __( 'Could not find the order for this checkout.', 'yoco_wc_payment_gateway' ) );
		}

		if ( 'refunded' === $this->order->get_status() ) {
			return $this->sendFailResponse( 403, __( 'Order for this checkout is already refunded.', 'yoco_wc_payment_gateway' ) );
		}

		yoco( Notes::class )->addNote(
			$this->order,
			$payload->hasFailureReason()
			? sprintf( __( 'Yoco: %s', 'yoco_wc_payment_gateway' ), $payload->getFailureReason() )
			: __( 'Yoco: Failed to refund the order.', 'yoco_wc_payment_gateway' )
		);
		return $this->sendSuccessResponse();
	}
}
