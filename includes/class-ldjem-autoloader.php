<?php
/**
 * LanceDesk Elementor Menu – Autoloader
 * 
 * Automatically loads classes using PSR-4 namespace convention
 * 
 * @package     LDJEM
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Autoloader Class
 * 
 * Maps namespace\class to file paths using PSR-4 standard
 */
class LDJEM_Autoloader {

    /**
     * Register autoloader
     */
    public static function register() {
        spl_autoload_register([__CLASS__, 'load']);
    }

    /**
     * Load class file based on namespace
     * 
     * @param string $class Full class name with namespace
     * @return void
     */
    public static function load($class) {
        // Only load classes with LDJEM prefix
        if (0 !== strpos($class, 'LDJEM_')) {
            return;
        }

        // Remove LDJEM_ prefix
        $class_name = substr($class, 6); // Remove "LDJEM_"

        // Convert namespace to file path
        // LDJEM_Admin_Dashboard → admin/class-ldjem-admin-dashboard.php
        $parts = explode('_', $class_name);
        $file_parts = array_map('strtolower', $parts);

        // Build file path
        $file_path = LDJEM_PLUGIN_DIR . 'includes/' . implode('/', $file_parts);
        $file_path = str_replace('/', '/', rtrim($file_path, '/'));
        $file_path = LDJEM_PLUGIN_DIR . 'includes/class-' . LDJEM_PREFIX . '-' . implode('-', $file_parts) . '.php';

        // Load file if it exists
        if (file_exists($file_path)) {
            require_once $file_path;
        }
    }
}

// Register autoloader
LDJEM_Autoloader::register();
