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
    var forzar = $('#forzar_garante').val();
    // var prestamo_monto_interes = $('#prestamo_monto_interes').val();

    valor = validar_campo_vacio('id_cliente', id_cliente, valor);
    valor = validar_campo_vacio('monto_prestamo', prestamo_monto, valor);
    valor = validar_campo_vacio('interes', prestamo_interes, valor);
  //  valor = validar_campo_vacio('prestamo_garante', prestamo_garante, valor);
    valor = validar_campo_vacio('prestamo_num_cuotas', prestamo_num_cuotas, valor);
    valor = validar_campo_vacio('fecha_prestamo2', prestamo_fecha, valor);
    valor = validar_campo_vacio('fecha_prox_cobro2', prestamo_prox_cobro, valor);
    valor = validar_campo_vacio('prestamo_garantia', prestamo_garantia, valor);
    // valor = validar_campo_vacio('prestamo_garante', prestamo_garante, valor);
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
                select_domingos: select_domingos,
                forzar_garante: forzar
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
                            window.open(urlweb + 'Prestamos/generar_documento/' + r.result.id_p, '_blank');
                            window.location.href = urlweb + 'Clientes/inicio';
                            // location.reload();
                        }, 1000);
                        /*setTimeout(function () {
                            // Abre nueva pestaña con el comprobante
                            window.open(urlweb + 'Cobros/generar_documento/' + r.result.id_pago, '_blank');

                            // Redirige la ventana actual
                            window.location.href = urlweb + 'Clientes/inicio';
                        }, 1000);*/
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
    var boton = "btn-guardar-pago"; // <-- asegúrate que el id de tu botón sea este
    var id_prestamo = $('#id_prestamo').val();
    var id_pago = $('#id_pago').val();
    var pago_recepcion = $('#pago_recepcion').val();
    var pago_metodo = $('#pago_metodo').val();
    var prestamo_prox_cobro = $('#prestamo_prox_cobro').val();
    var pago_recepcion_yp = $('#pago_recepcion_yp').val();

    valor = validar_campo_vacio('id_prestamo', id_prestamo, valor);
    valor = validar_campo_vacio('pago_recepcion', pago_recepcion, valor);
    valor = validar_campo_vacio('pago_metodo', pago_metodo, valor);
    valor = validar_campo_vacio('prestamo_prox_cobro', prestamo_prox_cobro, valor);

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
                pago_recepcion_yp: pago_recepcion_yp
            },
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'Guardando...', true); // <-- bloquea el botón
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
function ajustar_interfaz_tipo_pago() {
    let tipoPago = $('input[name="tipo_pago2"]:checked').val().toLowerCase();
    let nuevasCuotas = $('#prestamo_num_cuotas').val(); // Empezamos con el valor actual

    if (tipoPago === 'diario') {
        $('#label_cuotas_dias').html('Días a Pagar <span class="text-danger">*</span>');
        $('#div_cuota_diaria').show();
        $('#div_diario_domingos').show();

        let incluirDom = $('#select_domingos').val();
        if (incluirDom === 'si') {
            nuevasCuotas = obtenerDiasDelMes(); // Asigna 28, 29, 30 o 31
        } else {
            nuevasCuotas = 26; // Asigna 26 fijo si no hay domingos
        }
    } else {
        $('#label_cuotas_dias').html('Número de Cuotas <span class="text-danger">*</span>');
        $('#div_cuota_diaria').hide();
        $('#div_diario_domingos').hide();

        if (tipoPago === 'semanal') nuevasCuotas = 4;
        if (tipoPago === 'mensual') nuevasCuotas = 1;
    }

    // 1. Asignamos el nuevo número de cuotas/días al input
    $('#prestamo_num_cuotas').val(nuevasCuotas);

    // 2. Calculamos las fechas en base a lo nuevo
    cambiar_proximo_cobro();

    // 3. Calculamos el dinero en base a los nuevos días
    calcular_cuota();
}

// Cálculo matemático: [Préstamo + (Préstamo * Porcentaje / 100)] / Cuotas(Días)
function calcular_cuota() {
    if (!$('#diario').is(':checked')) return;

    let montoStr = $('#monto_prestamo').val();
    let interesStr = $('#interes').val();
    let cuotasStr = $('#prestamo_num_cuotas').val();

    let monto = parseFloat(montoStr) || 0;
    let porcentajeInteres = parseFloat(interesStr) || 0;
    let cuotas = parseInt(cuotasStr) || 1;

    let cuota_diaria = 0;

    if (monto > 0 && cuotas > 0) {
        let monto_interes = monto * (porcentajeInteres / 100);
        let total_prestamo = monto + monto_interes;
        cuota_diaria = total_prestamo / cuotas;
    }

    $('#cuota_diaria_visual').val(cuota_diaria.toFixed(2));
    $('#cuota_calculada_hidden').val(cuota_diaria);
}
function cambiar_proximo_cobro() {
    // 1. Obtener la fecha de préstamo seleccionada
    let fechaBaseStr = $('#fecha_prestamo2').val();
    if (!fechaBaseStr) return;

    // Creamos el objeto Date (añadimos T00:00:00 para evitar desfases por zona horaria)
    let fecha = new Date(fechaBaseStr + 'T00:00:00');

    // 2. Obtener parámetros de tipo de pago e incluir domingos
    let tipoPago = $('input[name="tipo_pago2"]:checked').val(); // 'Diario', 'Semanal' o 'Mensual'
    let incluirDomingos = $('#select_domingos').val(); // 'si' o 'no'

    // 3. Sumar el intervalo correspondiente
    if (tipoPago === 'Diario' || tipoPago === 'diario') {
        fecha.setDate(fecha.getDate() + 1);
    } else if (tipoPago === 'Semanal' || tipoPago === 'semanal') {
        fecha.setDate(fecha.getDate() + 7);
    } else if (tipoPago === 'Mensual' || tipoPago === 'mensual') {
        fecha.setMonth(fecha.getMonth() + 1);
    }

    // 4. Lógica de saltar Domingo:
    // .getDay() devuelve 0 para Domingo, 1 para Lunes, etc.
    if (incluirDomingos === 'no' && fecha.getDay() === 0) {
        fecha.setDate(fecha.getDate() + 1); // Si es domingo y no se incluye, pasar a lunes
    }

    // 5. Formatear la fecha resultante a YYYY-MM-DD para el input
    let anio = fecha.getFullYear();
    let mes = String(fecha.getMonth() + 1).padStart(2, '0');
    let dia = String(fecha.getDate()).padStart(2, '0');
    let fechaFinal = `${anio}-${mes}-${dia}`;

    // 6. Actualizar el campo de próximo cobro
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