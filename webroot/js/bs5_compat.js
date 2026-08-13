/**
 * Puente Bootstrap 3 -> Bootstrap 5.
 * El markup heredado (navbar de Croogo, elements de Personas, ajax_modal.js)
 * usa los atributos data-* de BS3 y la API de plugins de jQuery, que BS5 eliminó.
 */
(function ($) {
	'use strict';

	if (typeof bootstrap === 'undefined') {
		return;
	}

	var ATTR_MAP = {
		'data-toggle': 'data-bs-toggle',
		'data-target': 'data-bs-target',
		'data-dismiss': 'data-bs-dismiss',
		'data-parent': 'data-bs-parent',
		'data-placement': 'data-bs-placement',
		'data-content': 'data-bs-content',
		'data-trigger': 'data-bs-trigger',
		'data-html': 'data-bs-html',
		'data-container': 'data-bs-container',
		'data-backdrop': 'data-bs-backdrop',
		'data-keyboard': 'data-bs-keyboard',
		'data-ride': 'data-bs-ride',
		'data-slide': 'data-bs-slide',
		'data-slide-to': 'data-bs-slide-to'
	};

	function upgrade(root) {
		Object.keys(ATTR_MAP).forEach(function (oldAttr) {
			var newAttr = ATTR_MAP[oldAttr];
			var selector = '[' + oldAttr + ']';
			var nodes = Array.prototype.slice.call(root.querySelectorAll(selector));

			if (root.nodeType === 1 && root.hasAttribute(oldAttr)) {
				nodes.push(root);
			}

			nodes.forEach(function (el) {
				if (!el.hasAttribute(newAttr)) {
					el.setAttribute(newAttr, el.getAttribute(oldAttr));
				}
			});
		});
	}

	upgrade(document.documentElement);

	// El contenido del modal y de los boxes se inyecta por AJAX después del load.
	new MutationObserver(function (mutations) {
		mutations.forEach(function (mutation) {
			Array.prototype.forEach.call(mutation.addedNodes, function (node) {
				if (node.nodeType === 1) {
					upgrade(node);
				}
			});
		});
	}).observe(document.documentElement, { childList: true, subtree: true });

	if (!$ || !$.fn) {
		return;
	}

	['modal', 'tab', 'tooltip', 'popover', 'dropdown', 'collapse'].forEach(function (name) {
		var Component = bootstrap[name.charAt(0).toUpperCase() + name.slice(1)];

		if (!Component || $.fn[name]) {
			return;
		}

		$.fn[name] = function (config) {
			return this.each(function () {
				var options = (typeof config === 'object' && config !== null) ? config : {};
				var instance = Component.getOrCreateInstance(this, options);

				if (typeof config === 'string' && typeof instance[config] === 'function') {
					instance[config]();
				}
			});
		};
	});
})(window.jQuery);
