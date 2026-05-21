(function () {
    'use strict';

    var map = null;
    var markers = [];
    var infoWindow = null;
    var allEdificios = [];
    var totalEdificios = 0;
    var countMarcadores = 0;
    var sinUbicar = 0;
    var sinUbicarList = [];
    var bounds = null;

    var DEFAULT_LAT = -34.6037;
    var DEFAULT_LNG = -58.3816;
    var DEFAULT_ZOOM = 5;

    var chkVerMapa = document.getElementById('chk-ver-mapa');
    var mapContainer = document.getElementById('map-edificios-container');

    if (!chkVerMapa || !mapContainer) return;

    if (chkVerMapa.checked) {
        initMap();
        showLoader('Cargando edificios...');
        loadEdificiosFromServer();
    }

    chkVerMapa.addEventListener('change', function () {
        if (this.checked) {
            mapContainer.style.display = 'block';
            initMap();
            showLoader('Cargando edificios...');
            loadEdificiosFromServer();
        } else {
            mapContainer.style.display = 'none';
        }
    });

    function initMap() {
        if (map) return;

        map = new google.maps.Map(document.getElementById('map-edificios'), {
            center: { lat: DEFAULT_LAT, lng: DEFAULT_LNG },
            zoom: DEFAULT_ZOOM
        });

        infoWindow = new google.maps.InfoWindow();
    }

    function clearMarkers() {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
        markers = [];
    }

    function loadEdificiosFromServer() {
        clearMarkers();
        countMarcadores = 0;

        var params = new URLSearchParams(window.location.search);
        params.delete('ver_mapa');
        var queryString = params.toString() ? '?' + params.toString() : '';
        var url = '/afigestion/edificios/ajax_edificios_mapa' + queryString;

        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success && response.edificios) {
                        allEdificios = response.edificios;
                        totalEdificios = allEdificios.length;
                        sinUbicar = response.sin_ubicar || 0;
                        sinUbicarList = response.sin_ubicar_list || [];
                        processEdificios();
                        // Si el server tiene más por geocodificar, recargar en 3s
                        if (response.pending_geocode && response.pending_geocode > 0) {
                            showLoader('Geocodificando en servidor... (' + response.pending_geocode + ' pendientes)');
                            setTimeout(function () {
                                reloadFromServer(queryString);
                            }, 3000);
                        }
                    }
                } catch (e) {
                    console.error('Error parsing edificios mapa response', e);
                    hideLoader();
                }
            } else if (xhr.readyState === 4) {
                hideLoader();
            }
        };
        xhr.send();
    }

    function reloadFromServer(queryString) {
        var url = '/afigestion/edificios/ajax_edificios_mapa' + queryString;
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success && response.edificios) {
                        // Reemplazar data y redibujar
                        clearMarkers();
                        allEdificios = response.edificios;
                        totalEdificios = allEdificios.length;
                        sinUbicar = response.sin_ubicar || 0;
                        sinUbicarList = response.sin_ubicar_list || [];
                        countMarcadores = 0;
                        processEdificios();
                        if (response.pending_geocode && response.pending_geocode > 0) {
                            showLoader('Geocodificando en servidor... (' + response.pending_geocode + ' pendientes)');
                            setTimeout(function () {
                                reloadFromServer(queryString);
                            }, 3000);
                        } else {
                            hideLoader();
                        }
                    }
                } catch (e) {
                    console.error('Error parsing edificios mapa reload', e);
                    hideLoader();
                }
            } else if (xhr.readyState === 4) {
                hideLoader();
            }
        };
        xhr.send();
    }

    function processEdificios() {
        bounds = new google.maps.LatLngBounds();
        var hasMarkers = false;
        countMarcadores = 0;

        for (var i = 0; i < allEdificios.length; i++) {
            var edif = allEdificios[i];

            if (edif.latitud && edif.longitud) {
                if (edif.latitud < -55 || edif.latitud > -21 || edif.longitud < -74 || edif.longitud > -53) {
                    continue;
                }

                var info = buildInfo(edif);
                placeMarker(edif.latitud, edif.longitud, info, edif.id);
                bounds.extend(new google.maps.LatLng(edif.latitud, edif.longitud));
                hasMarkers = true;
                countMarcadores++;
            }
        }

        if (hasMarkers) {
            map.fitBounds(bounds);
            if (markers.length === 1) {
                map.setZoom(15);
            }
        }

        updateContador();
        hideLoader();
    }

    function buildInfo(edif) {
        return {
            codigo: edif.codigo || '',
            nombre: edif.nombre || '',
            calle: edif.calle || '',
            numero: edif.numero || '',
            localidad: edif.localidad || '',
            regional: edif.regional || '',
            es_electoral: edif.es_electoral || false,
            mesas: edif.mesas || [],
            personas: edif.cant_personas || 0,
            noafi: edif.cant_no_afiliados || 0,
            afi: edif.cant_afiliados || 0,
            historicos: edif.cant_historicos || 0
        };
    }

    function placeMarker(lat, lng, info, id) {
        var marker = new google.maps.Marker({
            position: { lat: lat, lng: lng },
            map: map,
            title: buildTitle(info)
        });

        marker._edificioInfo = info;

        marker.addListener('click', function () {
            var content = buildInfoWindowContent(this._edificioInfo);
            infoWindow.setContent(content);
            infoWindow.open(map, this);
        });

        markers.push(marker);
    }

    function buildTitle(info) {
        var title = '';
        if (info.nombre) title += info.nombre + ' - ';
        if (info.calle) title += info.calle;
        if (info.numero) title += ' ' + info.numero;
        return title;
    }

    function buildInfoWindowContent(info) {
        var direccion = '';
        if (info.calle) direccion += info.calle;
        if (info.numero) direccion += ' ' + info.numero;
        if (info.localidad) direccion += ', ' + info.localidad;

        var html = '<div style="font-size: 13px; min-width: 220px;">';
        if (info.codigo) {
            html += '<span style="color:#888;font-size:11px;">Cód: ' + escapeHtml(info.codigo) + '</span><br/>';
        }
        if (info.nombre) {
            html += '<strong style="font-size:14px;">' + escapeHtml(info.nombre) + '</strong><br/>';
        }
        html += '<span style="color:#555;">' + escapeHtml(direccion) + '</span>';
        html += '<hr style="margin: 5px 0;"/>';
        html += '<table style="width:100%; font-size: 12px;">';
        html += '<tr><td><strong>Regional:</strong></td><td style="text-align:right;">' + escapeHtml(info.regional) + '</td></tr>';
        html += '<tr><td><strong>Es Electoral:</strong></td><td style="text-align:right;">' + (info.es_electoral ? '<span style="color:green;">Sí</span>' : '<span style="color:#999;">No</span>') + '</td></tr>';
        if (info.mesas && info.mesas.length > 0) {
            html += '<tr><td><strong>Mesas:</strong></td><td style="text-align:right;">' + escapeHtml(info.mesas.join(', ')) + '</td></tr>';
        }
        html += '<tr><td><strong>Cant. Personas:</strong></td><td style="text-align:right;">' + info.personas + '</td></tr>';
        html += '<tr><td><strong>Cant. No Afiliados:</strong></td><td style="text-align:right;">' + info.noafi + '</td></tr>';
        html += '<tr><td><strong>Cant. Afiliados:</strong></td><td style="text-align:right;">' + info.afi + '</td></tr>';
        html += '<tr><td><strong>Cant. Históricos:</strong></td><td style="text-align:right;">' + info.historicos + '</td></tr>';
        html += '</table>';
        html += '</div>';
        return html;
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }

    function showLoader(msg) {
        var el = document.getElementById('mapa-edificios-loader');
        if (!el) {
            el = document.createElement('div');
            el.id = 'mapa-edificios-loader';
            el.style.cssText = 'position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);z-index:10;background:rgba(255,255,255,0.92);padding:18px 30px;border-radius:8px;box-shadow:0 2px 12px rgba(0,0,0,0.3);font-size:14px;text-align:center;';
            var mapDiv = document.getElementById('map-edificios');
            mapDiv.style.position = 'relative';
            mapDiv.appendChild(el);
        }
        el.innerHTML = '<div style="margin-bottom:6px;"><strong>' + (msg || 'Cargando...') + '</strong></div><div id="mapa-loader-progress" style="color:#666;font-size:12px;"></div>';
        el.style.display = 'block';
    }

    function hideLoader() {
        var el = document.getElementById('mapa-edificios-loader');
        if (el) el.style.display = 'none';
    }

    function updateContador() {
        var el = document.getElementById('mapa-edificios-contador');
        if (!el) {
            el = document.createElement('div');
            el.id = 'mapa-edificios-contador';
            el.style.cssText = 'position:absolute;top:10px;right:60px;z-index:5;background:#fff;padding:6px 12px;border-radius:4px;box-shadow:0 2px 6px rgba(0,0,0,0.3);font-size:13px;font-weight:bold;';
            var mapDiv = document.getElementById('map-edificios');
            mapDiv.style.position = 'relative';
            mapDiv.appendChild(el);
        }
        var texto = countMarcadores + ' / ' + totalEdificios + ' edificios en mapa';
        if (sinUbicar > 0) {
            texto += ' <a href="#" id="link-sin-ubicar" style="color:#c00;font-weight:normal;text-decoration:underline;">(' + sinUbicar + ' sin dirección)</a>';
        }
        el.innerHTML = texto;

        var link = document.getElementById('link-sin-ubicar');
        if (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                showSinUbicarPopup();
            });
        }
    }

    function showSinUbicarPopup() {
        // Remover popup anterior si existe
        var existing = document.getElementById('popup-sin-ubicar');
        if (existing) existing.remove();

        // Ordenar por regional ascendente
        var sorted = sinUbicarList.slice().sort(function (a, b) {
            var ra = (a.regional || '').toLowerCase();
            var rb = (b.regional || '').toLowerCase();
            if (ra < rb) return -1;
            if (ra > rb) return 1;
            return 0;
        });

        var overlay = document.createElement('div');
        overlay.id = 'popup-sin-ubicar';
        overlay.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:10000;display:flex;align-items:center;justify-content:center;';

        var modal = document.createElement('div');
        modal.style.cssText = 'background:#fff;border-radius:8px;padding:20px;max-width:1100px;width:95%;max-height:85vh;overflow-y:auto;box-shadow:0 4px 20px rgba(0,0,0,0.3);';

        var html = '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">';
        html += '<h3 style="margin:0;">Edificios sin dirección (' + sorted.length + ')</h3>';
        html += '<div><button id="btn-localizar-todos" style="border:none;background:#5cb85c;color:#fff;padding:5px 12px;border-radius:4px;cursor:pointer;font-size:13px;margin-right:8px;">📍 Localizar todos</button>';
        html += '<button id="btn-cerrar-sin-ubicar" style="border:none;background:#c00;color:#fff;padding:5px 12px;border-radius:4px;cursor:pointer;font-size:14px;">✕ Cerrar</button></div>';
        html += '</div>';

        html += '<table style="width:100%;border-collapse:collapse;font-size:11px;">';
        html += '<thead><tr style="background:#f5f5f5;">';
        html += '<th style="padding:5px;border:1px solid #ddd;">Cód</th>';
        html += '<th style="padding:5px;border:1px solid #ddd;">Nombre</th>';
        html += '<th style="padding:5px;border:1px solid #ddd;">Calle</th>';
        html += '<th style="padding:5px;border:1px solid #ddd;">Nro</th>';
        html += '<th style="padding:5px;border:1px solid #ddd;">Localidad</th>';
        html += '<th style="padding:5px;border:1px solid #ddd;">Regional</th>';
        html += '<th style="padding:5px;border:1px solid #ddd;">Latitud</th>';
        html += '<th style="padding:5px;border:1px solid #ddd;">Longitud</th>';
        html += '<th style="padding:5px;border:1px solid #ddd;text-align:center;">Acción</th>';
        html += '</tr></thead><tbody>';

        for (var i = 0; i < sorted.length; i++) {
            var e = sorted[i];
            html += '<tr id="row-sin-ubicar-' + e.id + '">';
            html += '<td style="padding:4px;border:1px solid #ddd;">' + escapeHtml(e.codigo || '') + '</td>';
            html += '<td style="padding:4px;border:1px solid #ddd;">' + escapeHtml(e.nombre || '') + '</td>';
            html += '<td style="padding:4px;border:1px solid #ddd;">' + escapeHtml(e.calle || '') + '</td>';
            html += '<td style="padding:4px;border:1px solid #ddd;">' + escapeHtml(e.numero || '') + '</td>';
            html += '<td style="padding:4px;border:1px solid #ddd;">' + escapeHtml(e.localidad || '') + '</td>';
            html += '<td style="padding:4px;border:1px solid #ddd;">' + escapeHtml(e.regional || '') + '</td>';
            html += '<td style="padding:4px;border:1px solid #ddd;"><input type="text" id="lat-' + e.id + '" style="width:90px;font-size:11px;padding:2px 4px;" /></td>';
            html += '<td style="padding:4px;border:1px solid #ddd;"><input type="text" id="lng-' + e.id + '" style="width:90px;font-size:11px;padding:2px 4px;" /></td>';
            html += '<td style="padding:4px;border:1px solid #ddd;text-align:center;white-space:nowrap;">';
            html += '<button class="btn-geolocalizar" data-id="' + e.id + '" data-calle="' + escapeHtml(e.calle || '') + '" data-numero="' + escapeHtml(e.numero || '') + '" data-localidad="' + escapeHtml(e.localidad || '') + '" style="border:none;background:#5cb85c;color:#fff;padding:2px 6px;border-radius:3px;cursor:pointer;font-size:10px;margin-right:3px;" title="Localizar">📍</button>';
            html += '<button class="btn-guardar-coords" data-id="' + e.id + '" style="border:none;background:#337ab7;color:#fff;padding:2px 6px;border-radius:3px;cursor:pointer;font-size:10px;" title="Guardar">💾</button>';
            html += '</td>';
            html += '</tr>';
        }

        html += '</tbody></table>';
        modal.innerHTML = html;
        overlay.appendChild(modal);
        document.body.appendChild(overlay);

        document.getElementById('btn-cerrar-sin-ubicar').addEventListener('click', function () {
            overlay.remove();
        });
        overlay.addEventListener('click', function (ev) {
            if (ev.target === overlay) overlay.remove();
        });

        // Botón "Localizar todos" - geocodifica secuencialmente con delay para no saturar
        document.getElementById('btn-localizar-todos').addEventListener('click', function () {
            var btnsAll = modal.querySelectorAll('.btn-geolocalizar:not([disabled])');
            var delay = 0;
            for (var k = 0; k < btnsAll.length; k++) {
                (function (btn, d) {
                    setTimeout(function () { btn.click(); }, d);
                })(btnsAll[k], delay);
                delay += 350; // 350ms entre cada request para no saturar
            }
        });

        // Bind botones geolocalizar (📍) - rellena los inputs lat/lng
        var btns = modal.querySelectorAll('.btn-geolocalizar');
        for (var j = 0; j < btns.length; j++) {
            btns[j].addEventListener('click', function () {
                var btn = this;
                var id = btn.getAttribute('data-id');
                var calle = btn.getAttribute('data-calle');
                var numero = btn.getAttribute('data-numero');
                var localidad = btn.getAttribute('data-localidad');
                var direccion = (calle + ' ' + numero).trim() + ', ' + localidad + ', Argentina';

                var latInput = document.getElementById('lat-' + id);
                var lngInput = document.getElementById('lng-' + id);

                btn.disabled = true;
                btn.textContent = '⏳';

                // Usar Google Maps Geocoder
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({ address: direccion }, function (results, status) {
                    if (status === 'OK' && results[0]) {
                        var lat = results[0].geometry.location.lat();
                        var lng = results[0].geometry.location.lng();

                        if (lat < -55 || lat > -21 || lng < -74 || lng > -53) {
                            btn.textContent = '❌';
                            btn.title = 'Fuera de Argentina';
                            btn.style.background = '#d9534f';
                            return;
                        }

                        latInput.value = lat.toFixed(8);
                        lngInput.value = lng.toFixed(8);
                        btn.textContent = '✓';
                        btn.style.background = '#337ab7';

                        // Auto-guardar después de localizar
                        var btnGuardar = modal.querySelector('.btn-guardar-coords[data-id="' + id + '"]');
                        if (btnGuardar) btnGuardar.click();
                    } else {
                        btn.textContent = '❌';
                        btn.title = 'No encontrado: ' + status;
                        btn.style.background = '#f0ad4e';
                    }
                });
            });
        }

        // Bind botones guardar (💾)
        var btnsGuardar = modal.querySelectorAll('.btn-guardar-coords');
        for (var g = 0; g < btnsGuardar.length; g++) {
            btnsGuardar[g].addEventListener('click', function () {
                var btn = this;
                var id = btn.getAttribute('data-id');
                var latInput = document.getElementById('lat-' + id);
                var lngInput = document.getElementById('lng-' + id);
                var lat = parseFloat(latInput.value);
                var lng = parseFloat(lngInput.value);

                if (isNaN(lat) || isNaN(lng)) {
                    btn.textContent = '⚠️';
                    btn.title = 'Ingrese latitud y longitud válidas';
                    return;
                }

                if (lat < -55 || lat > -21 || lng < -74 || lng > -53) {
                    btn.textContent = '❌';
                    btn.title = 'Coordenadas fuera de Argentina';
                    btn.style.background = '#d9534f';
                    return;
                }

                btn.disabled = true;
                btn.textContent = '⏳';

                var xhr = new XMLHttpRequest();
                xhr.open('POST', '/afigestion/edificios/ajax_save_coords', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var resp = JSON.parse(xhr.responseText);
                        if (resp.success) {
                            btn.textContent = '✓';
                            btn.style.background = '#5cb85c';
                            var row = document.getElementById('row-sin-ubicar-' + id);
                            if (row) row.style.background = '#dff0d8';
                            // Agregar pin al mapa
                            var info = { nombre: '', calle: latInput.closest('tr').children[2].textContent, numero: latInput.closest('tr').children[3].textContent, localidad: latInput.closest('tr').children[4].textContent, regional: latInput.closest('tr').children[5].textContent, es_electoral: false, mesas: [], codigo: latInput.closest('tr').children[0].textContent, personas: 0, noafi: 0, afi: 0, historicos: 0 };
                            placeMarker(lat, lng, info, id);
                            countMarcadores++;
                            sinUbicar--;
                            updateContador();
                        } else {
                            btn.textContent = '❌';
                            btn.style.background = '#d9534f';
                            btn.disabled = false;
                        }
                    }
                };
                xhr.send('data[id]=' + id + '&data[latitud]=' + lat + '&data[longitud]=' + lng);
            });
        }
    }

})();
