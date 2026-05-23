<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-hand-holding-usd me-2"></i>Pago de Deuda
        </h1>
        <a href="<?= _SERVER_ ?>prestamos/prestamos" class="btn btn-success">
            <i class="fa fa-arrow-left me-2"></i>Volver
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">

            <div class="row mb-4">
                <div class="col-md-7">
                    <div class="info-group mb-3">
                        <label class="fw-bold">Nombre:</label>
                        <input type="hidden" id="id_prestamo" name="id_prestamo" value="<?= $id_prestamo ?>">
                        <?= $cliente_data->cliente_nombre . ' ' . $cliente_data->cliente_apellido_paterno . ' ' . $cliente_data->cliente_apellido_materno ?>
                    </div>
                    <div class="info-group mb-3">
                        <label class="fw-bold">DNI:</label>
                        <?= $cliente_data->cliente_dni ?>
                    </div>
                    <div class="info-group mb-3">
                        <label class="fw-bold">Monto Inicial:</label>
                        <span class="text-success">S/ <?= number_format($prestamos_data->prestamo_monto + ($prestamos_data->prestamo_monto * $prestamos_data->prestamo_interes / 100), 2) ?></span>
                    </div>
                    <div class="info-group mb-3">
                        <label class="fw-bold">Resta por Pagar:</label>
                        <span class="badge bg-danger fs-6">
                <?= !empty($prestamos_data->prestamo_saldo_pagar) ? "S/ " . number_format($prestamos_data->prestamo_saldo_pagar, 2) : "Sin saldo restante" ?>
            </span>
                        <input id="input_resta_por_pagar" name="input_resta_por_pagar" type="hidden" value="<?= $valor_resta_por_pagar ?>">
                    </div>

                    <div class="info-group mb-3">
                        <label class="fw-bold">Aplicar Descuento</label>
                        <input onchange="aplicar_descuento()" type="radio" name="desc" id="descSi"><label for="descSi" class="me-2 ms-1">Si</label>
                        <input onchange="aplicar_descuento()" type="radio" name="desc" checked id="descNo"><label for="descNo" class="ms-1">No</label>
                    </div>
                    <div style="display: none" id="div_descontar" class="info-group mb-3">
                        <div class="d-flex align-items-center w-100">
                            <label class="fw-bold me-3 mb-0" style="white-space: nowrap;">Cantidad a Descontar:</label>
                            <div class="input-group" style="max-width: 200px;">
                                <span class="input-group-text bg-light">S/</span>
                                <input class="form-control" type="text"
                                       onkeyup="validar_numeros_decimales_dos(this.id)"
                                       id="descontar_cantidad" name="descontar_cantidad" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="info-group mb-3">
                        <label class="fw-bold">Interés Aplicado:</label>
                        <?= $prestamos_data->prestamo_interes ?> %
                    </div>
                    <div class="info-group mb-3">
                        <label class="fw-bold">Tipo de Pago:</label>
                        <span class="badge bg-info text-dark"><?= strtoupper($prestamos_data->prestamo_tipo_pago) ?></span>
                    </div>

                    <div class="info-group mb-3">
                        <label class="fw-bold">Fecha de Emisión:</label>
                        <?= date('d/m/Y', strtotime($prestamos_data->prestamo_fecha_emision)) ?>
                    </div>

                    <div class="info-group mb-3">
                        <label class="fw-bold text-danger"><i class="fa fa-flag-checkered me-1"></i>Vencimiento del Préstamo:</label>
                        <?= !empty($fecha_fin_prestamo) ? date('d/m/Y', strtotime($fecha_fin_prestamo)) : '<span class="text-muted">No programado</span>' ?>
                    </div>
                </div>
            </div>


            <?php if (!empty($cuota_a_pagar)) { ?>

            <!-- Resumen de deuda actual -->
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow-sm h-100" style="border-left: 4px solid #4e73df;">
                        <div class="card-body py-3">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">Capital Pendiente</div>
                            <div class="h5 fw-bold text-gray-800 mb-0">
                                S/ <?= number_format($capital_pendiente, 2) ?>
                            </div>
                            <div class="text-xs text-muted mt-1">Sin intereses</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow-sm h-100" style="border-left: 4px solid #1cc88a;">
                        <div class="card-body py-3">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">Próxima Cuota</div>
                            <div class="h5 fw-bold text-gray-800 mb-0" id="texto_cuota_principal">
                                S/ <?= number_format($cuota_a_pagar->pago_diario_monto, 2) ?>
                            </div>
                            <div id="badge_descuento_visual" style="display:none;" class="mt-1">
                                <span class="text-danger small" style="text-decoration:line-through;">
                                    S/ <?= number_format($cuota_a_pagar->pago_diario_monto, 2) ?>
                                </span>
                                <span class="badge bg-warning text-dark ms-1" id="texto_descuento_aplicado"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow-sm h-100" style="border-left: 4px solid #f6c23e;">
                        <div class="card-body py-3">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">Fecha de Vencimiento</div>
                            <div class="h5 fw-bold text-gray-800 mb-0">
                                <?= date('d/m/Y', strtotime($cuota_a_pagar->pago_diario_fecha)) ?>
                            </div>
                            <div class="text-xs text-muted mt-1">Cuota actual</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow-sm h-100" style="border-left: 4px solid #e74a3b;">
                        <div class="card-body py-3">
                            <div class="text-xs fw-bold text-danger text-uppercase mb-1">Saldo Total Pendiente</div>
                            <div class="h5 fw-bold text-gray-800 mb-0">
                                S/ <?= number_format($saldo_total_pendiente, 2) ?>
                            </div>
                            <div class="text-xs text-muted mt-1">Capital + intereses</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="card shadow-sm h-100" style="border-left: 4px solid #4e73df;">
                        <div class="card-body">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                Cuota a Pagar
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="h4 mb-0 fw-bold text-gray-800">
                                        S/ <?= number_format($cuota_a_pagar->pago_diario_monto, 2) ?>
                                    </div>
                                </div>

                                <div class="text-sm text-danger fw-bold text-end">
                                    <i class="fa fa-calendar-times me-1"></i> Fecha de Vencimiento de la Cuota:<br>
                                    <?= date('d/m/Y', strtotime($cuota_a_pagar->pago_diario_fecha)) ?>
                                </div>
                            </div>
                            <input type="hidden" id="id_pago" name="id_pago" value="<?= $cuota_a_pagar->id_pago_diario ?>">
                            <input type="hidden" id="monto_cuota_actual" value="<?= $cuota_a_pagar->pago_diario_monto ?>">
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="card shadow-sm h-100" style="border-left: 4px solid #36b9cc;">
                        <div class="card-body">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">
                                Próximo Cobro
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800 mt-2">
                                <?php
                                if ($fecha_proximo_cobro == "Préstamo Finalizado") {
                                    echo '<span class="text-success"><i class="fa fa-check-circle me-1"></i> Última Cuota</span>';
                                } else {
                                    echo '<i class="fa fa-calendar-day me-1"></i> ' . date('d/m/Y', strtotime($fecha_proximo_cobro));
                                }
                                ?>
                            </div>
                            <input type="hidden" id="prestamo_prox_cobro" name="prestamo_prox_cobro" value="<?= $fecha_proximo_cobro ?>">
                        </div>
                    </div>
                </div>
            </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6 mb-3">
                        <div class="form-floating">
                            <select id="pago_metodo" name="pago_metodo" class="form-select" onchange="cambiar_metodo_pago()" required>
                                <option value="">Seleccione</option>
                                <?php
                                if (!empty($metodos_pago)) {
                                    foreach ($metodos_pago as $metodo):
                                        ?>
                                        <option value="<?= $metodo->id_metodo_pago ?>"
                                                data-tipo="<?= strtolower($metodo->metodo_pago_nombre) ?>">
                                            <?= $metodo->metodo_pago_nombre ?>
                                        </option>
                                    <?php
                                    endforeach;
                                }
                                ?>
                            </select>
                            <label>Método de Pago <span class="text-danger">*</span></label>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-floating">
                            <input type="number" step="0.01" id="monto_pagar" name="monto_pagar"
                                   class="form-control text-success font-weight-bold bg-white"
                                   value="<?= number_format($cuota_a_pagar->pago_diario_monto, 2, '.', '') ?>"
                                   readonly placeholder=" ">
                            <label>
                                Monto a Pagar (S/)
                                <span id="etiqueta_descuento" class="text-danger ms-1" style="display: none; font-size: 0.9em; font-weight: bold;">
                <i class="fa fa-tags me-1"></i>Con Descuento
            </span>
                            </label>
                        </div>
                    </div>

                    <div class="col-md-12" id="grupo_efectivo">
                        <div class="row g-3 border p-3 rounded bg-light mx-0">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" step="0.01" id="monto_recibido" name="monto_recibido" class="form-control" onkeyup="calcular_vuelto()" placeholder=" ">
                                    <label>Monto Recibido (S/)</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" id="monto_vuelto" class="form-control font-weight-bold" readonly value="0.00" placeholder=" ">
                                    <label id="label_vuelto">Diferencia / Vuelto (S/)</label>
                                </div>
                                <input type="hidden" id="monto_vuelto_db" name="monto_vuelto" value="0">
                            </div>
                            <div class="col-md-12" id="grupo_no_vuelto" style="display: none;">
                                <div class="form-check form-switch pt-1">
                                    <input class="form-check-input" type="checkbox" id="no_vuelto" name="no_vuelto" onchange="calcular_vuelto()">
                                    <label class="form-check-label text-warning fw-semibold" for="no_vuelto">
                                        <i class="fa fa-ban me-1"></i> No vuelto
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" id="grupo_operacion_titular" style="display: none;">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" id="num_operacion" name="num_operacion" class="form-control" placeholder=" ">
                                    <label>Número de Operación</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" id="nombre_titular" name="nombre_titular" class="form-control" placeholder=" ">
                                    <label>Nombre del Titular</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" id="grupo_transferencia" style="display: none;">
                        <div class="row"> <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <select id="banco_entidad" name="banco_entidad" class="form-select">
                                        <option value="">Seleccione Banco...</option>
                                        <?php
                                        if (!empty($bancos)) {
                                            foreach ($bancos as $banco):
                                                ?>
                                                <option value="<?= $banco->id_banco ?>"><?= $banco->banco_nombre ?> (<?= $banco->banco_abreviado ?>)</option>
                                            <?php
                                            endforeach;
                                        }
                                        ?>
                                    </select>
                                    <label>Banco o Entidad</label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="date" id="fecha_transferencia" name="fecha_transferencia" class="form-control" value="<?= date('Y-m-d') ?>">
                                    <label>Fecha de Transferencia</label>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" id="pago_observacion" name="pago_observacion" class="form-control" placeholder=" ">
                            <label>Observación / Detalles</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card border-primary">
                            <div class="card-header bg-gray text-white" style="padding: 13px;">
                                <i class="fa fa-shield-alt me-2"></i>Garantías
                            </div>
                            <div class="card-body" style="padding: 15px;">
                                <p class="mb-2">
                                    <strong>Garantía:</strong> <?= $prestamos_data->prestamo_garantia ?>
                                </p>
                                <div>
                                    <label class="fw-bold mb-0">Garante:</label>
                                    <span class="text-muted ms-2"><?= $garante->cliente_nombre .' ' . $garante->cliente_apellido_paterno. ' '. $garante->cliente_apellido_materno ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

        <div class="row mb-4">
            <div class="col-md-8 offset-md-2">
                <div class="card shadow-sm border-bottom-success">
                    <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-success">
                            <i class="fa fa-receipt me-2"></i>Resumen de la Operación
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted">Monto de Cuota Original</span>
                                <span class="font-weight-bold text-dark" id="resumen_cuota">S/ 0.00</span>
                            </li>

                            <li class="list-group-item justify-content-between align-items-center text-danger" id="li_resumen_descuento" style="display: none;">
                                <span><i class="fa fa-arrow-down me-1"></i> Descuento Aplicado</span>
                                <span class="font-weight-bold" id="resumen_descuento">- S/ 0.00</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                                <strong class="text-dark">Total a Pagar</strong>
                                <strong class="text-primary h5 mb-0" id="resumen_total">S/ 0.00</strong>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted" id="label_monto_recibido">Monto Recibido (Efectivo)</span>
                                <span class="font-weight-bold text-success" id="resumen_recibido">S/ 0.00</span>
                            </li>

                            <li class="list-group-item justify-content-between align-items-center" id="li_resumen_vuelto" style="display: none;">
                                <span id="label_resumen_diferencia" class="text-muted"><i class="fa fa-reply me-1"></i> Vuelto a Entregar</span>
                                <span class="font-weight-bold text-danger h6 mb-0" id="resumen_vuelto">S/ 0.00</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

                <div class="text-center mt-4 d-flex justify-content-center gap-3 flex-wrap">
                    <button id="btn-guardar-pago" onclick="guardar_pago_prestamo()" type="button" class="btn btn-lg btn-primary px-5">
                        <i class="fa fa-check-circle me-2"></i>Confirmar Pago
                    </button>
                    <?php if ($puede_amortizar): ?>
                    <a href="<?= _SERVER_ ?>cobros/amortizar/<?= $id_prestamo ?>"
                       class="btn btn-lg btn-success px-5">
                        <i class="fa fa-hand-holding-usd me-2"></i>Amortizar
                    </a>
                    <?php endif; ?>
                </div>

            <?php } else { ?>
                <div class="alert alert-success text-center mt-4 py-4 shadow-sm">
                    <i class="fa fa-check-circle fa-3x mb-3 text-success"></i>
                    <h4>¡Préstamo Finalizado!</h4>
                    <p class="mb-0">Este cliente ya no tiene cuotas pendientes de pago para este préstamo.</p>
                </div>
            <?php } ?>

        </div>
    </div>
</div>

<style>
    .info-group label {
        width: 160px;
        color: #4a5568;
    }
    .card-header {
        font-weight: 500;
    }
    .bg-gray {
        background-color: #5a5c69; /* Color oscuro similar a SB Admin */
    }
</style>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>prestamos.js"></script>