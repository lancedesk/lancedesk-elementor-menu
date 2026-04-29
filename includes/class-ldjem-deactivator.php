<?php
/**
 * LanceDesk Elementor Menu – Deactivation Handler
 * 
 * Handles plugin deactivation cleanup
 * 
 * @package     LDJEM
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Deactivation Handler Class
 * 
 * Cleans up on plugin deactivation
 */
class LDJEM_Deactivator {

    /**
     * Deactivate plugin
     * 
     * @return void
     */
    public static function deactivate() {
        // Clean up transients
        delete_transient(LDJEM_PREFIX . '_activated');

        // Remove any scheduled cron jobs (if any added in future)
        // wp_clear_scheduled_hook('ldjem_scheduled_task');

        // Flush rewrite rules
        flush_rewrite_rules();

        // Note: We intentionally do NOT delete plugin options
        // Users might want to reactivate and keep their settings
    }
}
