<?php
/**
 * Plugin Name: Wc Authorize Net Payment Gateway
 * Plugin URI:  https://aiarnob.com/plugins/wc-authorize-payment-gateway/
 * Description: WC Authorize.net Payment Gateway
 * Version: 0.0.1
 * Author: Aminur Islam Arnob
 * Author URI: https://aiarnob.com/plugins/wc-authorize-payment-gateway/
 * Text Domain: wc-authorize-net-payment-gateway
 * WC requires at least: 5.0.0
 * Domain Path: /languages/
 * License: GPLv2 or later
 */
use WeLabs\WcAuthorizeNetPaymentGateway\WcAuthorizeNetPaymentGateway;

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'WC_AUTHORIZE_NET_PAYMENT_GATEWAY_FILE' ) ) {
    define( 'WC_AUTHORIZE_NET_PAYMENT_GATEWAY_FILE', __FILE__ );
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Load Wc_Authorize_Net_Payment_Gateway Plugin when all plugins loaded
 *
 * @return \WeLabs\WcAuthorizeNetPaymentGateway\WcAuthorizeNetPaymentGateway;
 */
function welabs_wc_authorize_net_payment_gateway() {
    return WcAuthorizeNetPaymentGateway::init();
}

// Lets Go....
welabs_wc_authorize_net_payment_gateway();
