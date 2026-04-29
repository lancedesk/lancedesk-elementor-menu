<?php
/**
 * LanceDesk Elementor Menu – Plugin Uninstall
 * 
 * Handles plugin uninstallation and cleanup
 * 
 * @package LDJEM
 */

// Exit if not being uninstalled
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Define plugin constants if not already defined
if (!defined('LDJEM_PREFIX')) {
    define('LDJEM_PREFIX', 'ldjem');
}

/**
 * Clean up plugin data on uninstall
 * 
 * This function:
 * - Removes plugin options
 * - Clears transients
 * - Removes any custom post types data
 * - Resets any settings
 */
function ldjem_uninstall_plugin() {
    global $wpdb;

    // Get all blog IDs (for multisite support)
    if (is_multisite()) {
        $blog_ids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->blogs}");
        foreach ($blog_ids as $blog_id) {
            switch_to_blog($blog_id);
            ldjem_cleanup_blog_data();
        }
        restore_current_blog();
    } else {
        ldjem_cleanup_blog_data();
    }
}

/**
 * Clean up data for a specific blog
 */
function ldjem_cleanup_blog_data() {
    // Remove plugin options
    delete_option(LDJEM_PREFIX . '_activation_date');
    delete_option(LDJEM_PREFIX . '_settings');
    delete_option(LDJEM_PREFIX . '_version');

    // Remove transients
    delete_transient(LDJEM_PREFIX . '_activated');
    delete_transient(LDJEM_PREFIX . '_cache_menus');

    // Remove any scheduled events (if added in future)
    wp_clear_scheduled_hook(LDJEM_PREFIX . '_scheduled_task');

    // You could also remove plugin-specific user metadata if needed:
    // delete_metadata('user', 0, LDJEM_PREFIX . '_user_settings', '', true);
}

// Run cleanup
ldjem_uninstall_plugin();
