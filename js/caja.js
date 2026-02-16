function gestionar_caja(){
    var valor = true;
    var boton = "btn-abrir_caja";
    var id_caja = $('#id_caja').val();
    var caja_monto = $('#caja_monto').val();
    // var fecha_caja = $('#fecha_caja').val();
    valor = validar_campo_vacio('caja_monto',caja_monto, valor);
    if(valor){
        var cadena = "id_caja=" + id_caja +
        "&caja_monto=" + caja_monto;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Caja/abrir_caja",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'Abriendo caja...', true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Abrir Caja", false);
                switch (r.result.code) {
                    case 1:
                        if(id_caja != ""){
                            respuesta('¡Caja abierta', 'success');
                        } else {
                            respuesta('¡Caja abierta! Recargando...', 'success');
                        }
                        setTimeout(function () { location.reload(); }, 1000);
                        break;
                    case 2:
                        respuesta('Error al abrir caja', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}


function cerrar_caja(id_caja){
    var valor = true;
    var boton = "btn-cerrar-caja";
//    valor = validar_campo_vacio('caja_monto',caja_monto, valor);
    if(valor){
        var cadena = "id_caja=" + id_caja;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Caja/cerrar_caja",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'Abriendo caja...', true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Abrir Caja", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Caja Cerrada! Recargando...', 'success');
                        setTimeout(function () {
                            window.location.href = urlweb + "Caja/reporte_de_cajas"; // <- tu ruta
                        }, 800);
                        break;
                    case 2:
                        respuesta('Error al cerrar caja', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}


function pago_x_id(id_pago) {

    var cadena = "id_pago=" + id_pago;


    $.ajax({
        type: "POST",
        url: urlweb + "api/Caja/listar_pago",
        data: cadena,
        dataType: 'json',
        success: function (r) {
            if (r.result.code == 1){

                let pago = r.result.pago;
                console.log(pago)
                if(pago){
                    $('#pago_monto').val(pago.pago_monto);
                    $('#id_pago').val(pago.id_pago);
                }
            }else{

            }
        }
    });
}

function guardar_monto() {
    var valor = true;

    // ====== CAMPOS DEL MODAL ======
    var pago_monto = $('#pago_monto').val();
    var id_pago = $('#id_pago').val();
    var contrasenha = $('#contrasenha').val();

    // ====== VALIDACIONES (vacíos) ======
 //   valor = validar_campo_vacio('alumno_nombre', alumno_nombre, valor);

    // ====== AJAX ======
    if (valor) {
        var boton = "btn-agregar-monto";

        var cadena = "pago_monto=" + pago_monto;
        cadena     += "&id_pago=" + id_pago;
        cadena     += "&contrasenha=" + contrasenha;

        $.ajax({
            type: "POST",
            url: urlweb + "api/Caja/guardar_monto", // Controlador para guardar
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, "Guardando...", true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar Profesor", false);

                switch (r.result.code) {
                    case 1:
                        respuesta('¡Monto editado exitosamente!', 'success');
                        setTimeout(function () {
                            location.reload(); // Recargar la página
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al guardar profesor', 'error');
                        break;
                    case 3:
                        respuesta('Necesitas la contraseña de un Supervisor', 'warning');
                        break;
                    default:
                        respuesta('¡Algo catastrófico ha ocurrido!', 'error');
                        break;
                }
            },
            error: function () {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar Profesor", false);
                respuesta('Error de conexión o servidor', 'error');
            }
        });
    }
}

function guardar_movimiento_caja() {
    var valor = true;

    // ====== CAMPOS DEL MODAL ======
    var monto_caja = $('#monto_caja').val();
    var contrasenha = $('#contrasenha').val();

    // ====== VALIDACIONES (vacíos) ======
    //   valor = validar_campo_vacio('alumno_nombre', alumno_nombre, valor);

    // ====== AJAX ======
    if (valor) {
        var boton = "btn-agregar-monto";

        var cadena = "monto_caja=" + monto_caja;
        cadena     += "&contrasenha=" + contrasenha;

        $.ajax({
            type: "POST",
            url: urlweb + "api/Caja/guardar_movimiento_caja", // Controlador para guardar
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, "Guardando...", true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar Profesor", false);

                switch (r.result.code) {
                    case 1:
                        respuesta('¡Dinero añadido exitosamente!', 'success');
                        setTimeout(function () {
                            location.reload(); // Recargar la página
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al añadir dinero', 'error');
                        break;
                    case 3:
                        respuesta('Necesitas la contraseña de un Supervisor', 'warning');
                        break;
                    default:
                        respuesta('¡Algo catastrófico ha ocurrido!', 'error');
                        break;
                }
            },
            error: function () {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar Profesor", false);
                respuesta('Error de conexión o servidor', 'error');
            }
        });
    }
}




function guardar_nuevo_monto(){
    var valor = true;
    var id_caja = $('#id_caja').val();
    var input_editar_monto = $('#input_editar_monto').val();
    // var fecha_caja = $('#fecha_caja').val();
    valor = validar_campo_vacio('input_editar_monto',input_editar_monto, valor);
    if(valor){
        var cadena = "id_caja=" + id_caja +
        "&input_editar_monto=" + input_editar_monto;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Caja/editar_caja",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                // cambiar_estado_boton(boton, 'Abriendo caja...', true);
            },
            success:function (r) {
                // cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Abrir Caja", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Caja actualizada', 'success');
                        setTimeout(function () { location.reload(); }, 1000);
                        break;
                    case 2:
                        respuesta('Error al actualizar caja', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}
function gestionar_caja_ultimo_monto(){
    var valor = true;
    var boton = "btn-abrir_caja_ultimo_monto";
    var id_caja = $('#id_caja').val();
    var caja_monto = $('#caja_monto').val();
    // var fecha_caja = $('#fecha_caja').val();
    // valor = validar_campo_vacio('caja_monto',caja_monto, valor);
    if(valor){
        var cadena = "id_caja=" + id_caja +
        "&caja_monto=" + caja_monto;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Caja/abrir_caja_ultimo_monto",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'Abriendo caja...', true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Abrir Caja", false);
                switch (r.result.code) {
                    case 1:
                        
                        // if(id_caja != ""){
                        //     respuesta('¡Caja abierta', 'success');
                        // } else {
                        //     respuesta('¡Caja abierta! Recargando...', 'success');
                        // }
                        // setTimeout(function () { location.reload(); }, 1000);
                        respuesta('Monto encontrado', 'success');
                        $('#caja_monto').val(r.result.dato_m);
                        break;
                    case 2:
                        respuesta('Error al abrir caja', 'error');
                        break;
                    case 3:
                        respuesta('No existe un registro anterior', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}
function habilitar_input_editar_monto(){
    $('#input_editar_monto').show(200);
    $('#btn_editar').show(200);
}

