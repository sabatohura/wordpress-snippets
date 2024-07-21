<?php
/**
 * Plugin Name: Woo commerce checkout fields customizer
 * Author: Sabato
 * Author URI:sabatohura.github.io
 * Description: Overriding Woo commerce fields and functions, removing unneccessary checkout fields 
 * version: 1.0.0
 */
add_filter('woocommerce_default_address_fields' ,'wfr_remove_checkout_fields' );

function wfr_remove_checkout_fields( $fields ) { 
    // remove unwanted fields
    unset($fields['state']);
    unset($fields['postcode']);
    unset($fields['city']);

    // overiding address  to District and Sector Label
    $fields['address_1']['label'] = 'District';
    $fields['address_2']['label'] = 'Sector';

    // overiding address  to District and Sector Place Holder
    $fields['address_1']['placeholder'] = 'District';
    $fields['address_2']['placeholder'] = 'Sector';
    return $fields; 
}
