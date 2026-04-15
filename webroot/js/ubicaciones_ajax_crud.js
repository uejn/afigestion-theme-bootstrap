/**
 * Ubicaciones AJAX CRUD
 * Maneja duplicar y eliminar ubicaciones sin refrescar la pantalla.
 */
(function ($) {
    'use strict';

    var UbicacionesCrud = {

        baseUrl: '/afigestion/ubicaciones/',

        init: function () {
            this.bindDeleteButtons();
            this.bindDuplicateButtons();
            this.bindFormSubmit();
        },

        /**
         * Vincula los botones de eliminar para usar AJAX
         */
        bindDeleteButtons: function () {
            var self = this;
            $(document).on('click', '.ajax-delete-ubicacion', function (e) {
                e.preventDefault();
                var $btn = $(this);
                var ubicacionId = $btn.data('id');
                var ubicacionName = $btn.data('name');

                if (!confirm('¿Está seguro de eliminar la ubicación "' + ubicacionName + '"?')) {
                    return;
                }

                $btn.prop('disabled', true);

                $.ajax({
                    url: self.baseUrl + 'ajax_eliminar_ubicacion/' + ubicacionId,
                    type: 'POST',
                    dataType: 'json',
                    data: { _method: 'POST' },
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function (response) {
                        if (response.success) {
                            var $row = $('#ubicacion-row-' + ubicacionId);
                            $row.addClass('bg-danger');
                            $row.fadeOut(400, function () {
                                $(this).remove();
                                self.updateCount(-1);
                            });
                            self.showNotification('success', response.message);
                        } else {
                            self.showNotification('error', response.message);
                            $btn.prop('disabled', false);
                        }
                    },
                    error: function () {
                        self.showNotification('error', 'Error de conexión. Intente nuevamente.');
                        $btn.prop('disabled', false);
                    }
                });
            });
        },

        /**
         * Vincula los botones de duplicar para usar AJAX con confirm
         */
        bindDuplicateButtons: function () {
            var self = this;
            $(document).on('click', '.ajax-duplicar-ubicacion', function (e) {
                e.preventDefault();
                var $btn = $(this);
                var ubicacionId = $btn.data('id');
                var ubicacionName = $btn.data('name');

                if (!confirm('¿Está seguro de duplicar la ubicación "' + ubicacionName + '"?')) {
                    return;
                }

                $btn.prop('disabled', true);

                $.ajax({
                    url: self.baseUrl + 'ajax_duplicar_ubicacion/' + ubicacionId,
                    type: 'POST',
                    dataType: 'json',
                    data: { _method: 'POST' },
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function (response) {
                        if (response.success) {
                            self.addRowToTable(response.row);
                            self.updateCount(1);
                            self.showNotification('success', response.message);
                        } else {
                            self.showNotification('error', response.message);
                        }
                        $btn.prop('disabled', false);
                    },
                    error: function () {
                        self.showNotification('error', 'Error de conexión. Intente nuevamente.');
                        $btn.prop('disabled', false);
                    }
                });
            });
        },

        /**
         * Intercepta el submit del formulario dentro del modal de editar_ubicacion
         */
        bindFormSubmit: function () {
            var self = this;
            $(document).on('submit', '#ajaxModal form', function (e) {
                // Solo interceptar si es un formulario de ubicación
                var $form = $(this);
                if ($form.find('[name="data[Ubicacion][name]"]').length === 0) {
                    return;
                }
                e.preventDefault();
                var formData = $form.serialize();
                var $submitBtn = $form.find('input[type="submit"], button[type="submit"]');
                var originalText = $submitBtn.val();

                $submitBtn.val('Guardando...').prop('disabled', true);

                $.ajax({
                    url: self.baseUrl + 'ajax_guardar_ubicacion',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            $('#ajaxModal').modal('hide');
                            if (response.isNew) {
                                self.addRowToTable(response.row);
                                self.updateCount(1);
                            } else {
                                self.updateRowInTable(response.id, response.row);
                            }
                            self.showNotification('success', response.message);
                        } else {
                            self.showFormErrors($form, response.errors, response.message);
                        }
                    },
                    error: function () {
                        self.showNotification('error', 'Error de conexión. Intente nuevamente.');
                    },
                    complete: function () {
                        $submitBtn.val(originalText).prop('disabled', false);
                    }
                });
            });
        },

        /**
         * Construye el HTML de los links del path (Rep-Dep)
         */
        buildPathHtml: function (pathItems) {
            var html = '';
            for (var i = 0; i < pathItems.length; i++) {
                html += '<a href="' + this.baseUrl + 'editar_ubicacion/' + pathItems[i].id + '" ' +
                    'class="ajax-modal" title="' + this.escapeHtml(pathItems[i].name) + '">' +
                    this.escapeHtml(pathItems[i].alias) + '</a>/';
            }
            return html;
        },

        /**
         * Construye el HTML de los botones de acción
         */
        buildActionsHtml: function (row) {
            var editBtn = '<a href="' + this.baseUrl + 'editar_ubicacion/' + row.id + '" ' +
                'class="btn btn-info btn-xs ajax-modal">' +
                '<span class="glyphicon glyphicon-pencil"></span></a>';

            var duplicateBtn = '<a href="#" class="btn btn-warning btn-xs ajax-duplicar-ubicacion" ' +
                'data-id="' + row.id + '" data-name="' + this.escapeHtml(row.name) + '" title="Duplicar ubicación">' +
                '<strong>C</strong></a>';

            var deleteBtn = '<a href="#" class="btn btn-danger btn-xs ajax-delete-ubicacion" ' +
                'data-id="' + row.id + '" data-name="' + this.escapeHtml(row.name) + '">' +
                '<span class="glyphicon glyphicon-trash"></span></a>';

            return '<div class="btn-group">' + editBtn + duplicateBtn + deleteBtn + '</div>';
        },

        /**
         * Construye el HTML del link del edificio
         */
        buildEdificioHtml: function (row) {
            if (!row.edificio_id) return '';
            return '<a href="/afigestion/edificios/editar_edificio/' + row.edificio_id + '" ' +
                'class="ajax-modal">' + this.escapeHtml(row.calle_edificio) + '</a>';
        },

        /**
         * Construye el HTML completo de una fila
         */
        buildRowHtml: function (row) {
            var cells = [
                row.id,
                this.escapeHtml(row.ubicacion_tipo),
                this.escapeHtml(row.name),
                this.escapeHtml(row.cod_edificio),
                this.buildEdificioHtml(row),
                this.escapeHtml(row.altura),
                this.escapeHtml(row.localidad),
                this.escapeHtml(row.regional),
                row.mesa,
                this.escapeHtml(row.tipo_conjunto),
                this.escapeHtml(row.fuero),
                this.escapeHtml(row.habilitacion),
                this.buildPathHtml(row.path || []),
                row.cant_personas,
                row.cant_personas_sin_arbol,
                row.updated,
                row.created,
                this.buildActionsHtml(row)
            ];

            var html = '<tr id="ubicacion-row-' + row.id + '">';
            for (var i = 0; i < cells.length; i++) {
                html += '<td>' + (cells[i] || '') + '</td>';
            }
            html += '</tr>';
            return html;
        },

        /**
         * Agrega una nueva fila a la tabla
         */
        addRowToTable: function (row) {
            var $tbody = $('.table-listado-ubicaciones tbody');
            var $newRow = $(this.buildRowHtml(row));
            $newRow.hide();
            $tbody.prepend($newRow);
            $newRow.addClass('bg-success').fadeIn(400);
            setTimeout(function () { $newRow.removeClass('bg-success'); }, 3000);
        },

        /**
         * Actualiza una fila existente en la tabla
         */
        updateRowInTable: function (id, row) {
            var $existingRow = $('#ubicacion-row-' + id);
            if ($existingRow.length) {
                var $newRow = $(this.buildRowHtml(row));
                $existingRow.replaceWith($newRow);
                $newRow.addClass('bg-info');
                setTimeout(function () { $newRow.removeClass('bg-info'); }, 3000);
            }
        },

        /**
         * Actualiza el contador de ubicaciones
         */
        updateCount: function (delta) {
            var $counter = $('.text-success.center strong');
            if ($counter.length) {
                var text = $counter.text();
                var match = text.match(/(\d+)/);
                if (match) {
                    var newCount = parseInt(match[1], 10) + delta;
                    $counter.text(newCount + ' Ubicaciones');
                }
            }
        },

        /**
         * Muestra una notificación temporal
         */
        showNotification: function (type, message) {
            var alertClass = (type === 'success') ? 'alert-success' : 'alert-danger';
            var $alert = $('<div class="alert ' + alertClass + ' alert-dismissible" style="position:fixed;top:10px;right:10px;z-index:9999;min-width:300px;">' +
                '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                message + '</div>');
            $('body').append($alert);
            setTimeout(function () { $alert.fadeOut(400, function () { $(this).remove(); }); }, 4000);
        },

        /**
         * Muestra errores de validación en el formulario
         */
        showFormErrors: function ($form, errors, message) {
            $form.find('.error-message, .has-error').removeClass('has-error');
            $form.find('.error-message').remove();
            if (message) {
                $form.prepend('<div class="alert alert-danger error-message">' + message + '</div>');
            }
            if (errors) {
                for (var field in errors) {
                    if (errors.hasOwnProperty(field)) {
                        var $input = $form.find('[name="data[Ubicacion][' + field + ']"]');
                        $input.closest('.form-group').addClass('has-error');
                        $input.after('<span class="help-block error-message">' + errors[field].join(', ') + '</span>');
                    }
                }
            }
        },

        escapeHtml: function (str) {
            if (!str) return '';
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }
    };

    $(document).ready(function () {
        UbicacionesCrud.init();
    });

})(jQuery);
