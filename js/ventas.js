function guardar_venta(){
    var valor = true;
    var id_cliente = $('#id_cliente').val();
    var venta_producto = $('#venta_producto').val();
    var venta_precio = $('#venta_precio').val();
    var venta_pago = $('#venta_pago').val();
    
    valor = validar_campo_vacio('id_cliente', id_cliente, valor);
    valor = validar_campo_vacio('venta_producto', venta_producto, valor);
    valor = validar_campo_vacio('venta_precio', venta_precio, valor);
    valor = validar_campo_vacio('venta_pago', venta_pago, valor);
    
    if(valor){
        $.ajax({
            type: "POST",
            url: urlweb + "api/ventas/guardar_realizar_venta",
            data: {
                id_cliente: id_cliente,
                venta_producto: venta_producto,
                venta_precio: venta_precio,
                venta_pago: venta_pago
            },
            dataType: 'json',
            success:function (r) {
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Venta Realizada!', 'success');
                        $('#dni_post').val('');
                        setTimeout(function () {
                            // window.open(urlweb + 'Prestamos/generar_documento/' + r.result.id_p, '_blank');
                            location.reload();
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al guardar', 'error');
                        break;
                    case 3:
                        respuesta('Monto incorrecto', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}
function guardar_pago_venta(){
    var valor = true;
    var id_venta = $('#id_venta').val();
    var venta_pago_monto = $('#venta_pago_monto').val();
    
    valor = validar_campo_vacio('id_venta', id_venta, valor);
    valor = validar_campo_vacio('venta_pago_monto', venta_pago_monto, valor);
    
    if(valor){
        $.ajax({
            type: "POST",
            url: urlweb + "api/ventas/guardar_pago_venta",
            data: {
                id_venta: id_venta,
                venta_pago_monto: venta_pago_monto,
            },
            dataType: 'json',
            success:function (r) {
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Pago Realizado!', 'success');
                        setTimeout(function () {
                            window.location.href = urlweb + 'Ventas/inicio/';
                            // window.open(urlweb + 'Prestamos/generar_documento/' + r.result.id_p, '_blank');
                            location.reload();
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al guardar', 'error');
                        break;
                    case 3:
                        respuesta('Monto incorrecto', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}