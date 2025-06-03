function cambiar_proximo_cobro(tipo){
    var fecha_prestamo = $('#fecha_prestamo2').val();
    var domingo = $('#select_domingos').val();
    var fecha_nueva = '';

    if(tipo != 'diario'){
        $('#div_diario_domingos').hide(200);
        var fecha = new Date(fecha_prestamo);

        if(tipo == 'semanal'){
            fecha.setDate(fecha.getDate() + 7);
        } else {
            var mesActual = fecha.getMonth();
            fecha.setMonth(mesActual + 1);
            if (fecha.getDate() !== new Date(fecha.getFullYear(), mesActual + 1, 0).getDate()) {
                fecha.setDate(Math.min(fecha.getDate(), new Date(fecha.getFullYear(), fecha.getMonth() + 1, 0).getDate()));
            }
        }

        // Validar si cae en domingo y si "domingo" es 'no'
        if(domingo == 'no' && fecha.getDay() == 0){
            fecha.setDate(fecha.getDate() + 1); // Sumar un día si es domingo
        }

        fecha_nueva = fecha.toISOString().split('T')[0];

    } else {
        $('#div_diario_domingos').show(200);
        if(domingo == 'no'){
            var fecha = new Date(fecha_prestamo);
            fecha.setDate(fecha.getDate() + 1);
            if(fecha.getDay() == 0){
                fecha.setDate(fecha.getDate() + 1);
            }
            fecha_nueva = fecha.toISOString().split('T')[0];
        } else {
            var fecha = new Date(fecha_prestamo);
            fecha.setDate(fecha.getDate() + 1);
            fecha_nueva = fecha.toISOString().split('T')[0];
        }
    }

    $('#fecha_prox_cobro2').val(fecha_nueva);
}
function guardar_nuevo_linea_credito(){
    var valor = true;
    var boton = "btn_alc";
    var id_cliente = $('#id_cliente').val();
    var incremento = $('#incremento').val();
    var clave_validacion = $('#clave_validacion').val();
    var motivo_aumento = $('#motivo_aumento').val();
    
    valor = validar_campo_vacio('id_cliente', id_cliente, valor);
    valor = validar_campo_vacio('incremento', incremento, valor);
    valor = validar_campo_vacio('clave_validacion', clave_validacion, valor);
    valor = validar_campo_vacio('motivo_aumento', motivo_aumento, valor);
    
    if(valor){
        $.ajax({
            type: "POST",
            url: urlweb + "api/prestamos/guardar_nueva_linea_credito",
            data: {
                id_cliente: id_cliente,
                incremento: incremento,
                clave_validacion: clave_validacion,
                motivo_aumento: motivo_aumento
            },
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'Guardando...', true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save \"></i> Guardar", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Línea de crédito Guardada!', 'success');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al guardar', 'error');
                        break;
                    case 3:
                        respuesta('Contraseña Incorrecta', 'error');
                        break;
                    case 4:
                        respuesta('El monto debe ser mayor que cero', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}
function validar_monto_linea(){
    var monto_prestamo = parseFloat($('#monto_prestamo').val()) || 0;
    var linea_actual = parseFloat($('#linea_actual').val()) || 0;

    if(monto_prestamo > linea_actual){
        respuesta('EL monto del préstamo no puede ser mayor a la línea de crédito actual', 'error');
        $('#monto_prestamo').val(0);
    }
}
function guardar_prestamo(){
    var valor = true;
    var id_cliente = $('#id_cliente').val();
    var prestamo_monto = $('#monto_prestamo').val();
    var prestamo_interes = $('#interes').val();
    var prestamo_tipo_pago = $('input[name="tipo_pago2"]:checked').val();
    var prestamo_num_cuotas = $('#prestamo_num_cuotas').val();
    var prestamo_fecha = $('#fecha_prestamo2').val();
    var prestamo_prox_cobro = $('#fecha_prox_cobro2').val();
    var prestamo_garantia = $('#prestamo_garantia').val();
    var prestamo_garante = $('#prestamo_garante').val();
    var prestamo_motivo = $('#prestamo_motivo').val();
    var prestamo_comentario = $('#prestamo_comentario').val();
    var select_domingos = $('#select_domingos').val();
    // var prestamo_monto_interes = $('#prestamo_monto_interes').val();

    valor = validar_campo_vacio('id_cliente', id_cliente, valor);
    valor = validar_campo_vacio('monto_prestamo', prestamo_monto, valor);
    valor = validar_campo_vacio('interes', prestamo_interes, valor);
    valor = validar_campo_vacio('prestamo_num_cuotas', prestamo_num_cuotas, valor);
    valor = validar_campo_vacio('fecha_prestamo2', prestamo_fecha, valor);
    valor = validar_campo_vacio('fecha_prox_cobro2', prestamo_prox_cobro, valor);
    valor = validar_campo_vacio('prestamo_garantia', prestamo_garantia, valor);
    valor = validar_campo_vacio('prestamo_garante', prestamo_garante, valor);
    valor = validar_campo_vacio('prestamo_motivo', prestamo_motivo, valor);
    valor = validar_campo_vacio('prestamo_comentario', prestamo_comentario, valor);
    //Si var valor no ha cambiado de valor, procedemos a hacer la llamada de ajax
    if(valor){
        $.ajax({
            type: "POST",
            url: urlweb + "api/prestamos/guardar_prestamo",
            data: {
                id_cliente: id_cliente,
                prestamo_monto: prestamo_monto,
                prestamo_interes: prestamo_interes,
                prestamo_tipo_pago: prestamo_tipo_pago,
                prestamo_num_cuotas: prestamo_num_cuotas,
                prestamo_fecha: prestamo_fecha,
                prestamo_prox_cobro: prestamo_prox_cobro,
                prestamo_garantia: prestamo_garantia,
                prestamo_garante: prestamo_garante,
                prestamo_motivo: prestamo_motivo,
                prestamo_comentario: prestamo_comentario,
                select_domingos: select_domingos
                // prestamo_monto_interes: prestamo_monto_interes
            },
            dataType: 'json',
            // beforeSend: function () {
            //     cambiar_estado_boton(boton, 'Guardando...', true);
            // },
            success:function (r) {
                // cambiar_estado_boton(boton, "<i class=\"fa fa-save \"></i> Guardar", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Préstamo Guardado!', 'success');
                        console.log(r.result.id_p)
                        setTimeout(function () {
                            // location.reload();
                            window.open(urlweb + 'Prestamos/generar_documento/' + r.result.id_p, '_self');
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al guardar', 'error');
                        break;
                    case 3:
                        respuesta('Contraseña Incorrecta', 'error');
                        break;
                    case 4:
                        respuesta('El monto debe ser mayor que cero', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}
function guardar_pago_prestamo(){
    var valor = true;
    var id_prestamo = $('#id_prestamo').val();
    var pago_monto = $('#pago_monto').val();
    var pago_recepcion = $('#pago_recepcion').val();
    var pago_metodo = $('#pago_metodo').val();
    var prestamo_prox_cobro = $('#prestamo_prox_cobro').val();
    
    valor = validar_campo_vacio('id_prestamo', id_prestamo, valor);
    valor = validar_campo_vacio('pago_monto', pago_monto, valor);
    valor = validar_campo_vacio('pago_recepcion', pago_recepcion, valor);
    valor = validar_campo_vacio('pago_metodo', pago_metodo, valor);
    valor = validar_campo_vacio('prestamo_prox_cobro', prestamo_prox_cobro, valor);
    
    if(valor){
        $.ajax({
            type: "POST",
            url: urlweb + "api/cobros/guardar_pago",
            data: {
                id_prestamo : id_prestamo,  
                pago_monto: pago_monto,
                pago_recepcion: pago_recepcion,
                pago_metodo: pago_metodo,
                prestamo_prox_cobro: prestamo_prox_cobro
            },
            dataType: 'json',
            // beforeSend: function () {
            //     cambiar_estado_boton(boton, 'Guardando...', true);
            // },
            success:function (r) {
                // cambiar_estado_boton(boton, "<i class=\"fa fa-save \"></i> Guardar", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Pago Guardado!', 'success');
                        setTimeout(function () {
                            // location.reload();
                            window.open(urlweb + 'Cobros/generar_documento/' + r.result.id_pago, '_blank');
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al guardar', 'error');
                        break;
                    case 3:
                        respuesta('El monto supera a lo que falta pagar', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}
function guardar_aplicar_descuento(id_prestamo){
    var valor = true;
    var input_resta_por_pagar = $('#input_resta_por_pagar').val();
    var descontar_cantidad = $('#descontar_cantidad').val();
    
    valor = validar_campo_vacio('input_resta_por_pagar', input_resta_por_pagar, valor);
    valor = validar_campo_vacio('descontar_cantidad', descontar_cantidad, valor);
    
    if(parseFloat(input_resta_por_pagar) < parseFloat(descontar_cantidad)){
        respuesta('La cantidad a descontar no puede ser mayor al monto restante por pagar', 'error');
        valor = false;
    }
    
    if(valor){
        $.ajax({
            type: "POST",
            url: urlweb + "api/cobros/aplicar_descuento",
            data: {
                id_prestamo : id_prestamo,
                input_resta_por_pagar: input_resta_por_pagar,
                descontar_cantidad: descontar_cantidad
            },
            dataType: 'json',
            // beforeSend: function () {
            //     cambiar_estado_boton(boton, 'Guardando...', true);
            // },
            success:function (r) {
                // cambiar_estado_boton(boton, "<i class=\"fa fa-save \"></i> Guardar", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Descuento Aplicado!', 'success');
                        setTimeout(function () {
                            location.reload();
                            // window.open(urlweb + 'Prestamos/generar_documento/' + r.result.id_p, '_self');
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
        });
    }
}
function aplicar_descuento(){
    var opcionSeleccionada = $('input[name="desc"]:checked').attr('id');
    if (opcionSeleccionada === 'descSi') {
        $('#div_descontar').show(200);
    }else{
        $('#div_descontar').hide(200);
        $('#descontar_cantidad').val('');
    }
}
