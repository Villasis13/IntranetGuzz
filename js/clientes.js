function guardar_editar_clientes(){
    var valor = true;
    var boton = "btn-agregar-cliente";

    var id_cliente = $('#id_cliente').val();
    var cliente_dni  = $('#cliente_dni ').val();
    var cliente_nombre = $('#cliente_nombre').val();
    var cliente_apellido_paterno = $('#cliente_apellido_paterno').val();
    var cliente_apellido_materno = $('#cliente_apellido_materno').val();
    var cliente_fecha_nacimiento = $('#cliente_fecha_nacimiento').val();
    var cliente_direccion = $('#cliente_direccion').val();
    var cliente_referencia = $('#cliente_referencia').val();
    var cliente_celular = $('#cliente_celular').val();
    var cliente_correo = $('#cliente_correo').val();
    var cliente_nro_tarjeta = $('#cliente_nro_tarjeta').val();
    var cliente_clave = $('#cliente_clave').val();
    var cliente_lugar_trabajo = $('#cliente_lugar_trabajo').val();
    var cliente_otro = $('#cliente_otro').val();

    valor = validar_campo_vacio('cliente_dni', cliente_dni, valor);
    valor = validar_campo_vacio('cliente_nombre', cliente_nombre, valor);
    valor = validar_campo_vacio('cliente_apellido_paterno', cliente_apellido_paterno, valor);
    valor = validar_campo_vacio('cliente_apellido_materno', cliente_apellido_materno, valor);
    valor = validar_campo_vacio('cliente_direccion', cliente_direccion, valor);
    valor = validar_campo_vacio('cliente_celular', cliente_celular, valor);

    if(valor){
        var cadena =
            "id_cliente=" + id_cliente +
            "&cliente_dni=" + cliente_dni +
            "&cliente_nombre=" + cliente_nombre +
            "&cliente_apellido_paterno=" + cliente_apellido_paterno +
            "&cliente_apellido_materno=" + cliente_apellido_materno +
            "&cliente_fecha_nacimiento=" + cliente_fecha_nacimiento +
            "&cliente_direccion=" + cliente_direccion +
            "&cliente_referencia=" + cliente_referencia +
            "&cliente_celular=" + cliente_celular +
            "&cliente_correo=" + cliente_correo +
            "&cliente_nro_tarjeta=" + cliente_nro_tarjeta +
            "&cliente_clave=" + cliente_clave +
            "&cliente_lugar_trabajo=" + cliente_lugar_trabajo +
            "&cliente_otro=" + cliente_otro;

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
        $('#cliente_direccion').val(almacenar.cliente_direccion);
        $('#cliente_referencia').val(almacenar.cliente_referencia);
        $('#cliente_celular').val(almacenar.cliente_celular);
        $('#cliente_correo').val(almacenar.cliente_correo);
        $('#cliente_nro_tarjeta').val(almacenar.cliente_nro_tarjeta);
        $('#cliente_clave').val(almacenar.cliente_clave);
        $('#cliente_lugar_trabajo').val(almacenar.cliente_lugar_trabajo);
        $('#cliente_otro').val(almacenar.cliente_otro);
    });

    function edicion(cliente_nombre, cliente_apellidos, cliente_dni, cliente_celular,cliente_email,cliente_genero){
    }

}
function limpiar_clientes(){
    $('#id_cliente').val('');
    $('#cliente_dni ').val('');
    $('#cliente_nombre').val('');
    $('#cliente_apellido_paterno').val('');
    $('#cliente_apellido_materno').val('');
    $('#cliente_fecha_nacimiento').val('');
    $('#cliente_direccion').val('');
    $('#cliente_referencia').val('');
    $('#cliente_celular').val('');
    $('#cliente_correo').val('');
    $('#cliente_nro_tarjeta').val('');
    $('#cliente_clave').val('');
    $('#cliente_lugar_trabajo').val('');
    $('#cliente_otro').val('');
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