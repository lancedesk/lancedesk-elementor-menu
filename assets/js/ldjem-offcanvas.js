/**
 * LanceDesk Elementor Menu - Off-Canvas (Slideout) Menu JavaScript
 * Premium feature: Beautiful off-canvas menu replacing WordPress fullwidth dropdowns
 * 
 * @package LDJEM
 * @since 1.0.0
 */

(function ($) {
  'use strict';

  window.__LDJEM_OFFCANVAS_BUILD = 'ldjem-offcanvas-2026-04-29-0154';

  /**
   * Off-Canvas Menu Handler
   */
  window.LDJEMOffCanvas = window.LDJEMOffCanvas || {};

  /**
   * Initialize off-canvas menu for a specific widget
   */
  LDJEMOffCanvas.init = function (widgetId, settings) {
    const self = this;
    const $hamburger = $(`[data-ldjem-id="${widgetId}"] .ldjem-hamburger-btn`);
    const $offcanvas = $(`[data-ldjem-id="${widgetId}"] .ldjem-offcanvas-wrapper`);
    const $closeBtn = $(`[data-ldjem-id="${widgetId}"] .ldjem-offcanvas-close`);
    const $menuItems = $(`[data-ldjem-id="${widgetId}"] .ldjem-offcanvas-menu-item`);
    const $wrapper = $(`[data-ldjem-id="${widgetId}"].ldjem-menu-wrapper-offcanvas`);

    if (!$offcanvas.length) {
      return;
    }

    // Store state
    const state = {
      isOpen: false,
      widgetId: widgetId,
      settings: settings || {},
      focusTrap: null,
    };

    function logLayoutDebug(context, device, enabled) {
      return;
    }

    function getCurrentDevice() {
      // Elementor editor preview mode is the source of truth when available.
      if (window.elementorFrontend && typeof window.elementorFrontend.getCurrentDeviceMode === 'function') {
        const mode = window.elementorFrontend.getCurrentDeviceMode();
        if (mode === 'mobile' || mode === 'tablet') {
          return mode;
        }
      }

      if (document.documentElement.classList.contains('elementor-device-mobile') || document.body.classList.contains('elementor-device-mobile')) {
        return 'mobile';
      }
      if (document.documentElement.classList.contains('elementor-device-tablet') || document.body.classList.contains('elementor-device-tablet')) {
        return 'tablet';
      }

      if (document.body.classList.contains('elementor-device-mobile')) {
        return 'mobile';
      }
      if (document.body.classList.contains('elementor-device-tablet')) {
        return 'tablet';
      }
      const width = window.innerWidth || document.documentElement.clientWidth;
      if (width <= 767) {
        return 'mobile';
      }
      if (width <= 1024) {
        return 'tablet';
      }
      return 'desktop';
    }

    function isOffcanvasEnabledForCurrentDevice() {
      const device = getCurrentDevice();
      const attr = $wrapper.attr(`data-offcanvas-${device}`);
      return attr === 'yes';
    }

    function syncDeviceState(context) {
      const device = getCurrentDevice();
      const enabled = isOffcanvasEnabledForCurrentDevice();
      $wrapper
        .removeClass('ldjem-device-desktop ldjem-device-tablet ldjem-device-mobile')
        .addClass(`ldjem-device-${device}`)
        .toggleClass('ldjem-offcanvas-disabled-device', !enabled)
        .toggleClass('ldjem-offcanvas-enabled-device', enabled);
      applyDeviceOverrides(device);
      logLayoutDebug(context || 'sync', device, enabled);

      if (!enabled && state.isOpen) {
        closeMenu();
      }
    }

    function getDataInt(attrName) {
      const raw = parseInt($wrapper.attr(attrName), 10);
      return Number.isFinite(raw) ? raw : 0;
    }

    function applyDirection(direction) {
      if (!direction || direction === 'inherit') return;
      if (!['left', 'right', 'top', 'bottom'].includes(direction)) return;

      $offcanvas.removeClass('direction-left direction-right direction-top direction-bottom');
      $offcanvas.addClass(`direction-${direction}`);
    }

    function applyDeviceOverrides(device) {
      const direction = $wrapper.attr(`data-direction-${device}`) || 'inherit';
      const duration = getDataInt(`data-animation-duration-${device}`);
      const panelSize = getDataInt(`data-panel-size-${device}`);
      const panelHeight = getDataInt(`data-panel-height-${device}`);

      applyDirection(direction);

      if (duration > 0) {
        $offcanvas.css('--ldjem-offcanvas-animation-speed', `${duration}ms`);
      }

      if (panelSize > 0) {
        $offcanvas.css('--ldjem-offcanvas-panel-size', `${panelSize}px`);
      }

      if (panelHeight > 0) {
        $offcanvas.css('--ldjem-offcanvas-panel-height', `${panelHeight}px`);
      }
    }

    /**
     * Open off-canvas menu
     */
    function openMenu() {
      if (state.isOpen) return;
      if (!isOffcanvasEnabledForCurrentDevice()) return;

      state.isOpen = true;

      $offcanvas.addClass('is-open');
      $offcanvas.attr('aria-hidden', 'false');
      $hamburger.attr('aria-expanded', 'true');

      // Prevent body scroll
      $('body').addClass('ldjem-offcanvas-open');

      // Setup focus trap
      self.setupFocusTrap($offcanvas, state);

      // Trigger custom event
      $(document).trigger('ldjem:offcanvas:opened', [widgetId]);
    }

    /**
     * Close off-canvas menu
     */
    function closeMenu() {
      if (!state.isOpen) return;

      state.isOpen = false;

      $offcanvas.removeClass('is-open');
      $offcanvas.attr('aria-hidden', 'true');
      $hamburger.attr('aria-expanded', 'false');

      // Restore body scroll
      $('body').removeClass('ldjem-offcanvas-open');

      // Return focus to hamburger
      $hamburger.focus();

      // Trigger custom event
      $(document).trigger('ldjem:offcanvas:closed', [widgetId]);
    }

    /**
     * Toggle off-canvas menu
     */
    function toggleMenu() {
      if (state.isOpen) {
        closeMenu();
      } else {
        openMenu();
      }
    }

    // Event: Hamburger click
    $hamburger.on('click.ldjem', function (e) {
      e.preventDefault();
      toggleMenu();
    });

    // Event: Close button click
    $closeBtn.on('click.ldjem', function (e) {
      e.preventDefault();
      closeMenu();
    });

    // Event: Escape key
    $(document).on('keydown.ldjem-' + widgetId, function (e) {
      if (e.key === 'Escape' && state.isOpen) {
        closeMenu();
      }
    });

    // Event: Menu item click (close on navigation)
    $offcanvas.on('click.ldjem', '.ldjem-offcanvas-menu-item:not(.has-children) > a', function () {
      closeMenu();
    });

    // Event: Submenu toggle - use parent link for items with children
    $offcanvas.on('click.ldjem', '.ldjem-offcanvas-menu-item.has-children > a, .ldjem-offcanvas-submenu-item.has-children > a', function (e) {
      e.preventDefault();
      const $toggleLink = $(this);
      const $parent = $toggleLink.closest('li');
      const isCurrentlyExpanded = $parent.hasClass('is-expanded');

      // Calculate max-height for smooth animation
      const $submenu = $parent.find('> .ldjem-offcanvas-submenu');
      if ($submenu.length) {
        const submenuHeight = $submenu[0].scrollHeight;
        
        if (!isCurrentlyExpanded) {
          // Expanding: set max-height to submenu height
          $submenu.css('max-height', submenuHeight + 'px');
        }
      }

      $parent.toggleClass('is-expanded');
      $toggleLink.attr('aria-expanded', $parent.hasClass('is-expanded') ? 'true' : 'false');

      // Trigger custom event for animations
      $(document).trigger('ldjem:submenu:toggled', {
        element: $toggleLink,
        isExpanded: $parent.hasClass('is-expanded'),
        level: $parent.parents('.ldjem-offcanvas-submenu').length
      });
    });

    // Event: Keyboard navigation in menu - Enhanced for nested items
    $offcanvas.on('keydown.ldjem', '.ldjem-offcanvas-menu-item a, .ldjem-offcanvas-submenu-item a', function (e) {
      const $this = $(this);
      const $parent = $this.closest('li');
      const $allFocusableItems = $offcanvas.find('a[role], a:not([role="presentation"])');
      const currentIndex = $allFocusableItems.index($this);

      switch (e.key) {
        case 'ArrowDown':
          e.preventDefault();
          if (currentIndex < $allFocusableItems.length - 1) {
            $allFocusableItems.eq(currentIndex + 1).focus();
          }
          break;

        case 'ArrowUp':
          e.preventDefault();
          if (currentIndex > 0) {
            $allFocusableItems.eq(currentIndex - 1).focus();
          }
          break;

        case 'ArrowRight':
          e.preventDefault();
          if ($parent.hasClass('has-children')) {
            if (!$parent.hasClass('is-expanded')) {
              // Expand submenu
              $parent.addClass('is-expanded');
              $this.attr('aria-expanded', 'true');
              const $submenu = $parent.find('> .ldjem-offcanvas-submenu');
              if ($submenu.length) {
                $submenu.css('max-height', $submenu[0].scrollHeight + 'px');
              }
            } else {
              // Move to first child
              const $firstChild = $parent.find('> .ldjem-offcanvas-submenu > li:first-child a');
              if ($firstChild.length) {
                $firstChild.focus();
              }
            }
          }
          break;

        case 'ArrowLeft':
          e.preventDefault();
          if ($parent.hasClass('has-children') && $parent.hasClass('is-expanded')) {
            // Collapse submenu
            $parent.removeClass('is-expanded');
            $this.attr('aria-expanded', 'false');
          } else {
            // Move to parent menu item
            const $parentMenuItem = $parent.closest('.ldjem-offcanvas-submenu').closest('li').children('a');
            if ($parentMenuItem.length) {
              $parentMenuItem.focus();
            }
          }
          break;

        case 'Enter':
        case ' ':
          // If item has children and is not expanded, expand it
          if ($parent.hasClass('has-children') && !$parent.hasClass('is-expanded')) {
            e.preventDefault();
            $parent.addClass('is-expanded');
            $this.attr('aria-expanded', 'true');
            const $submenu = $parent.find('> .ldjem-offcanvas-submenu');
            if ($submenu.length) {
              $submenu.css('max-height', $submenu[0].scrollHeight + 'px');
            }
          }
          break;
      }
    });

    // Event: Social icons (open in new tab)
    $offcanvas.on('click.ldjem', '.ldjem-offcanvas-social-link', function (e) {
      const href = $(this).attr('href');
      if (href) {
        window.open(href, '_blank');
        e.preventDefault();
      }
    });

    // Store functions on state for external access
    state.openMenu = openMenu;
    state.closeMenu = closeMenu;
    state.toggleMenu = toggleMenu;
    state.syncDeviceState = syncDeviceState;

    // Expose state
    $offcanvas.data('ldjem-offcanvas-state', state);
    syncDeviceState('init');

    return state;
  };

  /**
   * Setup focus trap for accessibility
   */
  LDJEMOffCanvas.setupFocusTrap = function ($container, state) {
    const focusableElements = $container.find(
      'a, button, [tabindex]:not([tabindex="-1"])'
    );
    const firstElement = focusableElements.first();
    const lastElement = focusableElements.last();

    $container.on('keydown.ldjem-focus-trap', function (e) {
      if (e.key !== 'Tab') return;

      if (e.shiftKey) {
        // Shift + Tab
        if ($(document.activeElement).is(firstElement)) {
          e.preventDefault();
          lastElement.focus();
        }
      } else {
        // Tab
        if ($(document.activeElement).is(lastElement)) {
          e.preventDefault();
          firstElement.focus();
        }
      }
    });

    state.focusTrap = {
      $container: $container,
      firstElement: firstElement,
      lastElement: lastElement,
    };
  };

  /**
   * jQuery plugin for off-canvas menu
   */
  $.fn.ldjemOffCanvas = function (options) {
    const settings = $.extend({}, options);

    return this.each(function () {
      const $this = $(this);
      const widgetId = $this.data('ldjem-id');

      if (!widgetId) {
        return;
      }

      LDJEMOffCanvas.init(widgetId, settings);
    });
  };

  /**
   * Auto-initialize off-canvas menus on page load
   */
  $(document).ready(function () {
    $('.ldjem-offcanvas-wrapper').each(function () {
      const $this = $(this);
      const widgetId = $this.data('ldjem-id');

      if (widgetId) {
        LDJEMOffCanvas.init(widgetId);
      }
    });
  });

  /**
   * Handle responsive behavior - close menu on resize
   */
  let resizeTimer;
  $(window).on('resize.ldjem', function () {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function () {
      // Sync all off-canvas instances on resize
      $('.ldjem-offcanvas-wrapper').each(function () {
        const state = $(this).data('ldjem-offcanvas-state');
        if (state && state.syncDeviceState) {
          state.syncDeviceState('resize');
        }
      });
    }, 250);
  });

  /**
   * Destroy/cleanup off-canvas menu
   */
  LDJEMOffCanvas.destroy = function (widgetId) {
    const $offcanvas = $(`[data-ldjem-id="${widgetId}"] .ldjem-offcanvas-wrapper`);
    const $hamburger = $(`[data-ldjem-id="${widgetId}"] .ldjem-hamburger-btn`);

    if ($offcanvas.length) {
      $offcanvas.off('.ldjem').off('.ldjem-focus-trap').removeData('ldjem-offcanvas-state');
      $hamburger.off('.ldjem');
      $(document).off('.ldjem-' + widgetId);
    }
  };

  /**
   * Public API
   */
  window.LDJEMOffCanvas = LDJEMOffCanvas;

})(jQuery);

/* Elementor Editor Support - Reinitialize on widget update */
if (window.elementorFrontend) {
  elementorFrontend.hooks.addAction(
    'frontend/element_ready/ldjem_menu.default',
    function ($scope) {
      $scope.find('.ldjem-offcanvas-wrapper').ldjemOffCanvas();
      // Keep preview responsive modes in sync.
      setTimeout(function () {
        $scope.find('.ldjem-offcanvas-wrapper').each(function () {
          const state = $(this).data('ldjem-offcanvas-state');
          if (state && state.syncDeviceState) {
            state.syncDeviceState('elementor-ready');
          }
        });
      }, 100);
    }
  );

  // Elementor device preview toggles do not always fire window resize.
  // Observe class changes and force sync/log when preview mode changes.
  const previewObserver = new MutationObserver(function () {
    jQuery('.ldjem-offcanvas-wrapper').each(function () {
      const state = jQuery(this).data('ldjem-offcanvas-state');
      if (state && state.syncDeviceState) {
        state.syncDeviceState('elementor-preview-toggle');
      }
    });
  });

  if (document.documentElement) {
    previewObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
  }
  if (document.body) {
    previewObserver.observe(document.body, { attributes: true, attributeFilter: ['class'] });
  }
}
