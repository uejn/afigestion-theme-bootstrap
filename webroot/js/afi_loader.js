/**
 * AfiLoader - Overlay de carga global para Afigestion
 *
 * Uso:
 *   AfiLoader.show();                              // Muestra con texto por defecto
 *   AfiLoader.show('Cargando...');                 // Solo título
 *   AfiLoader.show('Guardando...', 'Espere...');   // Título + subtítulo
 *   AfiLoader.hide();                              // Oculta el overlay (respeta tiempo mínimo)
 */
var AfiLoader = (function() {
	var _overlay    = null;
	var _text       = null;
	var _subtext    = null;
	var _shownAt    = null;   // timestamp del último show()
	var _hideTimer  = null;   // timer pendiente de hide()
	var MIN_MS      = 500;    // tiempo mínimo visible en milisegundos

	function _init() {
		if (!_overlay) {
			_overlay = document.getElementById('afi-loader-overlay');
			_text    = document.getElementById('afi-loader-text');
			_subtext = document.getElementById('afi-loader-subtext');
		}
	}

	function _doHide() {
		if (_overlay) _overlay.style.display = 'none';
		_shownAt = null;
	}

	return {
		show: function(text, subtext) {
			_init();
			if (!_overlay) return;
			// Cancelar cualquier hide() pendiente
			if (_hideTimer) { clearTimeout(_hideTimer); _hideTimer = null; }
			if (_text)    _text.textContent    = text    || 'Cargando...';
			if (_subtext) _subtext.textContent = subtext || '';
			_overlay.style.display = 'flex';
			_shownAt = Date.now();
		},
		hide: function() {
			_init();
			if (!_overlay) return;
			if (_hideTimer) { clearTimeout(_hideTimer); _hideTimer = null; }
			var elapsed = _shownAt ? (Date.now() - _shownAt) : MIN_MS;
			var remaining = MIN_MS - elapsed;
			if (remaining > 0) {
				// Aún no pasó el tiempo mínimo, esperar la diferencia
				_hideTimer = setTimeout(_doHide, remaining);
			} else {
				_doHide();
			}
		}
	};
})();

// Cuando el navegador restaura la página desde bfcache (botón atrás/adelante),
// el evento pageshow se dispara con persisted=true pero $(document).ready NO se ejecuta,
// por lo que el overlay quedaría visible indefinidamente. Lo ocultamos aquí.
window.addEventListener('pageshow', function(e) {
	if (e.persisted) {
		AfiLoader.hide();
	}
});
