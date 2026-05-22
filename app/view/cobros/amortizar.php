<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-hand-holding-usd me-2"></i>Amortización de Préstamo
        </h1>
        <a href="<?= _SERVER_ ?>cobros/pagar/<?= $id_prestamo ?>" class="btn btn-secondary">
            <i class="fa fa-arrow-left me-2"></i>Volver al Pago
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">

            <!-- Info del cliente y préstamo -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="info-group mb-2">
                        <label class="fw-bold">Cliente:</label>
                        <?= htmlspecialchars($cliente_data->cliente_nombre . ' ' . $cliente_data->cliente_apellido_paterno . ' ' . $cliente_data->cliente_apellido_materno) ?>
                    </div>
                    <div class="info-group mb-2">
                        <label class="fw-bold">DNI:</label>
                        <?= htmlspecialchars($cliente_data->cliente_dni) ?>
                    </div>
                    <div class="info-group mb-2">
                        <label class="fw-bold">Tipo de Pago:</label>
                        <span class="badge bg-info text-dark"><?= strtoupper($prestamos_data->prestamo_tipo_pago) ?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-group mb-2">
                        <label class="fw-bold">Préstamo N°:</label>
                        <?= str_pad($id_prestamo, 6, '0', STR_PAD_LEFT) ?>
                    </div>
                    <div class="info-group mb-2">
                        <label class="fw-bold">Interés:</label>
                        <?= $prestamos_data->prestamo_interes ?> %
                    </div>
                    <div class="info-group mb-2">
                        <label class="fw-bold">Vencimiento próxima cuota:</label>
                        <span class="text-danger fw-bold">
                            <?= date('d/m/Y', strtotime($cuota_a_pagar->pago_diario_fecha)) ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Resumen de deuda -->
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-md-4">
                    <div class="card shadow-sm h-100" style="border-left: 4px solid #4e73df;">
                        <div class="card-body py-3">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">Capital Pendiente</div>
                            <div class="h5 fw-bold mb-0">S/ <?= number_format($capital_pendiente, 2) ?></div>
                            <div class="text-xs text-muted mt-1">Límite máximo a amortizar</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="card shadow-sm h-100" style="border-left: 4px solid #e74a3b;">
                        <div class="card-body py-3">
                            <div class="text-xs fw-bold text-danger text-uppercase mb-1">Saldo Total Pendiente</div>
                            <div class="h5 fw-bold mb-0">S/ <?= number_format($saldo_total_pendiente, 2) ?></div>
                            <div class="text-xs text-muted mt-1">Capital + intereses</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4">
                    <div class="card shadow-sm h-100" style="border-left: 4px solid #1cc88a;">
                        <div class="card-body py-3">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">Saldo Resultante</div>
                            <div class="h5 fw-bold mb-0" id="saldo_resultante_preview">S/ <?= number_format($saldo_total_pendiente, 2) ?></div>
                            <div class="text-xs text-muted mt-1">Después de amortizar</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario -->
            <input type="hidden" id="id_prestamo"     name="id_prestamo"     value="<?= $id_prestamo ?>">
            <input type="hidden" id="capital_max"     value="<?= $capital_pendiente ?>">
            <input type="hidden" id="saldo_total_max" value="<?= $saldo_total_pendiente ?>">

            <div class="row g-3 mb-4">

                <!-- Monto a amortizar -->
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="number" step="0.01" min="0.01"
                               id="monto_pagar" name="monto_pagar"
                               class="form-control fw-bold"
                               onkeyup="validar_amortizacion(); calcular_vuelto();"
                               oninput="validar_amortizacion(); calcular_vuelto();"
                               placeholder=" ">
                        <label>Monto a Amortizar (S/) <span class="text-danger">*</span></label>
                    </div>
                    <div id="error_amortizacion" class="text-danger small mt-1" style="display:none;"></div>
                    <div id="ok_amortizacion"    class="text-success small mt-1" style="display:none;">
                        <i class="fa fa-check-circle me-1"></i> Monto válido
                    </div>
                </div>

                <!-- Método de pago -->
                <div class="col-md-6">
                    <div class="form-floating">
                        <select id="pago_metodo" name="pago_metodo" class="form-select"
                                onchange="cambiar_metodo_pago()" required>
                            <option value="">Seleccione</option>
                            <?php foreach ($metodos_pago as $metodo): ?>
                                <option value="<?= $metodo->id_metodo_pago ?>"
                                        data-tipo="<?= strtolower($metodo->metodo_pago_nombre) ?>">
                                    <?= $metodo->metodo_pago_nombre ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <label>Método de Pago <span class="text-danger">*</span></label>
                    </div>
                </div>

                <!-- Monto recibido y diferencia -->
                <div class="col-md-12" id="grupo_efectivo">
                    <div class="row g-3 border p-3 rounded bg-light mx-0">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" step="0.01" id="monto_recibido" name="monto_recibido"
                                       class="form-control" onkeyup="calcular_vuelto()" placeholder=" ">
                                <label>Monto Recibido (S/)</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" id="monto_vuelto" name="monto_vuelto"
                                       class="form-control font-weight-bold" readonly value="0.00" placeholder=" ">
                                <label id="label_vuelto">Diferencia (S/)</label>
                            </div>
                        </div>
                        <div class="col-md-12" id="grupo_no_vuelto" style="display:none;">
                            <div class="form-check form-switch pt-1">
                                <input class="form-check-input" type="checkbox" id="no_vuelto"
                                       name="no_vuelto" onchange="calcular_vuelto()">
                                <label class="form-check-label text-warning fw-semibold" for="no_vuelto">
                                    <i class="fa fa-ban me-1"></i> No vuelto
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transferencia -->
                <div class="col-md-12" id="grupo_operacion_titular" style="display:none;">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" id="num_operacion" name="num_operacion"
                                       class="form-control" placeholder=" ">
                                <label>Número de Operación</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" id="nombre_titular" name="nombre_titular"
                                       class="form-control" placeholder=" ">
                                <label>Nombre del Titular</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12" id="grupo_transferencia" style="display:none;">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <select id="banco_entidad" name="banco_entidad" class="form-select">
                                    <option value="">Seleccione Banco...</option>
                                    <?php foreach ($bancos as $banco): ?>
                                        <option value="<?= $banco->id_banco ?>">
                                            <?= htmlspecialchars($banco->banco_nombre) ?> (<?= htmlspecialchars($banco->banco_abreviado) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <label>Banco o Entidad</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="date" id="fecha_transferencia" name="fecha_transferencia"
                                       class="form-control" value="<?= date('Y-m-d') ?>">
                                <label>Fecha de Transferencia</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Observación -->
                <div class="col-md-12">
                    <div class="form-floating">
                        <textarea id="pago_observacion" name="pago_observacion"
                                  class="form-control" placeholder=" " style="height:80px;"></textarea>
                        <label>Observación (opcional)</label>
                    </div>
                </div>

            </div>

            <!-- Resumen -->
            <div class="row mb-4">
                <div class="col-md-8 offset-md-2">
                    <div class="card shadow-sm border-bottom-success">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 font-weight-bold text-success">
                                <i class="fa fa-receipt me-2"></i>Resumen de la Operación
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Capital Pendiente</span>
                                    <span class="fw-bold text-dark">S/ <?= number_format($capital_pendiente, 2) ?></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                                    <strong class="text-dark">Monto a Amortizar</strong>
                                    <strong class="text-primary h5 mb-0" id="resumen_total">S/ 0.00</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="text-muted" id="label_monto_recibido">Monto Recibido</span>
                                    <span class="fw-bold text-success" id="resumen_recibido">S/ 0.00</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center"
                                    id="li_resumen_vuelto" style="display:none !important;">
                                    <span id="label_resumen_diferencia" class="text-muted">
                                        <i class="fa fa-reply me-1"></i> Vuelto a Entregar
                                    </span>
                                    <span class="fw-bold h6 mb-0" id="resumen_vuelto">S/ 0.00</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="text-center mt-4 d-flex justify-content-center gap-3 flex-wrap">
                <button id="btn-guardar-amortizacion"
                        onclick="confirmar_amortizacion()"
                        type="button"
                        class="btn btn-lg btn-success px-5"
                        disabled>
                    <i class="fa fa-check-circle me-2"></i>Confirmar Amortización
                </button>
                <a href="<?= _SERVER_ ?>cobros/pagar/<?= $id_prestamo ?>"
                   class="btn btn-lg btn-outline-secondary px-5">
                    <i class="fa fa-times me-2"></i>Cancelar
                </a>
            </div>

        </div>
    </div>
</div>

<script src="<?= _SERVER_ . _JS_ ?>domain.js"></script>
<script src="<?= _SERVER_ . _JS_ ?>prestamos.js"></script>
<script>
const CAPITAL_MAX = <?= $capital_pendiente ?>;
const SALDO_MAX   = <?= $saldo_total_pendiente ?>;
const TASA        = <?= $tasa ?>;

function validar_amortizacion() {
    const monto = parseFloat($('#monto_pagar').val()) || 0;
    const $err  = $('#error_amortizacion');
    const $ok   = $('#ok_amortizacion');
    const $btn  = $('#btn-guardar-amortizacion');

    let error = '';

    if (monto <= 0) {
        error = 'El monto debe ser mayor a S/ 0.00.';
    } else if (monto > CAPITAL_MAX) {
        error = 'Excede el capital pendiente (máx. S/ ' + CAPITAL_MAX.toFixed(2) + ').';
    } else if (monto > SALDO_MAX) {
        error = 'Excede el saldo total pendiente (máx. S/ ' + SALDO_MAX.toFixed(2) + ').';
    }

    if (error) {
        $err.text(error).show();
        $ok.hide();
        $btn.prop('disabled', true);
    } else {
        $err.hide();
        $ok.show();
        $btn.prop('disabled', false);
    }

    // Actualizar preview del saldo resultante (amortización sobre capital)
    const nuevo_capital = Math.max(0, CAPITAL_MAX - monto);
    const nuevo_saldo   = TASA > 0 ? nuevo_capital * (1 + TASA / 100) : nuevo_capital;
    $('#saldo_resultante_preview').text('S/ ' + nuevo_saldo.toFixed(2));
}

function confirmar_amortizacion() {
    const monto = parseFloat($('#monto_pagar').val()) || 0;

    // Re-validar antes de enviar
    if (monto <= 0 || monto > CAPITAL_MAX || monto > SALDO_MAX) {
        validar_amortizacion();
        return;
    }

    const metodo = $('#pago_metodo').val();
    if (!metodo) {
        respuesta('Debe seleccionar un método de pago.', 'warning');
        return;
    }

    const boton = 'btn-guardar-amortizacion';
    cambiar_estado_boton(boton, 'Guardando...', true);

    $.ajax({
        type: 'POST',
        url: urlweb + 'api/cobros/guardar_amortizacion',
        data: {
            id_prestamo:        $('#id_prestamo').val(),
            monto_amortizar:    monto,
            pago_metodo:        metodo,
            monto_recibido:     $('#monto_recibido').val(),
            monto_vuelto:       $('#monto_vuelto').val(),
            num_operacion:      $('#num_operacion').val(),
            nombre_titular:     $('#nombre_titular').val(),
            banco_entidad:      $('#banco_entidad').val(),
            fecha_transferencia:$('#fecha_transferencia').val(),
            pago_observacion:   $('#pago_observacion').val(),
        },
        dataType: 'json',
        success: function(r) {
            switch (r.result.code) {
                case 1:
                    respuesta('¡Amortización registrada!', 'success');
                    setTimeout(() => {
                        window.location.href = urlweb + 'cobros/pagar/' + $('#id_prestamo').val();
                    }, 1200);
                    break;
                case 3:
                case 4:
                case 5:
                    respuesta(r.result.message, 'error');
                    cambiar_estado_boton(boton, 'Confirmar Amortización', false);
                    break;
                default:
                    respuesta('Error al registrar la amortización.', 'error');
                    cambiar_estado_boton(boton, 'Confirmar Amortización', false);
            }
        },
        error: function() {
            respuesta('Error de conexión.', 'error');
            cambiar_estado_boton(boton, 'Confirmar Amortización', false);
        }
    });
}

// Inicializar el resumen al cargar la página
$(document).ready(function() {
    actualizar_resumen();
});
</script>
