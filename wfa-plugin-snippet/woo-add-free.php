
<?php
/**
 * Plugin Name: Woo add free price
 * Author: Sabato
 * Author URI:sabatodev.com
 * Description: Override 0 price
 * version: 1.0.0
 */


if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'FREE_PRODUCT_FOR_WOOCOMMERCE_VERSION', '1.1' );
define( 'FREE_PRODUCT_FOR_WOOCOMMERCE_DIR', plugin_dir_path( __FILE__ ) );

add_filter( 'woocommerce_get_price_html', 'fpfw_price_free_zero', 9999, 2 );

function fpfw_price_free_zero( $price, $product ) {

	$free_price_txt = __( 'FREE', 'free-product-for-woocommerce' );

	if ( $product->is_type( 'variable' ) ) {

		$prices    = $product->get_variation_prices( true );
		$min_price = current( $prices['price'] );
		if ( 0 === absint( $min_price ) ) {
			$max_price     = end( $prices['price'] );
			$min_reg_price = current( $prices['regular_price'] );
			$max_reg_price = end( $prices['regular_price'] );
			if ( $min_price !== $max_price ) {
				$price  = wc_format_price_range( $free_price_txt, $max_price );
				$price .= $product->get_price_suffix();
			} elseif ( $product->is_on_sale() && $min_reg_price === $max_reg_price ) {
				$price  = wc_format_sale_price( wc_price( $max_reg_price ), $free_price_txt );
				$price .= $product->get_price_suffix();
			} else {
				$price = $free_price_txt;
			}
		}
	} elseif ( 0 === absint( $product->get_price() ) ) {
		$price = '<span class="woocommerce-Price-amount amount">' . $free_price_txt . '</span>';
	}
	return $price;
}
