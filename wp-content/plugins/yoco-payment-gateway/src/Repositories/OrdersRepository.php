<?php

namespace Yoco\Repositories;

use Exception;
use WC_Order;
use Yoco\Gateway\Metadata;
use Yoco\Helpers\Logger;

use function Yoco\yoco;

class OrdersRepository {

	public static function getById( int $id ): ?WC_Order {
		$order = wc_get_order( $id );

		return is_a( $order, WC_Order::class ) ? $order : null;
	}

	public static function getByYocoCheckoutId( string $sessionId ) {
		$orders = wc_get_orders(
			array(
				'meta_key'     => Metadata::CHECKOUT_ID_ORDER_META_KEY,
				'meta_value'   => $sessionId,
				'meta_compare' => '=',
			)
		);

		if ( empty( $orders ) ) {
			yoco( Logger::class )->logInfo( sprintf( __( 'Order of checkout ID (%s) not found in the repository.', 'yoco_wc_payment_gateway' ), $sessionId ) );
			return null;
		}

		$order = array_shift( $orders );

		return is_a( $order, WC_Order::class ) ? $order : null;
	}

	public static function getByYocoPaymentId( string $paymentId ): ?WC_Order {
		$orders = wc_get_orders(
			array(
				'meta_key'     => Metadata::PAYMENT_ID_ORDER_META_KEY,
				'meta_value'   => $paymentId,
				'meta_compare' => '=',
			)
		);

		if ( empty( $orders ) ) {
			yoco( Logger::class )->logInfo( sprintf( __( 'Order of payment ID (%s) not found in the repository.', 'yoco_wc_payment_gateway' ), $paymentId ) );
			return null;
		}

		$order = array_shift( $orders );

		return is_a( $order, WC_Order::class ) ? $order : null;
	}

	public static function getByYocoRefundId( string $refundId ): ?WC_Order {
		$orders = wc_get_orders(
			array(
				'meta_key'     => Metadata::REFUND_ID_ORDER_META_KEY,
				'meta_value'   => $refundId,
				'meta_compare' => '=',
			)
		);

		if ( empty( $orders ) ) {
			yoco( Logger::class )->logInfo( sprintf( __( 'Order of refund ID (%s) not found in the repository.', 'yoco_wc_payment_gateway' ), $refundId ) );
			return null;
		}

		$order = array_shift( $orders );

		return is_a( $order, WC_Order::class ) ? $order : null;
	}

	public function getOrders( array $args ): WC_Order {
		$orders = wc_get_orders( $args );

		if ( empty( $orders ) ) {
			yoco( Logger::class )->logError( __( 'Order not found.', 'yoco_wc_payment_gateway' ) );
			throw new Exception( __( 'Order not found.', 'yoco_wc_payment_gateway' ) );
		}

		$order = array_shift( $orders );

		if ( ! is_a( $order, WC_Order::class ) ) {
			yoco( Logger::class )->logError( 'getOrders: ' . __( 'Order is not instance of WC_Order.', 'yoco_wc_payment_gateway' ) );
			throw new Exception( 'getOrders: ' . __( 'Order is not instance of WC_Order.' ) );
		}

		return $order;
	}

	public static function getOrderById( int $id ): WC_Order {
		$order = wc_get_order( $id );

		if ( ! is_a( $order, WC_Order::class ) ) {
			yoco( Logger::class )->logError( sprintf( __( 'Order ID (%s) is not instance of WC_Order.', 'yoco_wc_payment_gateway' ), $id ) );
			throw new Exception( sprintf( __( 'Order ID (%s) is not instance of WC_Order.', 'yoco_wc_payment_gateway' ), $id ) );
		}

		return $order;
	}
}
