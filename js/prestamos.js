/*function cambiar_proximo_cobro(tipo){
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
            fecha.setMonth(mesActual + 1);
            if (fecha.getDate() !== new Date(fecha.getFullYear(), mesActual + 1, 0).getDate()) {
                fecha.setDate(Math.min(fecha.getDate(), new Date(fecha.getFullYear(), fecha.getMonth() + 1, 0).getDate()));
            }
            num_cuotas = 1;
        }

        // Validar si cae en domingo y si "domingo" es 'no'
        if(domingo == 'no' && fecha.getDay() == 0){
            fecha.setDate(fecha.getDate() + 1); // Sumar un día si es domingo
        }

        fecha_nueva = fecha.toISOString().split('T')[0];

    } else {
        /!*$('#div_diario_domingos').show(200);
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
        }*!/
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
}*/
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
                            // location.reload();
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

    // AQUÍ EL CAMBIO: Lo recibimos directo y limpio
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

    // Añadimos validación por seguridad para la fecha de inicio
    valor = validar_campo_vacio('prestamo_fecha_inicio', prestamo_fecha_inicio, valor);

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
                prestamo_fecha_inicio: prestamo_fecha_inicio, // El dato ya va listo para la BD
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
                        console.log(r.result.id_p)
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
                                // Agrega el flag y reenvía
                                $('#forzar_garante').val('1');
                                guardar_prestamo(); // <-- la función que hace el AJAX
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
        // beforeSend: function () {
        //     cambiar_estado_boton(boton, 'Guardando...', true);
        // },
        success:function (r) {
            // cambiar_estado_boton(boton, "<i class=\"fa fa-save \"></i> Guardar", false);
            switch (r.result.code) {
                case 1:
                    respuesta('¡Préstamo Transferido a Antiguo!', 'success');
                    setTimeout(function () {
                        location.reload();
                        // window.open(urlweb + 'Prestamos/generar_documento/' + r.result.id_p, '_self');
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
    var pago_recepcion = $('#pago_recepcion').val();
    var pago_metodo = $('#pago_metodo').val();
    var prestamo_prox_cobro = $('#prestamo_prox_cobro').val();
    var pago_recepcion_yp = $('#pago_recepcion_yp').val();

    // CAPTURAMOS EL DESCUENTO DINÁMICAMENTE
    var descuento = $('#descontar_cantidad').val();
    var tope_cuota = $('#monto_cuota_actual').val(); // Leemos el input oculto

    if(descuento === "" || isNaN(descuento)) {
        descuento = 0;
    }

    if(parseFloat(descuento) > parseFloat(tope_cuota)) {
        respuesta('Error: El descuento supera el monto de la cuota actual.', 'error');
        return false; // Cortamos la función para que no guarde
    }

    valor = validar_campo_vacio('id_prestamo', id_prestamo, valor);
    valor = validar_campo_vacio('pago_recepcion', pago_recepcion, valor);
    valor = validar_campo_vacio('pago_metodo', pago_metodo, valor);

    if(valor){
        $.ajax({
            type: "POST",
            url: urlweb + "api/cobros/guardar_pago",
            data: {
                id_prestamo : id_prestamo,
                id_pago: id_pago,
                pago_recepcion: pago_recepcion,
                pago_metodo: pago_metodo,
                prestamo_prox_cobro: prestamo_prox_cobro,
                pago_recepcion_yp: pago_recepcion_yp,
                descuento: descuento // ENVIAMOS EL DESCUENTO AL PHP
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
                        cambiar_estado_boton(boton, 'Confirmar Pago', false); // Reactiva el botón
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
            // beforeSend: function () {
            //     cambiar_estado_boton(boton, 'Guardando...', true);
            // },
            success:function (r) {
                // cambiar_estado_boton(boton, "<i class=\"fa fa-save \"></i> Guardar", false);
                switch (r.result.code) {
                    case 1:
                        /*respuesta('¡Pago Guardado!', 'success');
                        setTimeout(function () {
                            // location.reload();
                            window.open(urlweb + 'Cobros/generar_documento/' + r.result.id_pago, '_blank');
                        }, 1000);
                        break;*/

                        respuesta('¡Prestamo actualizado!', 'success');
                        setTimeout(function () {
                            // Abre nueva pestaña con el comprobante
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



$(function () {
    // Select2 configurado al 100% de ancho
    $("#prestamo_garante").select2({
        width: '100%',
        placeholder: "Seleccionar Garante",
        allowClear: true
    });

    $('#formBuscar').on('submit', function (e) {
        let dni = $('#dni_post').val()?.trim();
        if (!dni) {
            e.preventDefault();
            respuesta('Ingrese DNI para buscar', 'error');
            return;
        }
        respuesta('Buscando cliente...', 'success');
    });

    // Inicializar interfaz al cargar
    ajustar_interfaz_tipo_pago();
    calcular_cuota();
});

// Función que ajusta textos y muestra/oculta campos según el tipo de pago
// Función para obtener los días del mes de la fecha seleccionada
function obtenerDiasDelMes() {
    let fechaSeleccionada = $('#fecha_prestamo2').val();
    let fecha = fechaSeleccionada ? new Date(fechaSeleccionada + 'T00:00:00') : new Date();
    // El día 0 del mes siguiente es el último día del mes actual
    return new Date(fecha.getFullYear(), fecha.getMonth() + 1, 0).getDate();
}

// Función principal que controla todo al cambiar de tipo de pago
// Función principal que controla todo al cambiar de tipo de pago
// Función principal que controla todo al cambiar de tipo de pago
// Función principal que controla todo al cambiar de tipo de pago
// Le añadimos "conservar_cuotas = false" por defecto
function ajustar_interfaz_tipo_pago(conservar_cuotas = false) {
    let tipoPago = $('input[name="tipo_pago2"]:checked').val().toLowerCase();

    // 1. SIEMPRE mostramos/ocultamos la interfaz visual correcta
    $('#div_cuota_diaria').show();
    if (tipoPago === 'diario') {
        $('#label_cuotas_dias').html('Días a Pagar <span class="text-danger">*</span>');
        $('#div_diario_domingos').show();
    } else {
        $('#label_cuotas_dias').html('Número de Cuotas <span class="text-danger">*</span>');
        $('#div_diario_domingos').hide();
    }

    // 2. SOLO reseteamos el número si NO nos piden conservarlo (ej: al cambiar de Diario a Mensual)
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

        // Inyectamos el nuevo número
        $('#prestamo_num_cuotas').val(nuevasCuotas);
    }

    // 3. Recalculamos fechas de vencimiento y dinero con el número que esté en la caja
    cambiar_proximo_cobro();
    calcular_cuota();
}

// Función matemática de la cuota (Apta para Diario, Semanal y Mensual)
function calcular_cuota() {
    // ¡NUEVO! Eliminamos el freno que evitaba que calcule en semanal/mensual

    let montoStr = $('#monto_prestamo').val();
    let interesStr = $('#interes').val();
    let cuotasStr = $('#prestamo_num_cuotas').val();

    let monto = parseFloat(montoStr) || 0;
    let porcentajeInteres = parseFloat(interesStr) || 0;
    let cuotas = parseInt(cuotasStr) || 1;

    let cuota_estimada = 0;

    if (monto > 0 && cuotas > 0) {
        let monto_interes = monto * (porcentajeInteres / 100);
        let total_prestamo = monto + monto_interes;
        cuota_estimada = total_prestamo / cuotas;
    }

    $('#cuota_diaria_visual').val(cuota_estimada.toFixed(2));
    $('#cuota_calculada_hidden').val(cuota_estimada);
}
// Cálculo matemático: [Préstamo + (Préstamo * Porcentaje / 100)] / Cuotas(Días)
function calcular_cuota() {
    // 🚨 Eliminamos el condicional que lo frenaba
    // if (!$('#diario').is(':checked')) return;

    let montoStr = $('#monto_prestamo').val();
    let interesStr = $('#interes').val();
    let cuotasStr = $('#prestamo_num_cuotas').val();

    let monto = parseFloat(montoStr) || 0;
    let porcentajeInteres = parseFloat(interesStr) || 0;
    let cuotas = parseInt(cuotasStr) || 1;

    let cuota_estimada = 0; // Cambié el nombre de la variable para que tenga más sentido

    if (monto > 0 && cuotas > 0) {
        let monto_interes = monto * (porcentajeInteres / 100);
        let total_prestamo = monto + monto_interes;
        cuota_estimada = total_prestamo / cuotas;
    }

    // Actualizamos la vista sin importar el tipo de pago
    $('#cuota_diaria_visual').val(cuota_estimada.toFixed(2));
    $('#cuota_calculada_hidden').val(cuota_estimada);
}
function cambiar_proximo_cobro() {
    // 1. Obtener la fecha de préstamo seleccionada (Fecha de desembolso)
    let fechaBaseStr = $('#fecha_prestamo2').val();
    if (!fechaBaseStr) return;

    // Creamos el objeto Date con T00:00:00 para evitar errores de zona horaria
    let fecha = new Date(fechaBaseStr + 'T00:00:00');

    // --- REQUERIMIENTO: EL PRÉSTAMO EMPIEZA AL DÍA SIGUIENTE ---
    // Sumamos el día de gracia inicial
    fecha.setDate(fecha.getDate() + 1);
    // ---------------------------------------------------------

    // 2. Obtener parámetros (Tipo de pago y Domingos)
    let tipoPago = $('input[name="tipo_pago2"]:checked').val().toLowerCase();
    let incluirDomingos = $('#select_domingos').val();

    // 3. Sumar el intervalo correspondiente según el tipo seleccionado
    if (tipoPago === 'diario') {
        // Mañana empieza, mañana mismo es el primer cobro
        fecha.setDate(fecha.getDate() + 1);
    } else if (tipoPago === 'semanal') {
        // Mañana empieza, en 7 días es el primer cobro
        fecha.setDate(fecha.getDate() + 7);
    } else if (tipoPago === 'mensual') {
        // Mañana empieza, en 1 mes es el primer cobro
        fecha.setMonth(fecha.getMonth() + 1);
    }

    // 4. Lógica de saltar Domingo (Si cae domingo y no se cobra, pasar a lunes)
    if (incluirDomingos === 'no' && fecha.getDay() === 0) {
        fecha.setDate(fecha.getDate() + 1);
    }

    // 5. Formatear la fecha resultante a YYYY-MM-DD
    let anio = fecha.getFullYear();
    let mes = String(fecha.getMonth() + 1).padStart(2, '0');
    let dia = String(fecha.getDate()).padStart(2, '0');
    let fechaFinal = `${anio}-${mes}-${dia}`;

    // 6. Actualizar el campo de próximo cobro en la vista
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

            // Petición AJAX al controlador
            $.ajax({
                type: "POST",
                url: urlweb + "api/cobros/anular",
                data: { id_prestamo: id_prestamo },
                dataType: 'json',
                success: function (res) {
                    if (res.codigo === 1) {
                        respuesta('Crédito anulado y dinero devuelto a caja', 'success');
                        setTimeout(function () {
                            location.href = urlweb + 'Prestamos/inicio'; // <-- Redirige a Prestamos/inicio
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

$(document).ready(function() {
    // Escucha cada vez que el usuario escribe en el input de descuento
    $('#descontar_cantidad').on('input', function() {
        let valor_ingresado = parseFloat($(this).val());
        let monto_maximo = parseFloat($('#monto_cuota_actual').val());

        // Si el valor ingresado es mayor a la cuota real
        if (valor_ingresado > monto_maximo) {
            // Le advertimos al usuario (asumiendo que usas SweetAlert en tu función respuesta)
            respuesta('El descuento no puede ser mayor a la cuota (S/ ' + monto_maximo.toFixed(2) + ')', 'warning');

            // Forzamos el input a que su valor sea el tope máximo permitido
            $(this).val(monto_maximo.toFixed(2));
        }
    });
});


function actualizar_mensaje_inicio() {
    const fechaEmision = $('#fecha_prestamo2').val();
    if (!fechaEmision) return;

    // Desglosamos la fecha para evitar problemas de zona horaria
    const partes = fechaEmision.split('-');
    const anio = parseInt(partes[0], 10);
    const mes  = parseInt(partes[1], 10) - 1;
    const dia  = parseInt(partes[2], 10);

    // Creamos la fecha y sumamos 1 día
    const fecha = new Date(anio, mes, dia);
    fecha.setDate(fecha.getDate() + 1);

    const d = String(fecha.getDate()).padStart(2, '0');
    const m = String(fecha.getMonth() + 1).padStart(2, '0');
    const y = fecha.getFullYear();

    // 1. Pintamos el texto para que el usuario lo vea bonito
    $('#texto_fecha_inicio').text(`${d}-${m}-${y}`);

    // 2. Guardamos el valor en el input oculto para mandarlo por AJAX
    $('#prestamo_fecha_inicio').val(`${y}-${m}-${d}`);
}