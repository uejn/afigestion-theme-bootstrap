/**
 * Ubicaciones AJAX CRUD
 * Maneja crear, editar y eliminar ubicaciones sin refrescar la pantalla.
 * La tabla se actualiza dinámicamente con los cambios realizados.
 */
(function ($) {
    'use strict';

    var UbicacionesCrud = {

        // URL base del plugin Afigestion
        baseUrl: '/afigestion/ubicaciones/',

        init: function () {
            this.bindFormSubmit();
            this.bindDeleteButtons();
        },

        /**
         * Intercepta el submit del formulario dentro del modal de editar_ubicacion
         */
        bindFormSubmit: function () {
            var self = this;
            $(document).on('submit', '#ajaxModal .ajax-ubicacion-form, #ajaxModal form[id*="UbicacionEditar"]', function (e) {
                e.preventDefault();
                var $form = $(this);
                var formData = $form.serialize();
                var $submitBtn = $form.find('input[type="submit"], button[type="submit"]');
                var originalText = $submitBtn.val();

                // Deshabilitar botón mientras se procesa
                $submitBtn.val('Guardando...').prop('disabled', true);

                $.ajax({
                    url: self.baseUrl + 'ajax_guardar_ubicacion',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            // Cerrar modal
                            $('#ajaxModal').modal('hide');

                            if (response.isNew) {
                                // Agregar nueva fila a la tabla
                                self.addRowToTable(response.row);
                                self.updateCount(1);
                            } else {
                                // Actualizar fila existente
                                self.updateRowInTable(response.id, response.row);
                            }

                            // Mostrar notificación de éxito
                            self.showNotification('success', response.message);
                        } else {
                            // Mostrar errores de validación
                            self.showFormErrors($form, response.errors, response.message);
                        }
                    },
                    error: function (xhr) {
                        self.showNotification('error', 'Error de conexión. Intente nuevamente.');
                    },
                    complete: function () {
                        $submitBtn.val(originalText).prop('disabled', false);
                    }
                });
            });
        },

        /**
         * Vincula los botones de eliminar para usar AJAX
         */
        bindDeleteButtons: function () {
            var self = this;

            // Delegación de eventos para botones de eliminar (actuales y futuros)
            $(document).on('click', '.ajax-delete-ubicacion', function (e) {
                e.preventDefault();
                var $btn = $(this);
                var ubicacionId = $btn.data('id');
                var ubicacionName = $btn.data('name');

                if (!confirm('¿Está seguro de eliminar la ubicación ' + ubicacionName + '?')) {
                    return;
                }

                $btn.prop('disabled', true);

                $.ajax({
                    url: self.baseUrl + 'ajax_eliminar_ubicacion/' + ubicacionId,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _method: 'POST'
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function (response) {
                        if (response.success) {
                            // Animar y remover la fila
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
         * Construye el HTML de los links del path (Rep-Dep)
         */
        buildPathHtml: function (pathLinks) {
            var html = '';
            for (var i = 0; i < pathLinks.length; i++) {
                html += '<a href="' + this.baseUrl + 'editar_ubicacion/' + pathLinks[i].id + '" ' +
                    'class="ajax-modal" title="' + this.escapeHtml(pathLinks[i].name) + '">' +
                    this.escapeHtml(pathLinks[i].alias) + '</a>/';
            }
            return html;
        },

        /**
         * Construye el HTML de los botones de acción
         */
        buildActionsHtml: function (row) {
            var editBtn = '<a href="' + this.baseUrl + 'editar_ubicacion/' + row.id + '" ' +
                'class="btn btn-info btn-xs ajax-modal" escape="false">' +
                '<span class="glyphicon glyphicon-pencil"></span></a>';

            var deleteBtn = '<a href="#" class="btn btn-danger btn-xs ajax-delete-ubicacion" ' +
                'data-id="' + row.id + '" data-name="' + this.escapeHtml(row.name) + '">' +
                '<span class="glyphicon glyphicon-trash"></span></a>';

            return '<div class="btn-group">' + editBtn + deleteBtn + '</div>';
        },

        /**
         * Construye el HTML completo de una fila de la tabla
         */
        buildRowHtml: function (row) {
            var calleLink = '';
            if (row.calle_edificio && row.edificio_id) {
                calleLink = '<a href="/afigestion/edificios/editar_edificio/' + row.edificio_id + '" class="ajax-modal">' +
                    this.escapeHtml(row.calle_edificio) + '</a>';
            }

            var cells = [
                row.id,
                this.escapeHtml(row.ubicacion_tipo),
                this.escapeHtml(row.name),
                this.escapeHtml(row.cod_edificio),
                calleLink,
                this.escapeHtml(row.altura),
                this.escapeHtml(row.localidad),
                this.escapeHtml(row.regional),
                row.mesa,
                this.escapeHtml(row.tipo_conjunto),
                this.escapeHtml(row.fuero),
                this.escapeHtml(row.habilitacion),
                this.buildPathHtml(row.path),
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
         * Agrega una fila nueva a la tabla
         */
        addRowToTable: function (row) {
            var $tbody = $('.table-listado-ubicaciones tbody');
            if ($tbody.length === 0) {
                $tbody = $('.table.table-striped tbody');
            }
            var $newRow = $(this.buildRowHtml(row));
            $newRow.hide();
            $tbody.prepend($newRow);
            $newRow.addClass('bg-success').fadeIn(400, function () {
                setTimeout(function () {
                    $newRow.removeClass('bg-success');
                }, 3000);
            });
        },

        /**
         * Actualiza una fila existente en la tabla
         */
        updateRowInTable: function (id, row) {
            var $existingRow = $('#ubicacion-row-' + id);
            if ($existingRow.length === 0) {
                // La fila no existe en la tabla actual, ignorar
                return;
            }

            var $newRow = $(this.buildRowHtml(row));
            $existingRow.replaceWith($newRow);
            $newRow.addClass('bg-info');
            setTimeout(function () {
                $newRow.removeClass('bg-info');
            }, 3000);
        },

        /**
         * Actualiza el contador de ubicaciones
         */
        updateCount: function (delta) {
            var $counter = $('h3.text-success strong');
            if ($counter.length) {
                var text = $counter.text();
                var match = text.match(/(\d+)/);
                if (match) {
                    var currentCount = parseInt(match[1], 10);
                    var newCount = currentCount + delta;
                    $counter.text(newCount + ' Ubicaciones');
                }
            }
        },

        /**
         * Muestra errores de validación en el formulario del modal
         */
        showFormErrors: function ($form, errors, generalMessage) {
            // Limpiar errores previos
            $form.find('.ajax-error').remove();
            $form.find('.has-error').removeClass('has-error');

            if (generalMessage) {
                $form.prepend('<div class="alert alert-danger ajax-error">' + this.escapeHtml(generalMessage) + '</div>');
            }

            if (errors) {
                for (var field in errors) {
                    if (errors.hasOwnProperty(field)) {
                        var $input = $form.find('[name="data[Ubicacion][' + field + ']"]');
                        if ($input.length) {
                            var $group = $input.closest('.form-group, .col-md-6, .col-md-3, .col-md-2, .col-md-4, .col-md-5');
                            $group.addClass('has-error');
                            var errorMessages = errors[field];
                            if (Array.isArray(errorMessages)) {
                                for (var i = 0; i < errorMessages.length; i++) {
                                    $input.after('<span class="help-block ajax-error text-danger">' + this.escapeHtml(errorMessages[i]) + '</span>');
                                }
                            }
                        }
                    }
                }
            }
        },

        /**
         * Muestra una notificación temporal
         */
        showNotification: function (type, message) {
            var alertClass = (type === 'success') ? 'alert-success' : 'alert-danger';
            var $notification = $(
                '<div class="alert ' + alertClass + ' ajax-notification" ' +
                'style="position:fixed; top:70px; right:20px; z-index:10000; min-width:300px; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">' +
                '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                this.escapeHtml(message) +
                '</div>'
            );

            $('body').append($notification);

            // Auto-cerrar después de 4 segundos
            setTimeout(function () {
                $notification.fadeOut(400, function () {
                    $(this).remove();
                });
            }, 4000);
        },

        /**
         * Escapa HTML para prevenir XSS
         */
        escapeHtml: function (text) {
            if (!text && text !== 0) return '';
            var div = document.createElement('div');
            div.appendChild(document.createTextNode(text));
            return div.innerHTML;
        }
    };

    // Inicializar cuando el DOM esté listo
    $(function () {
        UbicacionesCrud.init();
    });

})(jQuery);
