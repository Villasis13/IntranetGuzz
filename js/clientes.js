function agregarDireccion(dir, ref) {
    var container = $('#direcciones_container');
    var idx = container.find('.dir-row').length + 1;
    var $row = $('<div class="dir-row border rounded p-2 mb-2 bg-white">' +
        '<div class="d-flex justify-content-between align-items-center mb-1">' +
        '<small class="fw-bold text-secondary dir-num">Dirección #' + idx + '</small>' +
        '<button type="button" class="btn btn-outline-danger btn-sm py-0 px-2" onclick="eliminarDireccion(this)" title="Eliminar">' +
        '<i class="fa fa-times"></i></button>' +
        '</div>' +
        '<input type="text" class="form-control form-control-sm mb-1 dir-input" placeholder="Dirección *">' +
        '<textarea class="form-control form-control-sm ref-input" rows="2" placeholder="Referencia (opcional)" style="resize:vertical"></textarea>' +
        '</div>');
    if (dir) $row.find('.dir-input').val(dir);
    if (ref) $row.find('.ref-input').val(ref);
    container.append($row);
}

function eliminarDireccion(btn) {
    var rows = $('#direcciones_container .dir-row');
    if (rows.length <= 1) {
        respuesta('Debe haber al menos una dirección', 'error');
        return;
    }
    $(btn).closest('.dir-row').remove();
    $('#direcciones_container .dir-row').each(function(i) {
        $(this).find('.dir-num').text('Dirección #' + (i + 1));
    });
}

function toggle_celular2() {
    var $div = $('#div_celular2');
    var visible = $div.is(':visible');
    if (visible) {
        $div.slideUp(150);
        $('#cliente_celular2').val('');
        $('#btn_add_celular2').html('<i class="fa fa-plus"></i>').attr('title', 'Agregar segundo celular');
    } else {
        $div.slideDown(150);
        $('#btn_add_celular2').html('<i class="fa fa-minus"></i>').attr('title', 'Quitar segundo celular');
        $('#cliente_celular2').focus();
    }
}

// ===== Documentos y fotos del cliente =====
var DOC_EXTENSIONES = ['jpg', 'jpeg', 'png', 'webp', 'pdf', 'doc', 'docx'];
var DOC_MAX_BYTES = 10 * 1024 * 1024; // 10 MB, igual que en el servidor

function agregarDocumento() {
    var $row = $('<div class="doc-row border rounded p-2 mb-2 bg-white">' +
        '<div class="row g-2 align-items-center">' +
        '<div class="col-md-3">' +
        '<select class="form-control form-control-sm doc-tipo">' +
        '<option value="DNI">DNI</option>' +
        '<option value="Recibo de luz">Recibo de luz</option>' +
        '<option value="Foto personal">Foto personal</option>' +
        '<option value="Otro">Otro</option>' +
        '</select>' +
        '</div>' +
        '<div class="col-md-4">' +
        '<input type="file" class="form-control form-control-sm doc-archivo" accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx">' +
        '</div>' +
        '<div class="col-md-4">' +
        '<input type="text" class="form-control form-control-sm doc-descripcion" maxlength="255" placeholder="Descripción (opcional)">' +
        '</div>' +
        '<div class="col-md-1 text-end">' +
        '<button type="button" class="btn btn-outline-danger btn-sm py-0 px-2" onclick="$(this).closest(\'.doc-row\').remove()" title="Quitar">' +
        '<i class="fa fa-times"></i></button>' +
        '</div>' +
        '</div>' +
        '</div>');
    $('#documentos_container').append($row);
}

function formatear_tamanho(bytes) {
    bytes = parseInt(bytes, 10) || 0;
    if (bytes >= 1024 * 1024) return (bytes / 1024 / 1024).toFixed(1) + ' MB';
    return Math.max(1, Math.round(bytes / 1024)) + ' KB';
}

// Lista los documentos ya guardados del cliente (texto insertado con .text() para no interpretar HTML)
function mostrar_documentos_guardados(documentos) {
    var $cont = $('#documentos_guardados').empty();
    if (!documentos || !documentos.length) {
        $cont.append('<small class="text-muted d-block mb-2">Este cliente aún no tiene documentos adjuntos.</small>');
        return;
    }
    documentos.forEach(function (d) {
        var es_imagen = (d.cliente_documento_mime || '').indexOf('image/') === 0;
        var icono = es_imagen ? 'fa-image' : (d.cliente_documento_mime === 'application/pdf' ? 'fa-file-pdf' : 'fa-file-alt');
        var $row = $('<div class="d-flex align-items-center border rounded p-2 mb-2 bg-white">' +
            '<i class="fa ' + icono + ' text-primary me-2"></i>' +
            '<div class="flex-grow-1 small">' +
            '<span class="badge bg-info text-white me-1 doc-g-tipo"></span>' +
            '<span class="doc-g-nombre"></span>' +
            '<div class="text-muted doc-g-detalle"></div>' +
            '</div>' +
            '<a class="btn btn-outline-primary btn-sm py-0 px-2 me-1" target="_blank" title="Ver"><i class="fa fa-eye"></i></a>' +
            '<button type="button" class="btn btn-outline-danger btn-sm py-0 px-2" title="Eliminar"><i class="fa fa-trash"></i></button>' +
            '</div>');
        $row.find('.doc-g-tipo').text(d.cliente_documento_tipo);
        $row.find('.doc-g-nombre').text(d.cliente_documento_nombre);
        var detalle = (d.cliente_documento_descripcion ? d.cliente_documento_descripcion + ' · ' : '') +
            formatear_tamanho(d.cliente_documento_tamanho) + ' · ' + (d.cliente_documento_fecha || '').substring(0, 16);
        $row.find('.doc-g-detalle').text(detalle);
        $row.find('a').attr('href', urlweb + 'Clientes/ver_documento/' + d.id_cliente_documento);
        $row.find('button').on('click', function () {
            preguntar('¿Eliminar este documento del cliente?', 'eliminar_documento_cliente', 'Sí, eliminar', 'Cancelar', d.id_cliente_documento);
        });
        $cont.append($row);
    });
}

function eliminar_documento_cliente(id_cliente_documento) {
    $.ajax({
        type: "POST",
        url: urlweb + "api/Clientes/eliminar_documento_cliente",
        data: { id_cliente_documento: id_cliente_documento },
        dataType: 'json',
        success: function (r) {
            if (r.result.code == 1) {
                respuesta('Documento eliminado', 'success');
                editar_clientes($('#id_cliente').val());
            } else {
                respuesta('No se pudo eliminar el documento', 'error');
            }
        }
    });
}

function guardar_editar_clientes(){
    var valor = true;
    var boton = "btn-agregar-cliente";

    var id_cliente = $('#id_cliente').val();
    var cliente_dni  = $('#cliente_dni ').val();
    var cliente_nombre = $('#cliente_nombre').val();
    var cliente_apellido_paterno = $('#cliente_apellido_paterno').val();
    var cliente_apellido_materno = $('#cliente_apellido_materno').val();
    var cliente_fecha_nacimiento = $('#cliente_fecha_nacimiento').val();
    var cliente_celular = $('#cliente_celular').val();
    var cliente_celular2 = $('#cliente_celular2').val();
    var cliente_correo = $('#cliente_correo').val();
    var cliente_nro_tarjeta = $('#cliente_nro_tarjeta').val();
    var cliente_clave = $('#cliente_clave').val();
    var cliente_lugar_trabajo = $('#cliente_lugar_trabajo').val();
    var cliente_otro = $('#cliente_otro').val();

    var cliente_direcciones = [];
    $('#direcciones_container .dir-row').each(function() {
        cliente_direcciones.push({
            direccion: $(this).find('.dir-input').val().trim(),
            referencia: $(this).find('.ref-input').val().trim()
        });
    });

    valor = validar_campo_vacio('cliente_dni', cliente_dni, valor);
    valor = validar_campo_vacio('cliente_nombre', cliente_nombre, valor);
    valor = validar_campo_vacio('cliente_apellido_paterno', cliente_apellido_paterno, valor);
    valor = validar_campo_vacio('cliente_apellido_materno', cliente_apellido_materno, valor);
    valor = validar_campo_vacio('cliente_celular', cliente_celular, valor);

    if (valor && (!cliente_direcciones.length || !cliente_direcciones[0].direccion)) {
        respuesta('La dirección es obligatoria', 'error');
        $('#direcciones_container .dir-row:first .dir-input').css('border', 'solid red');
        valor = false;
    }

    if (valor && cliente_celular && !/^[0-9]+$/.test(cliente_celular)) {
        respuesta('El celular solo debe contener números', 'error');
        $('#cliente_celular').css('border', 'solid red');
        valor = false;
    }
    if (valor && cliente_celular2 && !/^[0-9]+$/.test(cliente_celular2)) {
        respuesta('El Celular 2 solo debe contener números', 'error');
        $('#cliente_celular2').css('border', 'solid red');
        valor = false;
    }

    // Documentos nuevos: cada fila debe tener archivo, formato permitido y tamaño válido
    var documentos = [];
    $('#documentos_container .doc-row').each(function () {
        var $fila = $(this);
        var archivo = $fila.find('.doc-archivo')[0].files[0];
        $fila.find('.doc-archivo').css('border', '');
        if (!archivo) {
            if (valor) respuesta('Seleccione el archivo a adjuntar o quite la fila vacía', 'error');
            $fila.find('.doc-archivo').css('border', 'solid red');
            valor = false;
            return;
        }
        var ext = archivo.name.split('.').pop().toLowerCase();
        if (DOC_EXTENSIONES.indexOf(ext) === -1 || archivo.size > DOC_MAX_BYTES) {
            if (valor) respuesta('"' + archivo.name + '": solo JPG, PNG, WEBP, PDF, DOC o DOCX de hasta 10 MB', 'error');
            $fila.find('.doc-archivo').css('border', 'solid red');
            valor = false;
            return;
        }
        documentos.push({
            archivo: archivo,
            tipo: $fila.find('.doc-tipo').val(),
            descripcion: $fila.find('.doc-descripcion').val().trim()
        });
    });

    if(valor){
        // FormData para poder enviar los archivos junto con los datos del cliente
        var datos = new FormData();
        datos.append('id_cliente', id_cliente);
        datos.append('cliente_dni', cliente_dni);
        datos.append('cliente_nombre', cliente_nombre);
        datos.append('cliente_apellido_paterno', cliente_apellido_paterno);
        datos.append('cliente_apellido_materno', cliente_apellido_materno);
        datos.append('cliente_fecha_nacimiento', cliente_fecha_nacimiento);
        datos.append('cliente_direcciones', JSON.stringify(cliente_direcciones));
        datos.append('cliente_celular', cliente_celular);
        datos.append('cliente_celular2', cliente_celular2);
        datos.append('cliente_correo', cliente_correo);
        datos.append('cliente_nro_tarjeta', cliente_nro_tarjeta);
        datos.append('cliente_clave', cliente_clave);
        datos.append('cliente_lugar_trabajo', cliente_lugar_trabajo);
        datos.append('cliente_otro', cliente_otro);
        documentos.forEach(function (d) {
            datos.append('documentos[]', d.archivo);
            datos.append('documentos_tipo[]', d.tipo);
            datos.append('documentos_descripcion[]', d.descripcion);
        });

        $.ajax({
            type: "POST",
            url: urlweb + "api/Clientes/guardar_editar_clientes",
            data: datos,
            contentType: false,
            processData: false,
            cache: false,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, documentos.length ? 'Subiendo archivos...' : 'Guardando...', true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                switch (r.result.code) {
                    case 1:
                        var rechazados = r.result.documentos_rechazados || [];
                        if (rechazados.length) {
                            cambiar_estado_boton(boton, 'Guardando...', true);
                            Swal.fire({
                                icon: 'warning',
                                title: 'Cliente guardado',
                                text: 'No se pudieron guardar estos archivos: ' + rechazados.join(', ') +
                                    '. Verifique que sean JPG, PNG, WEBP, PDF, DOC o DOCX de hasta 10 MB.'
                            }).then(function () { location.reload(); });
                            break;
                        }
                        if(id_cliente != ""){
                            respuesta('¡Cliente Editado Exitosamente', 'success');
                        } else {
                            respuesta('¡Cliente guardado! Recargando...', 'success');
                        }
                        setTimeout(function () { location.reload(); }, 1000);
                        break;
                    case 2:
                        respuesta('Error al guardar cliente', 'error');
                        break;
                    case 3:
                        respuesta('El DNI del cliente ' + cliente_dni + ' ya se encuentra registrado', 'error');
                        $('#cliente_dni').css('border','solid red');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            },
            error: function () {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                respuesta('No se pudo guardar: error de conexión o archivos demasiado grandes', 'error');
            }
        });
    }
}
function guardar_editar_clientes_moroso(){
    var valor = true;
    var boton = "btn_pasar_moroso_cliente";

    var id_cliente_moroso = $('#id_cliente_moroso').val();
    var cliente_historial_moroso_comentario = $('#cliente_historial_moroso_comentario').val();
    
    valor = validar_campo_vacio('id_cliente_moroso', id_cliente_moroso, valor);
    valor = validar_campo_vacio('cliente_historial_moroso_comentario', cliente_historial_moroso_comentario, valor);

    if(valor){
        var cadena =
            "id_cliente_moroso=" + id_cliente_moroso +
            "&cliente_historial_moroso_comentario=" + cliente_historial_moroso_comentario;

        $.ajax({
            type: "POST",
            url: urlweb + "api/Clientes/actualizar_cliente_a_moroso",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'Guardando...', true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Cliente guardado! Recargando...', 'success');
                        setTimeout(function () { location.reload(); }, 1000);
                        break;
                    case 2:
                        respuesta('Error al guardar cliente', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}
function guardar_garante(){
    var valor = true;
    var id_cliente_recomendado = $('#id_cliente_recomendado').val();
    var id_cliente = $('#id_cliente').val();
    
    if(id_cliente_recomendado === ""){
        respuesta('Debe buscar un cliente','error');
        valor = false;
    }

    if(valor){
        var cadena =
            "id_cliente=" + id_cliente+
        "&id_cliente_recomendado=" + id_cliente_recomendado;

        $.ajax({
            type: "POST",
            url: urlweb + "api/Clientes/guardar_cliente_garante",
            data: cadena,
            dataType: 'json',
            /*beforeSend: function () {
                cambiar_estado_boton(boton, 'Guardando...', true);
            },*/
            success:function (r) {
                // cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Garante Guardado', 'success');
                        setTimeout(function () { 
                            location.reload(); 
                            }, 1000);
                        break;
                    case 2:
                        respuesta('Error al guardar', 'error');
                        break;
                    case 3:
                        respuesta('Ese garante ya está registrado para este cliente', 'error');
                        break;
                    case 4:
                        respuesta('Uno mismo no puede ser garante', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}
function buscar_cliente_garante(){
    var valor = true;
    var btn_dni_garante_nuevo = $('#btn_dni_garante_nuevo').val();
    var id_cliente_recomendado = $('#id_cliente_recomendado').val('');
    valor = validar_campo_vacio('btn_dni_garante_nuevo', btn_dni_garante_nuevo, valor);
    if(valor){
        var cadena =
            "btn_dni_garante_nuevo=" + btn_dni_garante_nuevo;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Clientes/buscar_cliente_garante",
            data: cadena,
            dataType: 'json',
            /*beforeSend: function () {
                cambiar_estado_boton(boton, 'Guardando...', true);
            },*/
            success:function (r) {
                // cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                let data = r.result.code;
                // console.log(data)
                if(data){
                    respuesta('Cliente encontrado', 'info');
                    $('#id_cliente_recomendado').val(data.id_cliente);
                    $('#input_recomendado').val(data.cliente_nombre + ' ' + data.cliente_apellido_paterno + ' ' + data.cliente_apellido_materno);
                }else{
                    $('#input_recomendado').val('')
                    $('#id_cliente_recomendado').val('');
                    respuesta('Cliente no encontrado', 'error');
                }
            }
        });
    }
}
function editar_clientes(id_cliente){
    let guardarid = id_cliente;
    $.ajax({
        type: "POST",
        url: urlweb + "api/Clientes/edicion_clientes",
        data: {
            guardarid :guardarid
        },
        dataType: 'json'
    }).done(function(datos_editar){
        console.log(datos_editar);
        let almacenar = datos_editar.result.code;
        $("#id_cliente").val(guardarid);
        $('#cliente_dni ').val(almacenar.cliente_dni);
        $('#cliente_nombre').val(almacenar.cliente_nombre);
        $('#cliente_apellido_paterno').val(almacenar.cliente_apellido_paterno);
        $('#cliente_apellido_materno').val(almacenar.cliente_apellido_materno);
        $('#cliente_fecha_nacimiento').val(almacenar.cliente_fecha_nacimiento);
        var dirs = almacenar.direcciones || [];
        $('#direcciones_container').empty();
        if (dirs.length > 0) {
            dirs.forEach(function(d) {
                agregarDireccion(d.cldir_direccion, d.cldir_referencia || '');
            });
        } else {
            agregarDireccion(almacenar.cliente_direccion || '', almacenar.cliente_referencia || '');
        }
        $('#cliente_celular').val(almacenar.cliente_celular);
        var cel2 = almacenar.cliente_celular2 || '';
        $('#cliente_celular2').val(cel2);
        if (cel2) {
            $('#div_celular2').show();
            $('#btn_add_celular2').html('<i class="fa fa-minus"></i>').attr('title', 'Quitar segundo celular');
        } else {
            $('#div_celular2').hide();
            $('#btn_add_celular2').html('<i class="fa fa-plus"></i>').attr('title', 'Agregar segundo celular');
        }
        $('#cliente_correo').val(almacenar.cliente_correo);
        $('#cliente_nro_tarjeta').val(almacenar.cliente_nro_tarjeta);
        $('#cliente_clave').val(almacenar.cliente_clave);
        $('#cliente_lugar_trabajo').val(almacenar.cliente_lugar_trabajo);
        $('#cliente_otro').val(almacenar.cliente_otro);
        $('#documentos_container').empty();
        mostrar_documentos_guardados(almacenar.documentos || []);
    });

    function edicion(cliente_nombre, cliente_apellidos, cliente_dni, cliente_celular,cliente_email,cliente_genero){
    }

}
function actualizar_cliente_a_moroso(id_cliente,tipo){
    let guardarid = id_cliente;
    $.ajax({
        type: "POST",
        url: urlweb + "api/Clientes/actualizar_cliente_a_moroso",
        data: {
            guardarid :guardarid,
            tipo : tipo
        },
        dataType: 'json',
        success:function (r) {
            // cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
            switch (r.result.code) {
                case 1:
                    respuesta('¡Cliente enviado a Moroso', 'success');
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                    break;
                case 2:
                    respuesta('Error al guardar', 'error');
                    break;
                default:
                    respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                    break;
            }
        }
    })
}
function limpiar_clientes(){
    $('#id_cliente').val('');
    $('#cliente_dni ').val('');
    $('#cliente_nombre').val('');
    $('#cliente_apellido_paterno').val('');
    $('#cliente_apellido_materno').val('');
    $('#cliente_fecha_nacimiento').val('');
    $('#direcciones_container').empty();
    agregarDireccion();
    $('#cliente_celular').val('');
    $('#cliente_celular2').val('');
    $('#div_celular2').hide();
    $('#btn_add_celular2').html('<i class="fa fa-plus"></i>').attr('title', 'Agregar segundo celular');
    $('#cliente_correo').val('');
    $('#cliente_nro_tarjeta').val('');
    $('#cliente_clave').val('');
    $('#cliente_lugar_trabajo').val('');
    $('#cliente_otro').val('');
    $('#documentos_container').empty();
    $('#documentos_guardados').empty();
}
function poner_id_modal_moroso(id){
    $('#id_cliente_moroso').val(id);
    cargar_resumen_comportamiento(id);
}

// Resumen del historial de pagos para decidir si corresponde marcar al cliente como moroso
function cargar_resumen_comportamiento(id_cliente) {
    var $cont = $('#resumen_comportamiento').html('<small class="text-muted">Cargando historial de pagos...</small>');
    $.ajax({
        type: "POST",
        url: urlweb + "api/Clientes/resumen_comportamiento_cliente",
        data: { id_cliente: id_cliente },
        dataType: 'json',
        success: function (r) {
            if (r.result.code != 1 || !r.result.resumen) {
                $cont.html('<small class="text-danger">No se pudo cargar el historial de pagos.</small>');
                return;
            }
            var d = r.result.resumen;
            var n = function (v) { return parseInt(v, 10) || 0; };
            var pagadas = n(d.cuotas.pagadas), con_atraso = n(d.cuotas.con_atraso);
            var filas = [
                ['Préstamos', n(d.prestamos.total) + ' (' + n(d.prestamos.activos) + ' activos, ' + n(d.prestamos.cancelados) + ' cancelados'
                    + (n(d.prestamos.en_recuperacion) ? ', ' + n(d.prestamos.en_recuperacion) + ' en recuperación' : '') + ')'],
                ['Cuotas pagadas', pagadas + (pagadas ? ' — ' + (pagadas - con_atraso) + ' a tiempo, ' + con_atraso + ' con atraso' : '')],
                ['Atraso al pagar', con_atraso ? ('promedio ' + Math.round(parseFloat(d.cuotas.prom_dias_atraso) || 0) + ' días, máximo ' + n(d.cuotas.max_dias_atraso) + ' días') : 'Sin atrasos'],
                ['Cuotas vencidas hoy', n(d.vencidas.vencidas) ? n(d.vencidas.vencidas) + ' (la más antigua con ' + n(d.vencidas.max_dias) + ' días)' : 'Ninguna'],
                ['Plazos vencidos con interés', n(d.renovaciones.veces) ? n(d.renovaciones.veces) + ' (S/ ' + (parseFloat(d.renovaciones.interes) || 0).toFixed(2) + ')' : 'Ninguno'],
                ['Veces marcado moroso', n(d.veces_moroso)]
            ];
            var $tabla = $('<table class="table table-sm table-bordered mb-1 small"><tbody></tbody></table>');
            filas.forEach(function (f) {
                $tabla.find('tbody').append($('<tr>').append($('<th class="bg-light" style="width:45%">').text(f[0]), $('<td>').text(f[1])));
            });
            $cont.empty()
                .append('<div class="fw-bold mb-1"><i class="fa fa-history me-1"></i> Historial de pagos del cliente</div>')
                .append($tabla)
                .append('<small class="text-muted">Un atraso aislado no convierte al cliente en moroso: revise su comportamiento antes de confirmar.</small>');
        },
        error: function () {
            $cont.html('<small class="text-danger">No se pudo cargar el historial de pagos.</small>');
        }
    });
}
function eliminar_cliente(id_cliente){
    $.ajax({
        type: "POST",
        url: urlweb + "api/Clientes/eliminar_cliente",
        data: {
            id : id_cliente
        },
        dataType: 'json',
        success:function (r) {
            switch (r.result.code) {
                case 1:
                    respuesta('¡Cliente eliminado! Recargando...', 'success');
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                    break;
                case 2:
                    respuesta('Error al borrar cliente', 'error');
                    break;
                default:
                    respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                    break;
            }
        }
    });
}