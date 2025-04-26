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

