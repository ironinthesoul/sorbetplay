<?php

namespace Yoco\Gateway;

use WC_Order;
use Yoco\Integrations\Yoco\Webhooks\Models\WebhookPayload;

class Metadata {

	public const CHECKOUT_ID_ORDER_META_KEY = 'yoco_order_checkout_id';

	public const CHECKOUT_URL_ORDER_META_KEY = 'yoco_order_checkout_url';

	public const PAYMENT_ID_ORDER_META_KEY = 'yoco_order_payment_id';

	public const REFUND_ID_ORDER_META_KEY = 'yoco_order_refund_id';

	public function __construct() {
		add_action( 'yoco_payment_gateway/checkout/created', array( $this, 'updateOrderCheckoutMeta' ), 10, 2 );
		add_action( 'yoco_payment_gateway/payment/completed', array( $this, 'updateOrderPaymentId' ), 10, 2 );
		add_action( 'yoco_payment_gateway/order/refunded', array( $this, 'updateOrderRefundId' ), 10, 2 );
	}

	public function updateOrderCheckoutMeta( WC_Order $order, array $data ): void {
		$this->updateOrderMeta( $order, self::CHECKOUT_ID_ORDER_META_KEY, $data['id'] );
		$this->updateOrderMeta( $order, self::CHECKOUT_URL_ORDER_META_KEY, $data['redirectUrl'] );
	}

	public function getOrderCheckoutId( WC_Order $order ): string {
		return $this->getOrderMeta( $order, self::CHECKOUT_ID_ORDER_META_KEY );
	}

	public function getOrderCheckoutUrl( WC_Order $order ): string {
		return $this->getOrderMeta( $order, self::CHECKOUT_URL_ORDER_META_KEY );
	}

	public function updateOrderPaymentId( WC_Order $order, WebhookPayload $payload ): void {
		$this->updateOrderMeta( $order, self::PAYMENT_ID_ORDER_META_KEY, $payload->getPaymentId() );
	}

	public function getOrderPaymentId( WC_Order $order ): string {
		return $this->getOrderMeta( $order, self::PAYMENT_ID_ORDER_META_KEY );
	}

	public function updateOrderRefundId( WC_Order $order, array $data ): void {
		$this->updateOrderMeta( $order, self::REFUND_ID_ORDER_META_KEY, $data['refundId'] );
	}

	public function getOrderRefundId( WC_Order $order ): string {
		return $this->getOrderMeta( $order, self::REFUND_ID_ORDER_META_KEY );
	}

	public function updateOrderMeta( WC_Order $order, string $key, string $value ): void {
		$order->update_meta_data( $key, $value );
		$order->save_meta_data();
		$action = false !== $order->get_meta( $key ) ? 'updated_successfully' : 'updated_unsuccessfully';

		do_action( "yoco_payment_gateway/order/meta/{$key}/{$action}", $order->get_id() );
	}

	public function getOrderMeta( WC_Order $order, string $key ): string {
		$meta = $order->get_meta( $key );

		return is_string( $meta ) ? $meta : '';
	}
}
