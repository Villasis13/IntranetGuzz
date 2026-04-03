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

    // Sumar todos los descuentos aplicados
    $total_descuento = 0;
    $hay_descuentos = isset($descuentos_monto[0]->total) && floatval($descuentos_monto[0]->total) > 0;
    if ($hay_descuentos && !empty($descuentos_prestamos)) {
        foreach ($descuentos_prestamos as $c) {
            $total_descuento += floatval($c->descuento_monto);
        }
    }

    // Calcular el monto total del préstamo (Capital + Interés)
    $monto_capital = isset($listar_cliente_x_prestamo->prestamo_monto) ? floatval($listar_cliente_x_prestamo->prestamo_monto) : 0;
    $monto_interes = isset($listar_cliente_x_prestamo->prestamo_monto_interes) ? floatval($listar_cliente_x_prestamo->prestamo_monto_interes) : 0;
    $total_prestamo = $monto_capital + $monto_interes;

    // Calcular el saldo que aún debe el cliente
    $saldo_pendiente = $total_prestamo - $total_pagado - $total_descuento;

    // ==========================================
    // 2. LÓGICA DE ANULACIÓN (REGLA 48 HORAS)
    // ==========================================
    $tiene_pagos = ($total_pagado > 0) ? true : false;

    // OJO: Asegúrate de que tu modelo traiga la fecha de registro del préstamo.
    // Ajusta 'prestamo_fecha_registro' al nombre real de tu columna en la BD.
    $fecha_creacion = isset($listar_cliente_x_prestamo->prestamo_fecha_registro) ? $listar_cliente_x_prestamo->prestamo_fecha_registro : date('Y-m-d H:i:s');

    // Calculamos la diferencia en horas
    $diferencia_segundos = strtotime(date('Y-m-d H:i:s')) - strtotime($fecha_creacion);
    $horas_transcurridas = $diferencia_segundos / 3600;

    // Condición estricta: No tiene pagos, han pasado menos de 48 hrs y asumiendo que el estado sigue activo
    $se_puede_anular = (!$tiene_pagos && $horas_transcurridas <= 48);
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
                                // Se usa htmlspecialchars para seguridad y nl2br para respetar los saltos de línea
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
                    <h6 style="font-weight: bold" class="m-0 font-weight-bold">
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
                <div class="card shadow mb-4 border-left-warning">
                    <div class="card-header bg-warning py-3">
                        <h6 style="font-weight: bold" class="m-0 font-weight-bold text-white">
                            Descuentos Aplicados
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped" id="dataTableDescuentos">
                                <thead class="thead-light">
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Monto Descontado</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $a = 1;
                                foreach ($descuentos_prestamos as $c){
                                    ?>
                                    <tr class="text-center">
                                        <td><?= $a ?></td>
                                        <td><?= $c->descuento_fecha ?></td>
                                        <td class="font-weight-bold text-warning">S/. <?= number_format($c->descuento_monto, 2) ?></td>
                                    </tr>
                                    <?php
                                    $a++;
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>prestamos.js"></script>