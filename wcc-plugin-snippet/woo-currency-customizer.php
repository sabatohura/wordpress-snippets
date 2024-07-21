<?php
/**
 * Plugin Name: Woo Currency customizer
 * Author: Sabato
 * Author URI:sabatodev.com
 * Description: Override Rwandan currency code
 * version: 1.0.0
 */

add_filter( 'woocommerce_currencies', 'add_my_currency' );
     function add_my_currency( $currencies ) {
       $currencies['RWF'] = __( 'Rwandan Francs', 'woocommerce' );
        return $currencies;
}

add_filter('woocommerce_currency_symbol', 'add_my_currency_symbol', 10, 2);
    function add_my_currency_symbol( $currency_symbol, $currency ) {
        switch( $currency ) {
            case 'RWF': $currency_symbol = 'RWF'; break;
        }
        return $currency_symbol;
}