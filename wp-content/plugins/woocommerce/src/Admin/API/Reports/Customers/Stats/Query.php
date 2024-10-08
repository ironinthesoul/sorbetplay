<?php
/**
 * Class for parameter-based Customers Report Stats querying
 *
 * Example usage:
 * $args = array(
 *          'registered_before'   => '2018-07-19 00:00:00',
 *          'registered_after'    => '2018-07-05 00:00:00',
 *          'page'                => 2,
 *          'avg_order_value_min' => 100,
 *          'country'             => 'GB',
 *         );
 * $report = new \Automattic\WooCommerce\Admin\API\Reports\Customers\Stats\Query( $args );
 * $mydata = $report->get_data();
 */

namespace Automattic\WooCommerce\Admin\API\Reports\Customers\Stats;

defined( 'ABSPATH' ) || exit;

use Automattic\WooCommerce\Admin\API\Reports\Query as ReportsQuery;

/**
 * API\Reports\Customers\Stats\Query
 *
 * @deprecated 9.3.0 Customers\Stats\Query class is deprecated, please use Reports\Customers\Query with a custom name, GenericQuery or \WC_Object_Query instead.
 */
class Query extends ReportsQuery {

	/**
	 * Valid fields for Customers report.
	 *
	 * @deprecated 9.3.0 Customers\Stats\Query class is deprecated, please use Reports\Customers\Query with a custom name, GenericQuery or \WC_Object_Query instead.
	 *
	 * @return array
	 */
	protected function get_default_query_vars() {
		return array(
			'per_page' => get_option( 'posts_per_page' ), // not sure if this should be the default.
			'page'     => 1,
			'order'    => 'DESC',
			'orderby'  => 'date_registered',
			'fields'   => '*', // @todo Needed?
		);
	}

	/**
	 * Get product data based on the current query vars.
	 *
	 * @deprecated 9.3.0 Customers\Stats\Query class is deprecated, please use Reports\Customers\Query with a custom name, GenericQuery or \WC_Object_Query instead.
	 *
	 * @return array
	 */
	public function get_data() {
		$args = apply_filters( 'woocommerce_analytics_customers_stats_query_args', $this->get_query_vars() );

		$data_store = \WC_Data_Store::load( 'report-customers-stats' );
		$results    = $data_store->get_data( $args );
		return apply_filters( 'woocommerce_analytics_customers_stats_select_query', $results, $args );
	}
}
