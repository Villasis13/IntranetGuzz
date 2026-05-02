function cambiar_proximo_cobro(tipo){
    var fecha_prestamo = $('#fecha_prestamo2').val();
    var domingo = $('#select_domingos').val();
    var fecha_nueva = '';
    var num_cuotas = 0;

    if(tipo != 'diario'){
        $('#div_diario_domingos').hide(200);
        var fecha = new Date(fecha_prestamo);

        if(tipo == 'semanal'){
            fecha.setDate(fecha.getDate() + 7);
            num_cuotas = 7;
        } else {
            var mesActual = fecha.getMonth();
            var anioActual = fecha.getFullYear();

            // Obtener días del mes siguiente (30 o 31)
            var diasMesSiguiente = new Date(anioActual, mesActual + 1, 0).getDate();
            num_cuotas = diasMesSiguiente;

            fecha.setMonth(mesActual + 1);
            if (fecha.getDate() !== new Date(fecha.getFullYear(), mesActual + 1, 0).getDate()) {
                fecha.setDate(Math.min(fecha.getDate(), new Date(fecha.getFullYear(), fecha.getMonth() + 1, 0).getDate()));
            }
        }

        if(domingo == 'no' && fecha.getDay() == 0){
            fecha.setDate(fecha.getDate() + 1);
        }

        fecha_nueva = fecha.toISOString().split('T')[0];

    } else {
        $('#div_diario_domingos').show(200);
        var fecha = new Date(fecha_prestamo);
        fecha.setDate(fecha.getDate() + 1);

        if(domingo == 'no' && fecha.getDay() == 0){
            fecha.setDate(fecha.getDate() + 1);
        }

        fecha_nueva = fecha.toISOString().split('T')[0];
        num_cuotas = 1;
    }

    $('#fecha_prox_cobro2').val(fecha_nueva);
    $('#prestamo_num_cuotas').val(num_cuotas);
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
                            window.location.href = urlweb + 'Prestamos/inicio';
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

function guardar_prestamo() {
    var valor = true;
    var id_cliente = $('#id_cliente').val();
    var prestamo_monto = $('#monto_prestamo').val();
    var prestamo_interes = $('#interes').val();
    var prestamo_tipo_pago = $('input[name="tipo_pago2"]:checked').val();
    var prestamo_num_cuotas = $('#prestamo_num_cuotas').val();
    var prestamo_fecha = $('#fecha_prestamo2').val();
    var prestamo_prox_cobro = $('#fecha_prox_cobro2').val();
    var prestamo_fecha_inicio = $('#prestamo_fecha_inicio').val();
    var prestamo_garantia = $('#prestamo_garantia').val();
    var prestamo_garante = $('#prestamo_garante').val();
    var prestamo_motivo = $('#prestamo_motivo').val();
    var prestamo_comentario = $('#prestamo_comentario').val();
    var select_domingos = $('#select_domingos').val();
    var forzar = $('#forzar_garante').val();

    // Validaciones
    valor = validar_campo_vacio('id_cliente', id_cliente, valor);
    valor = validar_campo_vacio('monto_prestamo', prestamo_monto, valor);
    valor = validar_campo_vacio('interes', prestamo_interes, valor);
    valor = validar_campo_vacio('prestamo_num_cuotas', prestamo_num_cuotas, valor);
    valor = validar_campo_vacio('fecha_prestamo2', prestamo_fecha, valor);
    valor = validar_campo_vacio('fecha_prox_cobro2', prestamo_prox_cobro, valor);
    valor = validar_campo_vacio('prestamo_garantia', prestamo_garantia, valor);
    valor = validar_campo_vacio('prestamo_motivo', prestamo_motivo, valor);
    valor = validar_campo_vacio('prestamo_comentario', prestamo_comentario, valor);
    valor = validar_campo_vacio('prestamo_fecha_inicio', prestamo_fecha_inicio, valor);

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
                prestamo_fecha_inicio: prestamo_fecha_inicio,
                prestamo_garantia: prestamo_garantia,
                prestamo_garante: prestamo_garante,
                prestamo_motivo: prestamo_motivo,
                prestamo_comentario: prestamo_comentario,
                select_domingos: select_domingos,
                forzar_garante: forzar
            },
            dataType: 'json',
            success:function (r) {
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Préstamo Guardado!', 'success');
                        setTimeout(function () {
                            window.open(urlweb + 'Prestamos/generar_documento/' + r.result.id_p, '_blank');
                            window.location.href = urlweb + 'Clientes/inicio';
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al guardar', 'error');
                        break;
                    case 3:
                        respuesta('Monto insuficiente en caja', 'error');
                        break;
                    case 4:
                        Swal.fire({
                            title: 'Garante ya asociado',
                            text: 'Esta persona ya está asociada como garante en otro préstamo. ¿Desea continuar igual?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Sí, continuar',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#forzar_garante').val('1');
                                guardar_prestamo();
                            }
                        });
                        break;
                    case 5:
                        respuesta('El garante no puede ser el mismo titular del préstamo.', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}

function transferir_prestamo(id_prestamos){
    $.ajax({
        type: "POST",
        url: urlweb + "api/prestamos/transferir_prestamo",
        data: {
            id_prestamos : id_prestamos
        },
        dataType: 'json',
        success:function (r) {
            switch (r.result.code) {
                case 1:
                    respuesta('¡Préstamo Transferido a Antiguo!', 'success');
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                    break;
                case 2:
                    respuesta('Error al transferir préstamo', 'error');
                    break;
                default:
                    respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                    break;
            }
        }
    });
}

function guardar_pago_prestamo(){
    var valor = true;
    var boton = "btn-guardar-pago";

    var id_prestamo = $('#id_prestamo').val();
    var id_pago = $('#id_pago').val();
    var prestamo_prox_cobro = $('#prestamo_prox_cobro').val();
    var pago_metodo = $('#pago_metodo').val();

    // NUEVOS CAMPOS DINÁMICOS
    var monto_pagar = $('#monto_pagar').val();
    var monto_recibido = $('#monto_recibido').val();
    var monto_vuelto = $('#monto_vuelto').val();
    var num_operacion = $('#num_operacion').val();
    var nombre_titular = $('#nombre_titular').val();
    var banco_entidad = $('#banco_entidad').val();
    var fecha_transferencia = $('#fecha_transferencia').val();
    var pago_observacion = $('#pago_observacion').val();

    // CAPTURAMOS EL DESCUENTO DINÁMICAMENTE
    var descuento = $('#descontar_cantidad').val();
    var tope_cuota = $('#monto_cuota_actual').val();

    if(descuento === "" || isNaN(descuento)) {
        descuento = 0;
    }

    if(parseFloat(descuento) > parseFloat(tope_cuota)) {
        respuesta('Error: El descuento supera el monto de la cuota actual.', 'error');
        return false;
    }

    // Validaciones obligatorias
    valor = validar_campo_vacio('id_prestamo', id_prestamo, valor);
    valor = validar_campo_vacio('pago_metodo', pago_metodo, valor);
    valor = validar_campo_vacio('monto_pagar', monto_pagar, valor);

    if(valor){
        $.ajax({
            type: "POST",
            url: urlweb + "api/cobros/guardar_pago",
            data: {
                id_prestamo : id_prestamo,
                id_pago: id_pago,
                prestamo_prox_cobro: prestamo_prox_cobro,
                pago_metodo: pago_metodo,
                monto_pagar: monto_pagar,
                monto_recibido: monto_recibido,
                monto_vuelto: monto_vuelto,
                num_operacion: num_operacion,
                nombre_titular: nombre_titular,
                banco_entidad: banco_entidad,
                fecha_transferencia: fecha_transferencia,
                pago_observacion: pago_observacion,
                descuento: descuento
            },
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'Guardando...', true);
            },
            success:function (r) {
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Pago Guardado!', 'success');
                        setTimeout(function () {
                            window.open(urlweb + 'Cobros/generar_documento/' + r.result.id_pago, '_blank');
                            window.location.href = urlweb + 'Clientes/inicio';
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al guardar', 'error');
                        cambiar_estado_boton(boton, 'Confirmar Pago', false);
                        break;
                    case 3:
                        respuesta('El monto supera a lo que falta pagar', 'error');
                        cambiar_estado_boton(boton, 'Confirmar Pago', false);
                        break;
                    default:
                        respuesta('¡Algo catastrófico ha ocurrido!', 'error');
                        cambiar_estado_boton(boton, 'Confirmar Pago', false);
                        break;
                }
            }
        });
    }
}

function cambiar_prestamo_a_antiguo(id_prestamo){
    var valor = true;
    if(valor){
        $.ajax({
            type: "POST",
            url: urlweb + "api/admin/cambiar_estado",
            data: {
                id_prestamo : id_prestamo,
            },
            dataType: 'json',
            success:function (r) {
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Prestamo actualizado!', 'success');
                        setTimeout(function () {
                            window.location.href = (urlweb + 'Admin/inicio/');
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
            success:function (r) {
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Descuento Aplicado!', 'success');
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
        });
    }
}

function obtenerDiasDelMes() {
    let fechaSeleccionada = $('#fecha_prestamo2').val();
    let fecha = fechaSeleccionada ? new Date(fechaSeleccionada + 'T00:00:00') : new Date();
    return new Date(fecha.getFullYear(), fecha.getMonth() + 1, 0).getDate();
}

function ajustar_interfaz_tipo_pago(conservar_cuotas = false) {
    let tipoPago = $('input[name="tipo_pago2"]:checked').val()?.toLowerCase();
    if(!tipoPago) return;

    $('#div_cuota_diaria').show();
    if (tipoPago === 'diario') {
        $('#label_cuotas_dias').html('Días a Pagar <span class="text-danger">*</span>');
        $('#div_diario_domingos').show();
    } else {
        $('#label_cuotas_dias').html('Número de Cuotas <span class="text-danger">*</span>');
        $('#div_diario_domingos').hide();
    }

    if (!conservar_cuotas) {
        let nuevasCuotas = 1;
        if (tipoPago === 'diario') {
            let incluirDom = $('#select_domingos').val();
            nuevasCuotas = (incluirDom === 'si') ? obtenerDiasDelMes() : 26;
        } else if (tipoPago === 'semanal') {
            nuevasCuotas = 4;
        } else if (tipoPago === 'mensual') {
            nuevasCuotas = 1;
        }
        $('#prestamo_num_cuotas').val(nuevasCuotas);
    }
    cambiar_proximo_cobro();
    calcular_cuota();
}

function calcular_cuota() {
    let montoStr = $('#monto_prestamo').val();
    let interesStr = $('#interes').val();
    let cuotasStr = $('#prestamo_num_cuotas').val();

    let monto = parseFloat(montoStr) || 0;
    let porcentajeInteres = parseFloat(interesStr) || 0;
    let cuotas = parseInt(cuotasStr) || 1;

    let cuota_redondeada = 0;
    let cuota_real_sin_redondear = 0;

    if (monto > 0 && cuotas > 0) {
        let monto_interes = monto * (porcentajeInteres / 100);
        let total_prestamo = monto + monto_interes;
        cuota_real_sin_redondear = total_prestamo / cuotas;
        cuota_redondeada = Math.ceil(cuota_real_sin_redondear * 10) / 10;
    }

    $('#cuota_diaria_visual').val(cuota_redondeada.toFixed(2));
    $('#cuota_calculada_hidden').val(cuota_redondeada.toFixed(2));
}

function cambiar_proximo_cobro() {
    let fechaBaseStr = $('#fecha_prestamo2').val();
    if (!fechaBaseStr) return;

    let fecha = new Date(fechaBaseStr + 'T00:00:00');
    fecha.setDate(fecha.getDate() + 1);

    let tipoPago = $('input[name="tipo_pago2"]:checked').val()?.toLowerCase();
    let incluirDomingos = $('#select_domingos').val();

    if (tipoPago === 'diario') {
        fecha.setDate(fecha.getDate() + 1);
    } else if (tipoPago === 'semanal') {
        fecha.setDate(fecha.getDate() + 7);
    } else if (tipoPago === 'mensual') {
        fecha.setMonth(fecha.getMonth() + 1);
    }

    if (incluirDomingos === 'no' && fecha.getDay() === 0) {
        fecha.setDate(fecha.getDate() + 1);
    }

    let anio = fecha.getFullYear();
    let mes = String(fecha.getMonth() + 1).padStart(2, '0');
    let dia = String(fecha.getDate()).padStart(2, '0');
    let fechaFinal = `${anio}-${mes}-${dia}`;

    $('#fecha_prox_cobro2').val(fechaFinal);
}

function preguntar_anular_prestamo(id_prestamo) {
    Swal.fire({
        title: '¿Anular este crédito?',
        text: "El préstamo se cancelará y el dinero prestado retornará automáticamente a la caja actual. Esta acción no se puede deshacer.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, anular crédito',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                url: urlweb + "api/cobros/anular",
                data: { id_prestamo: id_prestamo },
                dataType: 'json',
                success: function (res) {
                    if (res.codigo === 1) {
                        respuesta('Crédito anulado y dinero devuelto a caja', 'success');
                        setTimeout(function () {
                            location.href = urlweb + 'Prestamos/inicio';
                        }, 1500);
                    } else {
                        respuesta(res.mensaje, 'error');
                    }
                },
                error: function () {
                    respuesta('Error de comunicación con el servidor', 'error');
                }
            });
        }
    });
}

function actualizar_mensaje_inicio() {
    const fechaEmision = $('#fecha_prestamo2').val();
    if (!fechaEmision) return;

    const partes = fechaEmision.split('-');
    const anio = parseInt(partes[0], 10);
    const mes  = parseInt(partes[1], 10) - 1;
    const dia  = parseInt(partes[2], 10);

    const fecha = new Date(anio, mes, dia);
    fecha.setDate(fecha.getDate() + 1);

    const d = String(fecha.getDate()).padStart(2, '0');
    const m = String(fecha.getMonth() + 1).padStart(2, '0');
    const y = fecha.getFullYear();

    $('#texto_fecha_inicio').text(`${d}-${m}-${y}`);
    $('#prestamo_fecha_inicio').val(`${y}-${m}-${d}`);
}

function cambiar_metodo_pago() {
    // CAMBIO AQUÍ: Ahora leemos el 'data-tipo' (efectivo, yape, etc.)
    let metodo = $('#pago_metodo').find(':selected').data('tipo');

    // 1. Ocultamos todos los grupos
    $('#grupo_efectivo').hide();
    $('#grupo_transferencia').hide();
    $('#grupo_operacion_titular').hide();

    // 2. Limpiamos los valores por seguridad
    $('#monto_recibido').val('');
    $('#monto_vuelto').val('0.00');
    $('#num_operacion').val('');
    $('#nombre_titular').val('');
    $('#banco_entidad').val('');

    // 3. Mostramos lo que corresponde
    if (metodo === 'efectivo') {
        $('#grupo_efectivo').show(200);
    } else if (metodo === 'transferencia') {
        $('#grupo_transferencia').show(200);
        $('#grupo_operacion_titular').show(200);
    } else if (metodo === 'yape' || metodo === 'plin') {
        $('#grupo_operacion_titular').show(200);
    }

    // 4. Forzamos el cálculo
    calcular_vuelto();
}

function calcular_vuelto() {
    let monto_pagar = parseFloat($('#monto_pagar').val()) || 0;
    let metodo = $('#pago_metodo').find(':selected').data('tipo');

    if (metodo === 'transferencia' || metodo === 'yape' || metodo === 'plin') {
        $('#monto_recibido').val(monto_pagar.toFixed(2));
        $('#monto_vuelto').val('0.00');
    } else {
        let monto_recibido = parseFloat($('#monto_recibido').val()) || 0;

        if (monto_recibido >= monto_pagar && monto_pagar > 0) {
            let vuelto = monto_recibido - monto_pagar;
            $('#monto_vuelto').val(vuelto.toFixed(2));
        } else {
            $('#monto_vuelto').val('0.00');
        }
    }

    // ==========================================
    // LLAMAMOS AL RESUMEN PARA QUE SE ACTUALICE
    // ==========================================
    actualizar_resumen();
}

function aplicar_descuento() {
    var opcionSeleccionada = $('input[name="desc"]:checked').attr('id');

    if (opcionSeleccionada === 'descSi') {
        $('#div_descontar').show(200);
    } else {
        // SI ELIGEN "NO"
        $('#div_descontar').hide(200);
        $('#descontar_cantidad').val(''); // Limpiamos el descuento

        let cuota_original = parseFloat($('#monto_cuota_actual').val()) || 0;

        if(cuota_original) {
            $('#monto_pagar').val(cuota_original.toFixed(2));

            // Restauramos la tarjeta azul a la normalidad
            $('#texto_cuota_principal').html('S/ ' + cuota_original.toFixed(2));
            $('#texto_cuota_principal').removeClass('text-success').addClass('text-gray-800');
            $('#badge_descuento_visual').fadeOut(150);

            // OCULTAMOS LA ETIQUETA DEL INPUT (Porque dijeron que NO)
            $('#etiqueta_descuento').hide();
        }

        calcular_vuelto();
    }
}
// ==========================================
// UN SOLO BLOQUE DE INICIALIZACIÓN
// ==========================================
$(document).ready(function() {
    // Select2
    if ($("#prestamo_garante").length > 0) {
        $("#prestamo_garante").select2({
            width: '100%',
            placeholder: "Seleccionar Garante",
            allowClear: true
        });
    }

    // Buscador
    $('#formBuscar').on('submit', function (e) {
        let dni = $('#dni_post').val()?.trim();
        if (!dni) {
            e.preventDefault();
            respuesta('Ingrese DNI para buscar', 'error');
            return;
        }
        respuesta('Buscando cliente...', 'success');
    });

    // Lógica para la vista de Préstamos
    if ($('#monto_prestamo').length > 0) {
        ajustar_interfaz_tipo_pago();
        calcular_cuota();
    }

    // Lógica para la vista de Cobros (Descuentos y Pagos)
    if ($('#pago_metodo').length > 0) {
        cambiar_metodo_pago();
        aplicar_descuento();

        $('#descontar_cantidad').on('keyup', function() {
            let descuento_ingresado = parseFloat($(this).val()) || 0;
            let cuota_original = parseFloat($('#monto_cuota_actual').val());

            if (descuento_ingresado > cuota_original) {
                respuesta('El descuento no puede ser mayor a la cuota (S/ ' + cuota_original.toFixed(2) + ')', 'warning');
                $(this).val(cuota_original.toFixed(2));
                descuento_ingresado = cuota_original;
            }

            let nuevo_monto_a_pagar = cuota_original - descuento_ingresado;
            $('#monto_pagar').val(nuevo_monto_a_pagar.toFixed(2));

            calcular_vuelto();
        });
    }
    if ($('#pago_metodo').length > 0) {
        cambiar_metodo_pago();
        aplicar_descuento();

        // AQUÍ ES DONDE ESTÁ EL EVENTO QUE DEBES REEMPLAZAR:
        $('#descontar_cantidad').on('keyup', function() {
            let descuento_ingresado = parseFloat($(this).val()) || 0;
            let cuota_original = parseFloat($('#monto_cuota_actual').val());

            if (descuento_ingresado > cuota_original) {
                respuesta('El descuento no puede ser mayor a la cuota (S/ ' + cuota_original.toFixed(2) + ')', 'warning');
                $(this).val(cuota_original.toFixed(2));
                descuento_ingresado = cuota_original;
            }

            let nuevo_monto_a_pagar = cuota_original - descuento_ingresado;
            $('#monto_pagar').val(nuevo_monto_a_pagar.toFixed(2));

            // ==========================================
            // MAGIA VISUAL: TARJETA Y ETIQUETA DEL INPUT
            // ==========================================
            if (descuento_ingresado > 0) {
                $('#texto_cuota_principal').html('S/ ' + nuevo_monto_a_pagar.toFixed(2));
                $('#texto_cuota_principal').addClass('text-success').removeClass('text-gray-800');
                $('#texto_descuento_aplicado').text('- S/ ' + descuento_ingresado.toFixed(2));
                $('#badge_descuento_visual').fadeIn(150);

                // MOSTRAMOS LA ETIQUETA "Con Descuento" EN EL INPUT
                $('#etiqueta_descuento').fadeIn(150);
            } else {
                $('#texto_cuota_principal').html('S/ ' + cuota_original.toFixed(2));
                $('#texto_cuota_principal').removeClass('text-success').addClass('text-gray-800');
                $('#badge_descuento_visual').fadeOut(150);

                // OCULTAMOS LA ETIQUETA SI EL DESCUENTO ES CERO
                $('#etiqueta_descuento').fadeOut(150);
            }

            calcular_vuelto();
        });
    }
});

function actualizar_resumen() {
    // 1. Capturamos todos los valores actuales
    let cuota_original = parseFloat($('#monto_cuota_actual').val()) || 0;
    let descuento = parseFloat($('#descontar_cantidad').val()) || 0;
    let total_pagar = parseFloat($('#monto_pagar').val()) || 0;
    let monto_recibido = parseFloat($('#monto_recibido').val()) || 0;
    let monto_vuelto = parseFloat($('#monto_vuelto').val()) || 0; // CAPTURAMOS EL VUELTO
    let metodo = $('#pago_metodo').find(':selected').data('tipo');

    // 2. Si es pago digital, el recibido es igual al total a pagar
    if (metodo === 'transferencia' || metodo === 'yape' || metodo === 'plin') {
        monto_recibido = total_pagar;
        $('#label_monto_recibido').text('Monto Recibido (' + (metodo.charAt(0).toUpperCase() + metodo.slice(1)) + ')');
    } else {
        $('#label_monto_recibido').text('Monto Recibido (Efectivo)');
    }

    // 3. Pintamos la Cuota Original
    $('#resumen_cuota').text('S/ ' + cuota_original.toFixed(2));

    // 4. Pintamos el Descuento (Si existe)
    if (descuento > 0) {
        $('#li_resumen_descuento').attr('style', 'display: flex !important;');
        $('#resumen_descuento').text('- S/ ' + descuento.toFixed(2));
    } else {
        $('#li_resumen_descuento').attr('style', 'display: none !important;');
    }

    // 5. Pintamos el Total Final y lo Recibido
    $('#resumen_total').text('S/ ' + total_pagar.toFixed(2));
    $('#resumen_recibido').text('S/ ' + monto_recibido.toFixed(2));

    // ==========================================
    // 6. NUEVO: Lógica visual para el Vuelto
    // ==========================================
    if (metodo === 'efectivo' && monto_vuelto > 0) {
        // Solo lo mostramos si pagaron en efectivo y de verdad sobra plata
        $('#li_resumen_vuelto').attr('style', 'display: flex !important; background-color: #fff3cd;'); // Fondo un poco amarillo para resaltar
        $('#resumen_vuelto').text('S/ ' + monto_vuelto.toFixed(2));
    } else {
        // Lo ocultamos si pagaron exacto o por medios digitales
        $('#li_resumen_vuelto').attr('style', 'display: none !important;');
    }
}
