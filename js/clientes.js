function guardar_editar_clientes(){
    var valor = true;
    var boton = "btn-agregar-cliente";
    var id_cliente = $('#id_cliente').val();
    var cliente_nombre = $('#cliente_nombre').val();
    var cliente_apellidos = $('#cliente_apellidos').val();
    var cliente_dni = $('#cliente_dni').val();
    var cliente_celular = $("#cliente_celular").val();
    var cliente_email = $('#cliente_email').val();
    var cliente_genero = $("#cliente_genero").val();

    valor = validar_campo_vacio('cliente_nombre',cliente_nombre, valor);
    valor = validar_campo_vacio('cliente_apellidos',cliente_apellidos, valor);
    valor = validar_campo_vacio('cliente_dni',cliente_dni, valor);

    if(valor){
        var cadena = "cliente_nombre=" + cliente_nombre +
            "&cliente_apellidos=" + cliente_apellidos+
            "&cliente_dni=" + cliente_dni+
            "&cliente_celular=" + cliente_celular+
            "&cliente_email=" + cliente_email+
            "&cliente_genero=" + cliente_genero+
            "&id_cliente=" + id_cliente;
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
        $('#cliente_nombre').val(almacenar.cliente_nombre);
        $('#cliente_apellidos').val(almacenar.cliente_apellidos);
        $('#cliente_dni').val(almacenar.cliente_dni);
        $('#cliente_celular').val(almacenar.cliente_celular);
        $('#cliente_email').val(almacenar.cliente_email);
        $('#cliente_genero').val(almacenar.cliente_genero);
    });
    function edicion(cliente_nombre, cliente_apellidos, cliente_dni, cliente_celular,cliente_email,cliente_genero){
    }

}
function limpiar_clientes(){
    $('#id_cliente').val('');
    $('#cliente_nombre').val('');
    $('#cliente_apellidos').val('');
    $('#cliente_dni').val('');
    $('#cliente_celular').val('');
    $('#cliente_email').val('');
    $('#cliente_genero').val('');
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