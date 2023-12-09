<?php

namespace Yoco\Gateway;

use WC_Order;
use Yoco\Helpers\Logger;
use Yoco\Repositories\OrdersRepository;

use function Yoco\yoco;

class Notes {

	public function __construct() {
		add_action( 'yoco_payment_gateway/order/meta/yoco_order_checkout_id/updated_successfully', array( $this, 'addSessionIdNoteToOrder' ) );
		add_action( 'yoco_payment_gateway/order/meta/yoco_order_payment_id/updated_successfully', array( $this, 'addPaymentIdNoteToOrder' ) );
		add_action( 'yoco_payment_gateway/order/meta/yoco_order_refund_id/updated_successfully', array( $this, 'addRefundIdNoteToOrder' ) );
	}

	public function addSessionIdNoteToOrder( int $orderId ): void {
		$order = OrdersRepository::getById( $orderId );

		if ( empty( $order ) ) {
			yoco( Logger::class )->logError( sprintf( __( 'Failed to retrieve order (session) of ID %s.', 'yoco_wc_payment_gateway' ), $orderId ) );
			return;
		}

		$sessionId = yoco( Metadata::class )->getOrderCheckoutId( $order );

		if ( empty( $sessionId ) ) {
			yoco( Logger::class )->logError( sprintf( __( 'Failed to retrieve order session ID of ID %s.', 'yoco_wc_payment_gateway' ), $orderId ) );
			return;
		}

		$this->addNote( $order, sprintf( __( 'Yoco: Received checkout session ID (%s).', 'yoco_wc_payment_gateway' ), $sessionId ) );
	}

	public function addPaymentIdNoteToOrder( int $orderId ): void {
		$order = OrdersRepository::getById( $orderId );

		if ( empty( $order ) ) {
			yoco( Logger::class )->logError( sprintf( __( 'Failed to retrieve order (payment) of ID %s.', 'yoco_wc_payment_gateway' ), $orderId ) );
			return;
		}

		$paymentId = yoco( Metadata::class )->getOrderPaymentId( $order );

		if ( empty( $paymentId ) ) {
			yoco( Logger::class )->logError( sprintf( __( 'Failed to retrieve order payment ID of ID %s.', 'yoco_wc_payment_gateway' ), $paymentId ) );
			return;
		}

		$this->addNote( $order, sprintf( __( 'Yoco: Received payment session ID (%s).', 'yoco_wc_payment_gateway' ), $paymentId ) );
	}

	public function addRefundIdNoteToOrder( int $orderId ): void {
		$order = OrdersRepository::getById( $orderId );

		if ( empty( $order ) ) {
			yoco( Logger::class )->logError( sprintf( __( 'Failed to retrieve order (refund) of ID %s.', 'yoco_wc_payment_gateway' ), $orderId ) );
			return;
		}

		$refundId = yoco( Metadata::class )->getOrderRefundId( $order );

		if ( empty( $refundId ) ) {
			yoco( Logger::class )->logError( sprintf( __( 'Failed to retrieve order refund ID of ID %s.', 'yoco_wc_payment_gateway' ), $refundId ) );
			return;
		}

		$this->addNote( $order, sprintf( __( 'Yoco: Received refund session ID (%s).', 'yoco_wc_payment_gateway' ), $refundId ) );
	}

	public function addNote( WC_Order $order, string $note ): int {
		return $order->add_order_note( $note );
	}
}
