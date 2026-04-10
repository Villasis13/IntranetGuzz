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
                        <div class="d-flex align-items-center justify-content-between w-100">
                            <label class="fw-bold me-3 mb-0">Ingrese Cantidad a Descontar:</label>
                            <input class="form-control w-25 me-3" type="text" onkeyup="validar_numeros_decimales_dos(this.id)" id="descontar_cantidad" name="descontar_cantidad">
                            <a onclick="preguntar('¿Está seguro que desea aplicar este descuento?','guardar_aplicar_descuento','SI','NO',<?= $id_prestamo ?>)" style="cursor: pointer;" class="btn btn-sm btn-primary text-white">Aplicar Descuento</a>
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
                        <?= date('d/m/Y', strtotime($prestamos_data->prestamo_fecha)) ?>
                    </div>
                </div>
            </div>

            <?php if (!empty($cuota_a_pagar)) { ?>
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm h-100" style="border-left: 4px solid #4e73df;">
                            <div class="card-body">
                                <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                    Cuota a Pagar
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="h4 mb-0 fw-bold text-gray-800">
                                        S/ <?= number_format($cuota_a_pagar->pago_diario_monto, 2) ?>
                                    </div>
                                    <div class="text-sm text-danger fw-bold">
                                        <i class="fa fa-calendar-times me-1"></i> Vence: <?= date('d/m/Y', strtotime($cuota_a_pagar->pago_diario_fecha)) ?>
                                    </div>
                                </div>
                                <input type="hidden" id="id_pago" name="id_pago" value="<?= $cuota_a_pagar->id_pago_diario ?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm h-100" style="border-left: 4px solid #36b9cc;">
                            <div class="card-body">
                                <div class="text-xs fw-bold text-info text-uppercase mb-1">
                                    Próximo Cobro (Automático)
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

                <div class="row g-3 mb-4 align-items-end">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input id="pago_recepcion" name="pago_recepcion" type="text" class="form-control" placeholder=" ">
                            <label>Recepción</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select id="pago_metodo" name="pago_metodo" class="form-select" placeholder=" ">
                                <option value="">Seleccione</option>
                                <option value="transferencia">Transferencia</option>
                                <option value="efectivo">Efectivo</option>
                                <option value="plin">Plin</option>
                                <option value="yape">Yape</option>
                            </select>
                            <label>Método de Pago</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input id="pago_recepcion_yp" name="pago_recepcion_yp" type="text" class="form-control" placeholder=" ">
                            <label>Titular del Yape/Plin</label>
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
                                    <label class="fw-bold mb-0">Garantes:</label>
                                    <span class="text-muted ms-2"><?= $garante->cliente_nombre .' ' . $garante->cliente_apellido_paterno ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button id="btn-guardar-pago" onclick="guardar_pago_prestamo()" type="button" class="btn btn-lg btn-primary px-5">
                        <i class="fa fa-check-circle me-2"></i>Confirmar Pago
                    </button>
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