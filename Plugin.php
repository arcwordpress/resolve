<?php
/**
 * Plugin Name: Resolve
 * Description: A ticket system and help desk solution
 * Version: 0.1.0
 * Author: ARCWP
 * Author URI: https://arcwp.ca
 * Text Domain: resolve
 */

namespace Resolve;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('RESOLVE_VERSION', '0.1.0');
define('RESOLVE_PATH', plugin_dir_path(__FILE__));
define('RESOLVE_URL', plugin_dir_url(__FILE__));
define('RESOLVE_FILE', __FILE__);

// Register SPL autoloader for Resolve classes
spl_autoload_register(function ($class) {
    $prefix = 'Resolve\\';
    $base_dir = RESOLVE_PATH . 'includes/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

class Plugin
{
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->init();
    }

    /**
     * Initialize the plugin
     */
    private function init()
    {
        // Register activation and deactivation hooks
        register_activation_hook(RESOLVE_FILE, [$this, 'activate']);
        register_deactivation_hook(RESOLVE_FILE, [$this, 'deactivate']);

        // Hook for any initialization that needs to happen on 'init'
        add_action('init', [$this, 'onInit']);
    }

    public function onInit()
    {
        // Register collections
        Collections\TicketStatuses::register();
        Collections\Tickets::register();

        do_action('resolve_loaded');
    }

    /**
     * Plugin activation
     */
    public function activate()
    {
        // Check if Gateway is active
        if (!class_exists('\Gateway\Plugin')) {
            deactivate_plugins(plugin_basename(RESOLVE_FILE));
            wp_die('Resolve requires the Gateway plugin to be installed and activated.');
        }

        // Install database tables (statuses first, then tickets)
        Database\TicketStatusesDatabase::install();
        Database\TicketsDatabase::install();

        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Plugin deactivation
     */
    public function deactivate()
    {
        flush_rewrite_rules();
    }
}

// Initialize plugin
Plugin::getInstance();
