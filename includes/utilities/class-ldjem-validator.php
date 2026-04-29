<?php
/**
 * LanceDesk Elementor Menu – Validator Utility Class
 * 
 * Provides validation methods for user input
 * 
 * @package     LDJEM
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Validator Utility Class
 * 
 * Validates user inputs and settings
 */
class LDJEM_Validator {

    /**
     * Validate menu selection
     * 
     * @param int $menu_id Menu ID to validate
     * @return bool|WP_Error True if valid, WP_Error otherwise
     */
    public static function validate_menu_selection($menu_id) {
        $menu_id = intval($menu_id);

        if (empty($menu_id)) {
            return new WP_Error(
                'empty_menu_id',
                esc_html__('Menu ID is required', LDJEM_TEXT_DOMAIN)
            );
        }

        $menu = wp_get_nav_menu_object($menu_id);
        if (empty($menu)) {
            return new WP_Error(
                'invalid_menu_id',
                esc_html__('Selected menu does not exist', LDJEM_TEXT_DOMAIN)
            );
        }

        return true;
    }

    /**
     * Validate menu depth setting
     * 
     * @param int $depth Menu depth value
     * @return bool|WP_Error True if valid, WP_Error otherwise
     */
    public static function validate_menu_depth($depth) {
        $depth = intval($depth);

        if ($depth < 1 || $depth > 4) {
            return new WP_Error(
                'invalid_depth',
                sprintf(
                    esc_html__('Menu depth must be between 1 and 4, got %d', LDJEM_TEXT_DOMAIN),
                    $depth
                )
            );
        }

        return true;
    }

    /**
     * Validate layout option
     * 
     * @param string $layout Layout option to validate
     * @return bool|WP_Error True if valid, WP_Error otherwise
     */
    public static function validate_layout($layout) {
        $layout = sanitize_key($layout);
        $valid_layouts = array_keys(LDJEM_Helpers::get_layout_options());

        if (!in_array($layout, $valid_layouts, true)) {
            return new WP_Error(
                'invalid_layout',
                sprintf(
                    esc_html__('Invalid layout option: %s', LDJEM_TEXT_DOMAIN),
                    $layout
                )
            );
        }

        return true;
    }

    /**
     * Validate animation option
     * 
     * @param string $animation Animation option to validate
     * @return bool|WP_Error True if valid, WP_Error otherwise
     */
    public static function validate_animation($animation) {
        $animation = sanitize_key($animation);
        $valid_animations = array_keys(LDJEM_Helpers::get_animation_options());

        if (!in_array($animation, $valid_animations, true)) {
            return new WP_Error(
                'invalid_animation',
                sprintf(
                    esc_html__('Invalid animation option: %s', LDJEM_TEXT_DOMAIN),
                    $animation
                )
            );
        }

        return true;
    }

    /**
     * Validate breakpoint setting
     * 
     * @param string $breakpoint Breakpoint name
     * @return bool|WP_Error True if valid, WP_Error otherwise
     */
    public static function validate_breakpoint($breakpoint) {
        $breakpoint = sanitize_key($breakpoint);
        $breakpoints = LDJEM_Helpers::get_breakpoints();

        if (!isset($breakpoints[$breakpoint])) {
            return new WP_Error(
                'invalid_breakpoint',
                sprintf(
                    esc_html__('Invalid breakpoint: %s', LDJEM_TEXT_DOMAIN),
                    $breakpoint
                )
            );
        }

        return true;
    }

    /**
     * Validate CSS class string
     * 
     * @param string $classes CSS classes to validate
     * @return bool|WP_Error True if valid, WP_Error otherwise
     */
    public static function validate_css_classes($classes) {
        if (empty($classes)) {
            return true; // Empty is valid
        }

        if (!is_string($classes)) {
            return new WP_Error(
                'invalid_css_class_type',
                esc_html__('CSS classes must be a string', LDJEM_TEXT_DOMAIN)
            );
        }

        // Check for invalid characters
        if (!preg_match('/^[a-zA-Z0-9\s\-_]+$/', $classes)) {
            return new WP_Error(
                'invalid_css_class_characters',
                esc_html__('CSS classes contain invalid characters', LDJEM_TEXT_DOMAIN)
            );
        }

        return true;
    }

    /**
     * Validate alignment option
     * 
     * @param string $alignment Alignment value to validate
     * @return bool|WP_Error True if valid, WP_Error otherwise
     */
    public static function validate_alignment($alignment) {
        $alignment = sanitize_key($alignment);
        $valid_alignments = ['flex-start', 'center', 'flex-end', 'space-between', 'space-around'];

        if (!in_array($alignment, $valid_alignments, true)) {
            return new WP_Error(
                'invalid_alignment',
                sprintf(
                    esc_html__('Invalid alignment option: %s', LDJEM_TEXT_DOMAIN),
                    $alignment
                )
            );
        }

        return true;
    }

    /**
     * Validate color value
     * 
     * @param string $color Color value to validate
     * @return bool|WP_Error True if valid, WP_Error otherwise
     */
    public static function validate_color($color) {
        if (empty($color)) {
            return true; // Empty is valid (use default)
        }

        // Check for valid hex color
        if (preg_match('/^#[a-fA-F0-9]{6}$/', $color)) {
            return true;
        }

        // Check for valid rgb/rgba
        if (preg_match('/^rgba?\(/', $color)) {
            return true;
        }

        return new WP_Error(
            'invalid_color_format',
            esc_html__('Invalid color format', LDJEM_TEXT_DOMAIN)
        );
    }

    /**
     * Validate spacing value (px, em, rem, %)
     * 
     * @param string|int $spacing Spacing value
     * @return bool|WP_Error True if valid, WP_Error otherwise
     */
    public static function validate_spacing($spacing) {
        if (empty($spacing)) {
            return true;
        }

        if (is_numeric($spacing)) {
            return true; // Allow plain numbers (default unit)
        }

        if (preg_match('/^\d+(\.\d+)?(px|em|rem|%|ch|vw|vh|vmin|vmax)$/', $spacing)) {
            return true;
        }

        return new WP_Error(
            'invalid_spacing_format',
            sprintf(
                esc_html__('Invalid spacing format: %s', LDJEM_TEXT_DOMAIN),
                $spacing
            )
        );
    }

    /**
     * Validate widget settings array
     * 
     * @param array $settings Widget settings
     * @return bool|WP_Error True if valid, WP_Error if any setting is invalid
     */
    public static function validate_widget_settings($settings) {
        // Validate menu selection
        if (isset($settings['menu_id'])) {
            $result = self::validate_menu_selection($settings['menu_id']);
            if (is_wp_error($result)) {
                return $result;
            }
        }

        // Validate menu depth
        if (isset($settings['menu_depth'])) {
            $result = self::validate_menu_depth($settings['menu_depth']);
            if (is_wp_error($result)) {
                return $result;
            }
        }

        // Validate desktop layout
        if (isset($settings['desktop_layout'])) {
            $result = self::validate_layout($settings['desktop_layout']);
            if (is_wp_error($result)) {
                return $result;
            }
        }

        // Validate mobile layout
        if (isset($settings['mobile_layout'])) {
            $result = self::validate_layout($settings['mobile_layout']);
            if (is_wp_error($result)) {
                return $result;
            }
        }

        return true;
    }
}
