/**
 * LanceDesk Elementor Menu – Admin JavaScript
 * 
 * Admin panel and Elementor editor enhancements
 * 
 * NOTE: This file will be populated in Phase 6 (Frontend JavaScript & Interactivity)
 * 
 * @package LDJEM
 */

(function($) {
    'use strict';

    var LDJEMAdmin = {
        init: function() {
            this.bindPresetAutoApply();
        },

        bindPresetAutoApply: function() {
            if (typeof window.elementor === 'undefined' || !window.elementor.hooks || !window.ldjemAdmin) {
                return;
            }

            var self = this;

            window.elementor.hooks.addAction('panel/open_editor/widget', function(panel, model) {
                if (!model || !self.isTargetWidget(model)) {
                    return;
                }

                self.attachPresetListener(model);
            });
        },

        isTargetWidget: function(model) {
            var widgetName = (window.ldjemAdmin && window.ldjemAdmin.widget_name) ? window.ldjemAdmin.widget_name : 'ldjem_menu';
            return model.get('widgetType') === widgetName;
        },

        attachPresetListener: function(model) {
            var self = this;

            if (model._ldjemPresetBound) {
                return;
            }

            model._ldjemPresetBound = true;
            model.on('change:settings:offcanvas_preset', function() {
                var presetId = model.getSetting('offcanvas_preset');
                if (!self.isPresetAutoApplyEnabled(model)) {
                    return;
                }
                self.applyPresetToModel(model, presetId);
            });

            model.on('change:settings:offcanvas_preset_auto_apply', function() {
                var autoApplyEnabled = self.isPresetAutoApplyEnabled(model);
                var presetId = model.getSetting('offcanvas_preset');

                if (!autoApplyEnabled || !presetId || presetId === 'none') {
                    return;
                }

                self.applyPresetToModel(model, presetId);
            });
        },

        isPresetAutoApplyEnabled: function(model) {
            var settingValue = model.getSetting('offcanvas_preset_auto_apply');

            if (typeof settingValue === 'undefined' || settingValue === null || settingValue === '') {
                return true;
            }

            return settingValue === 'yes';
        },

        applyPresetToModel: function(model, presetId) {
            if (!presetId || presetId === 'none') {
                return;
            }

            var allPresetSettings = window.ldjemAdmin.preset_settings || {};
            var presetSettings = allPresetSettings[presetId];

            if (!presetSettings || typeof presetSettings !== 'object') {
                return;
            }

            Object.keys(presetSettings).forEach(function(key) {
                var value = presetSettings[key];
                if (typeof model.setSetting === 'function') {
                    model.setSetting(key, value);
                } else if (model.get('settings') && typeof model.get('settings').set === 'function') {
                    model.get('settings').set(key, value);
                }
            });
        }
    };

    $(document).ready(function() {
        LDJEMAdmin.init();
    });

})(jQuery);
