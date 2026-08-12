/**
 * AfiLoader - Global loading overlay controller
 */
var AfiLoader = (function () {
    'use strict';

    var overlay = null;
    var subtext = null;

    function getOverlay() {
        if (!overlay) {
            overlay = document.getElementById('afi-loader-overlay');
        }
        return overlay;
    }

    function getSubtext() {
        if (!subtext) {
            subtext = document.getElementById('afi-loader-subtext');
        }
        return subtext;
    }

    return {
        show: function (message) {
            var el = getOverlay();
            if (el) {
                el.classList.add('visible');
            }
            if (message) {
                var st = getSubtext();
                if (st) st.textContent = message;
            }
        },

        hide: function () {
            var el = getOverlay();
            if (el) {
                el.classList.remove('visible');
            }
            var st = getSubtext();
            if (st) st.textContent = '';
        }
    };
})();
