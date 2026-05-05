<?php
/**
 * LanceDesk Elementor Menu – Activation Handler
 * 
 * Handles plugin activation checks and setup
 * 
 * @package     LDJEM
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Activation Handler Class
 * 
 * Performs necessary checks and setup on plugin activation
 */
class LDJEM_Activator {

    /**
     * Activate plugin
     * 
     * @return void
     */
    public static function activate() {
        // Check WordPress version
        if (version_compare(get_bloginfo('version'), '5.0', '<')) {
            wp_die(
                esc_html__('LanceDesk Responsive Menu for Elementor requires WordPress 5.0 or higher.', 'lancedesk-responsive-menu-for-elementor')
            );
        }

        // Check PHP version
        if (version_compare(PHP_VERSION, '7.4', '<')) {
            wp_die(
                esc_html__('LanceDesk Responsive Menu for Elementor requires PHP 7.4 or higher.', 'lancedesk-responsive-menu-for-elementor')
            );
        }

        // Check if Elementor is active
        if (!self::is_elementor_active()) {
            wp_die(
                sprintf(
                    /* translators: %s: Required plugin name. */
                    esc_html__('LanceDesk Responsive Menu for Elementor requires %s to be installed and activated.', 'lancedesk-responsive-menu-for-elementor'),
                    'Elementor'
                )
            );
        }

        // Check Elementor version
        if (!self::is_elementor_version_compatible()) {
            wp_die(
                sprintf(
                    /* translators: %s: Minimum required Elementor version. */
                    esc_html__('LanceDesk Responsive Menu for Elementor requires Elementor %s or higher.', 'lancedesk-responsive-menu-for-elementor'),
                    '3.0'
                )
            );
        }

        // Set activation flag and timestamp
        set_transient(LDJEM_PREFIX . '_activated', 1, 30); // 30 seconds to show activation notice
        update_option(LDJEM_PREFIX . '_activation_date', current_time('mysql'));

        // Flush rewrite rules if needed
        flush_rewrite_rules();
    }

    /**
     * Check if Elementor plugin is active
     * 
     * @return bool
     */
    private static function is_elementor_active() {
        return did_action('elementor/loaded') || class_exists('\Elementor\Plugin');
    }

    /**
     * Check if Elementor version is compatible
     * 
     * @return bool
     */
    private static function is_elementor_version_compatible() {
        if (!defined('ELEMENTOR_VERSION')) {
            return false;
        }
        return version_compare(ELEMENTOR_VERSION, '3.0', '>=');
    }
}
