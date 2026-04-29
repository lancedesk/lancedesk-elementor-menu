<?php
/**
 * LanceDesk Elementor Menu – Security Utility Class
 * 
 * Handles input sanitization, output escaping, and security verification
 * 
 * @package     LDJEM
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Security Utility Class
 * 
 * Provides methods for sanitizing input, escaping output, and verifying nonces
 */
class LDJEM_Security {

    /**
     * Sanitize integer input
     * 
     * @param mixed $value Value to sanitize
     * @param int   $default Default value if invalid
     * @return int
     */
    public static function sanitize_int($value, $default = 0) {
        $sanitized = intval($value);
        return is_numeric($value) ? $sanitized : $default;
    }

    /**
     * Sanitize text field input
     * 
     * @param mixed $value Value to sanitize
     * @param string $default Default value if invalid
     * @return string
     */
    public static function sanitize_text($value, $default = '') {
        if (!is_string($value)) {
            return $default;
        }
        return sanitize_text_field($value);
    }

    /**
     * Sanitize CSS class input
     * 
     * @param string $class CSS class name(s)
     * @param string $default Default if invalid
     * @return string
     */
    public static function sanitize_class($class, $default = '') {
        if (empty($class)) {
            return $default;
        }

        // Allow multiple classes separated by space
        $classes = explode(' ', $class);
        $sanitized = array_map('sanitize_html_class', $classes);
        return implode(' ', array_filter($sanitized));
    }

    /**
     * Sanitize HTML content (limited tags allowed)
     * 
     * @param string $html HTML content to sanitize
     * @return string
     */
    public static function sanitize_html($html) {
        $allowed_html = [
            'a'      => ['href' => true, 'title' => true, 'target' => true],
            'br'     => [],
            'em'     => [],
            'strong' => [],
            'span'   => ['class' => true],
            'p'      => ['class' => true],
        ];

        return wp_kses($html, $allowed_html);
    }

    /**
     * Sanitize menu selection
     * 
     * Verifies menu exists and is accessible
     * 
     * @param int $menu_id Menu ID
     * @return int|false Menu ID or false if invalid
     */
    public static function sanitize_menu_id($menu_id) {
        $menu_id = intval($menu_id);

        // Get menu to verify it exists
        $menu = wp_get_nav_menu_object($menu_id);

        if (empty($menu)) {
            return false;
        }

        return $menu_id;
    }

    /**
     * Escape HTML attribute
     * 
     * @param string $text Text to escape
     * @return string
     */
    public static function escape_attr($text) {
        return esc_attr($text);
    }

    /**
     * Escape HTML content
     * 
     * @param string $text Text to escape
     * @return string
     */
    public static function escape_html($text) {
        return esc_html($text);
    }

    /**
     * Escape URL
     * 
     * @param string $url URL to escape
     * @return string
     */
    public static function escape_url($url) {
        return esc_url($url);
    }

    /**
     * Escape for JavaScript
     * 
     * @param mixed $data Data to encode
     * @return string
     */
    public static function escape_js($data) {
        return wp_json_encode($data);
    }

    /**
     * Create nonce for form submission
     * 
     * @param string $action Action name
     * @return string
     */
    public static function create_nonce($action) {
        return wp_create_nonce($action);
    }

    /**
     * Verify nonce from request
     * 
     * @param string $nonce Nonce value
     * @param string $action Action name
     * @return bool
     */
    public static function verify_nonce($nonce, $action) {
        return wp_verify_nonce($nonce, $action);
    }

    /**
     * Check user capability
     * 
     * @param string $capability Capability to check
     * @param int|null $object_id Optional object ID
     * @return bool
     */
    public static function check_capability($capability, $object_id = null) {
        if (null === $object_id) {
            return current_user_can($capability);
        }

        return current_user_can($capability, $object_id);
    }

    /**
     * Verify request is from admin area
     * 
     * @return bool
     */
    public static function is_admin_request() {
        return is_admin() && !wp_doing_ajax();
    }

    /**
     * Verify request is AJAX request
     * 
     * @return bool
     */
    public static function is_ajax_request() {
        return wp_doing_ajax();
    }

    /**
     * Verify request is from Elementor editor
     * 
     * @return bool
     */
    public static function is_elementor_editor() {
        return isset($_REQUEST['action']) && 'elementor_ajax' === $_REQUEST['action'];
    }
}
