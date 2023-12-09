<?php

namespace Yoco\Integrations\Yoco\Webhooks\Processors;

use WC_Order;
use WP_REST_Response;
use Yoco\Repositories\OrdersRepository;

abstract class WebhookProcessor {

	protected function sendSuccessResponse(): WP_REST_Response {
		return new WP_REST_Response();
	}

	protected function sendFailResponse( int $status, string $description = '' ): WP_REST_Response {
		return new WP_REST_Response(
			array(
				'description' => $description,
			),
			$status
		);
	}

	protected function getOrderByCheckoutId( string $checkoutId ): ?WC_Order {
		return OrdersRepository::getByYocoCheckoutId( $checkoutId );
	}
}
