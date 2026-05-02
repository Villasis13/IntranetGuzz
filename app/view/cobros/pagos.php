<div class="container-fluid">
    <?php
    // ==========================================
    // 1. CÁLCULO PREVIO DE TOTALES
    // ==========================================

    // Sumar todos los pagos realizados
    $total_pagado = 0;
    if (!empty($pagos_p)) {
        foreach ($pagos_p as $c) {
            $total_pagado += floatval($c->pago_monto);
        }
    }

    // NUEVA LÓGICA: Sumar todos los descuentos desde la tabla pagos_diarios
    $total_descuento = 0;
    $hay_descuentos = !empty($descuentos_aplicados);

    if ($hay_descuentos) {
        foreach ($descuentos_aplicados as $desc) {
            $total_descuento += floatval($desc->pago_diario_descuento_monto);
        }
    }

    // Calcular el monto total del préstamo (Capital + Interés)
    $monto_capital = isset($listar_cliente_x_prestamo->prestamo_monto) ? floatval($listar_cliente_x_prestamo->prestamo_monto) : 0;
    $monto_interes = isset($listar_cliente_x_prestamo->prestamo_monto_interes) ? floatval($listar_cliente_x_prestamo->prestamo_monto_interes) : 0;
    $total_prestamo = $monto_capital + $monto_interes;

    // Calcular el saldo que aún debe el cliente
    $saldo_pendiente = $total_prestamo - $total_pagado - $total_descuento;

    // ==========================================
    // 2. LÓGICA DE ANULACIÓN (REGLA 48 HORAS Y ESTADO ACTIVO)
    // ==========================================
    $tiene_pagos = ($total_pagado > 0) ? true : false;

    // OJO: Asegúrate de que tu modelo traiga la fecha de registro del préstamo.
    $fecha_creacion = isset($listar_cliente_x_prestamo->prestamo_fecha_registro) ? $listar_cliente_x_prestamo->prestamo_fecha_registro : date('Y-m-d H:i:s');

    // Calculamos la diferencia en horas
    $diferencia_segundos = strtotime(date('Y-m-d H:i:s')) - strtotime($fecha_creacion);
    $horas_transcurridas = $diferencia_segundos / 3600;

    // Verificamos si el estado del préstamo es 1 (Activo)
    $estado_prestamo = isset($listar_cliente_x_prestamo->prestamo_estado) ? $listar_cliente_x_prestamo->prestamo_estado : 0;

    // Condición estricta: No tiene pagos, han pasado menos de 48 hrs y el estado es exactamente 1
    $se_puede_anular = (!$tiene_pagos && $horas_transcurridas <= 48 && $estado_prestamo == 1);
    ?>

    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <h3 class="m-0 font-weight-bold text-dark">
            Detalles de Préstamo
        </h3>
        <div>
            <?php if($se_puede_anular): ?>
                <button onclick="preguntar_anular_prestamo(<?= $id_prestamo ?>)" class="btn btn-danger shadow-sm mr-2">
                    <i class="fa fa-ban me-1"></i> Anular Crédito
                </button>
            <?php endif; ?>

            <a href="<?= _SERVER_ ?>prestamos/prestamos" class="btn btn-success shadow-sm">
                <i class="fa fa-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow border-left-info">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5 border-right">
                            <h5 class="font-weight-bold text-primary mb-3">
                                Cliente: <?= $listar_cliente_x_prestamo->cliente_nombre . ' ' . $listar_cliente_x_prestamo->cliente_apellido_paterno ?>
                            </h5>

                            <div class="mb-1 text-muted">
                                <span>Capital Prestado:</span>
                                <span class="float-right">S/. <?= number_format($monto_capital, 2) ?></span>
                            </div>
                            <div class="mb-2 text-muted border-bottom pb-2">
                                <span>Interés Generado:</span>
                                <span class="float-right">+ S/. <?= number_format($monto_interes, 2) ?></span>
                            </div>

                            <div class="mb-2 mt-2">
                                <span class="font-weight-bold text-dark">Deuda Total Inicial:</span>
                                <span class="float-right font-weight-bold">S/. <?= number_format($total_prestamo, 2) ?></span>
                            </div>
                            <div class="mb-2">
                                <span class="font-weight-bold text-success">Total Pagado:</span>
                                <span class="float-right text-success">- S/. <?= number_format($total_pagado, 2) ?></span>
                            </div>

                            <?php if($hay_descuentos): ?>
                                <div class="mb-2">
                                    <span class="font-weight-bold text-warning">Descuentos Aplicados:</span>
                                    <span class="float-right text-warning">- S/. <?= number_format($total_descuento, 2) ?></span>
                                </div>
                            <?php endif; ?>

                            <hr>

                            <div class="mb-0">
                                <span class="font-weight-bold text-danger">Saldo Pendiente:</span>
                                <span class="float-right font-weight-bold text-danger" style="font-size: 1.1em;">S/. <?= number_format($saldo_pendiente, 2) ?></span>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <h6 class="font-weight-bold text-secondary">Comentario / Detalles del Préstamo:</h6>
                            <div class="p-3 bg-light border rounded" style="min-height: 100px;">
                                <?php
                                echo isset($listar_cliente_x_prestamo->prestamo_comentario) && trim($listar_cliente_x_prestamo->prestamo_comentario) !== ''
                                        ? nl2br(htmlspecialchars($listar_cliente_x_prestamo->prestamo_comentario))
                                        : '<span class="text-muted">No hay comentarios registrados para este préstamo.</span>';
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header bg-gradient-primary py-3">
                    <h6 style="font-weight: bold" class="m-0 font-weight-bold text-primary">
                        Historial de Pagos Realizados
                    </h6>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="dataTablePagos">
                            <thead class="thead-light">
                            <tr class="text-center">
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Nro. Recibo</th>
                                <th>Monto Pagado</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(!empty($pagos_p)){
                                $a = 1;
                                foreach ($pagos_p as $c){
                                    ?>
                                    <tr class="text-center">
                                        <td><?= $a ?></td>
                                        <td><?= $c->pago_fecha ?></td>
                                        <td><?= $c->id_pago ?></td>
                                        <td class="font-weight-bold text-success">S/. <?= number_format($c->pago_monto, 2) ?></td>
                                    </tr>
                                    <?php
                                    $a++;
                                }
                            } else {
                                echo '<tr><td colspan="4" class="text-center text-muted">No hay pagos registrados aún.</td></tr>';
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <?php if($hay_descuentos): ?>
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-light">
                        <h6 class="m-0 font-weight-bold text-warning">
                            <i class="fa fa-tags me-2"></i>Descuentos Aplicados en Cuotas
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered text-center mb-0">
                                <thead class="thead-light">
                                <tr>
                                    <th class="text-muted" style="width: 50px;">#</th>
                                    <th class="text-muted">FECHA CUOTA</th>
                                    <th class="text-muted">FECHA REGISTRO</th> <th class="text-muted">USUARIO</th>        <th class="text-muted">CUOTA ORIGINAL</th>
                                    <th class="text-danger">DESCUENTO</th>
                                    <th class="text-success">MONTO FINAL</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $con_desc = 1;
                                foreach($descuentos_aplicados as $desc):
                                    $monto_original = floatval($desc->pago_diario_monto);
                                    $descuento = floatval($desc->pago_diario_descuento_monto);
                                    $monto_real_pagado = $monto_original - $descuento;
                                    ?>
                                    <tr>
                                        <td class="align-middle"><?= $con_desc++ ?></td>
                                        <td class="align-middle"><?= date('d/m/Y', strtotime($desc->pago_diario_fecha)) ?></td>

                                        <td class="align-middle">
                                            <small class="text-muted">
                                                <i class="fa fa-clock me-1"></i>
                                                <?= date('d/m/Y H:i', strtotime($desc->pago_diario_descuento_fecha)) ?>
                                            </small>
                                        </td>

                                        <td class="align-middle">
                                    <span class="badge badge-light border text-dark">
                                        <i class="fa fa-user me-1 text-secondary"></i>
                                        <?= htmlspecialchars($desc->pago_diario_descuento_usuario) ?>
                                    </span>
                                        </td>

                                        <td class="align-middle text-muted">S/ <?= number_format($monto_original, 2) ?></td>
                                        <td class="text-danger font-weight-bold align-middle">- S/ <?= number_format($descuento, 2) ?></td>
                                        <td class="text-success font-weight-bold align-middle">S/ <?= number_format($monto_real_pagado, 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                                <tfoot class="bg-light">
                                <tr>
                                    <td colspan="5" class="text-right font-weight-bold align-middle text-uppercase">Total Ahorrado:</td>
                                    <td class="text-danger font-weight-bold h6 mb-0 align-middle">- S/ <?= number_format($total_descuento, 2) ?></td>
                                    <td></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="mt-3 d-flex justify-content-between align-items-center">
                            <div class="text-muted" style="font-size: 0.85rem;">
                                <strong><i class="fa fa-chart-pie me-1"></i>Resumen:</strong>
                                Se han aplicado descuentos en <b><?= count($descuentos_aplicados) ?></b> cuota(s).
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>prestamos.js"></script>