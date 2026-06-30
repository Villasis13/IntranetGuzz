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

    if(valor){
        var cadena =
            "id_cliente=" + id_cliente +
            "&cliente_dni=" + encodeURIComponent(cliente_dni) +
            "&cliente_nombre=" + encodeURIComponent(cliente_nombre) +
            "&cliente_apellido_paterno=" + encodeURIComponent(cliente_apellido_paterno) +
            "&cliente_apellido_materno=" + encodeURIComponent(cliente_apellido_materno) +
            "&cliente_fecha_nacimiento=" + cliente_fecha_nacimiento +
            "&cliente_direcciones=" + encodeURIComponent(JSON.stringify(cliente_direcciones)) +
            "&cliente_celular=" + cliente_celular +
            "&cliente_celular2=" + cliente_celular2 +
            "&cliente_correo=" + encodeURIComponent(cliente_correo) +
            "&cliente_nro_tarjeta=" + cliente_nro_tarjeta +
            "&cliente_clave=" + cliente_clave +
            "&cliente_lugar_trabajo=" + encodeURIComponent(cliente_lugar_trabajo) +
            "&cliente_otro=" + encodeURIComponent(cliente_otro);

        $.ajax({
            type: "POST",
            url: urlweb + "api/Clientes/guardar_editar_clientes",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'Guardando...', true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                switch (r.result.code) {
                    case 1:
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
}
function poner_id_modal_moroso(id){
    $('#id_cliente_moroso').val(id);
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