<?php

namespace WeLabs\WcAuthorizeNetPaymentGateway;

/**
 * WcAuthorizeNetPaymentGateway class
 *
 * @class WcAuthorizeNetPaymentGateway The class that holds the entire WcAuthorizeNetPaymentGateway plugin
 */
final class WcAuthorizeNetPaymentGateway {

    /**
     * Plugin version
     *
     * @var string
     */
    public $version = '0.0.1';

    /**
     * Instance of self
     *
     * @var WcAuthorizeNetPaymentGateway
     */
    private static $instance = null;

    /**
     * Holds various class instances
     *
     * @since 2.6.10
     *
     * @var array
     */
    private $container = [];

    /**
     * Constructor for the WcAuthorizeNetPaymentGateway class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     */
    private function __construct() {
        $this->define_constants();

        register_activation_hook( WC_AUTHORIZE_NET_PAYMENT_GATEWAY_FILE, [ $this, 'activate' ] );
        register_deactivation_hook( WC_AUTHORIZE_NET_PAYMENT_GATEWAY_FILE, [ $this, 'deactivate' ] );

        add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
        add_action( 'woocommerce_flush_rewrite_rules', [ $this, 'flush_rewrite_rules' ] );
    }

    /**
     * Initializes the WcAuthorizeNetPaymentGateway() class
     *
     * Checks for an existing WcAuthorizeNetPaymentGateway instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        if ( self::$instance === null ) {
			self::$instance = new self();
		}

        return self::$instance;
    }

    /**
     * Magic getter to bypass referencing objects
     *
     * @since 2.6.10
     *
     * @param string $prop
     *
     * @return Class Instance
     */
    public function __get( $prop ) {
		if ( array_key_exists( $prop, $this->container ) ) {
            return $this->container[ $prop ];
		}
    }

    /**
     * Placeholder for activation function
     *
     * Nothing being called here yet.
     */
    public function activate() {
        // Rewrite rules during wc_authorize_net_payment_gateway activation
        if ( $this->has_woocommerce() ) {
            $this->flush_rewrite_rules();
        }
    }

    /**
     * Flush rewrite rules after wc_authorize_net_payment_gateway is activated or woocommerce is activated
     *
     * @since 3.2.8
     */
    public function flush_rewrite_rules() {
        // fix rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Placeholder for deactivation function
     *
     * Nothing being called here yet.
     */
    public function deactivate() {     }

    /**
     * Define all constants
     *
     * @return void
     */
    public function define_constants() {
        $this->define( 'WC_AUTHORIZE_NET_PAYMENT_GATEWAY_PLUGIN_VERSION', $this->version );
        $this->define( 'WC_AUTHORIZE_NET_PAYMENT_GATEWAY_DIR', dirname( WC_AUTHORIZE_NET_PAYMENT_GATEWAY_FILE ) );
        $this->define( 'WC_AUTHORIZE_NET_PAYMENT_GATEWAY_INC_DIR', WC_AUTHORIZE_NET_PAYMENT_GATEWAY_DIR . '/includes' );
        $this->define( 'WC_AUTHORIZE_NET_PAYMENT_GATEWAY_TEMPLATE_DIR', WC_AUTHORIZE_NET_PAYMENT_GATEWAY_DIR . '/templates' );
        $this->define( 'WC_AUTHORIZE_NET_PAYMENT_GATEWAY_PLUGIN_ASSET', plugins_url( 'assets', WC_AUTHORIZE_NET_PAYMENT_GATEWAY_FILE ) );

        // give a way to turn off loading styles and scripts from parent theme
        $this->define( 'WC_AUTHORIZE_NET_PAYMENT_GATEWAY_LOAD_STYLE', true );
        $this->define( 'WC_AUTHORIZE_NET_PAYMENT_GATEWAY_LOAD_SCRIPTS', true );
    }

    /**
     * Define constant if not already defined
     *
     * @param string      $name
     * @param string|bool $value
     *
     * @return void
     */
    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
		}
    }

    /**
     * Load the plugin after WP User Frontend is loaded
     *
     * @return void
     */
    public function init_plugin() {
        $this->includes();
        $this->init_hooks();

        /*Payment gateway settings*/
        if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
            return;
        }

        include_once WC_AUTHORIZE_NET_PAYMENT_GATEWAY_INC_DIR . '/AuthorizePaymentGateway.php';
        add_filter( 'woocommerce_payment_gateways', [ $this, 'add_authorizenet_gateway' ] );
        /*End payment gateway settings*/

        do_action( 'wc_authorize_net_payment_gateway_loaded' );
    }

    /**
     * Add custom payment method to woo payment gateway
     *
     * @param [type] $methods
     * @return void
     */
    public function add_authorizenet_gateway( $methods ) {
        $methods[] = 'AuthorizePaymentGateway';
        return $methods;
    }

    /**
     * Initialize the actions
     *
     * @return void
     */
    public function init_hooks() {
        // initialize the classes
        add_action( 'init', [ $this, 'init_classes' ], 4 );
        add_action( 'plugins_loaded', [ $this, 'after_plugins_loaded' ] );
    }

    /**
     * Include all the required files
     *
     * @return void
     */
    public function includes() {
        // include_once STUB_PLUGIN_DIR . '/functions.php';
    }

    /**
     * Init all the classes
     *
     * @return void
     */
    public function init_classes() {
        $this->container['scripts'] = new Assets();
    }

    /**
     * Executed after all plugins are loaded
     *
     * At this point wc_authorize_net_payment_gateway Pro is loaded
     *
     * @since 2.8.7
     *
     * @return void
     */
    public function after_plugins_loaded() {
        // Initiate background processes and other tasks
    }

    /**
     * Check whether woocommerce is installed and active
     *
     * @since 2.9.16
     *
     * @return bool
     */
    public function has_woocommerce() {
        return class_exists( 'WooCommerce' );
    }

    /**
     * Check whether woocommerce is installed
     *
     * @since 3.2.8
     *
     * @return bool
     */
    public function is_woocommerce_installed() {
        return in_array( 'woocommerce/woocommerce.php', array_keys( get_plugins() ), true );
    }
}
