<?php
$h = function ($t) { return htmlspecialchars((string)$t, ENT_QUOTES, 'UTF-8'); };
$S = function ($v) { return 'S/ ' . number_format((float)$v, 2); };
$hoy = date('Y-m-d');
$total_por_cobrar = 0;
foreach ($prestamos_antiguos as $c) $total_por_cobrar += floatval($c->prestamo_saldo_pagar);
?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-2">
        <h1 class="h3 mb-0 text-gray-800">Préstamos en Recuperación</h1>
        <a target="_blank" href="<?= _SERVER_ ?>Prestamos/reporte_prestamos_antiguos" class="btn btn-danger shadow-sm">
            <i class="fa fa-file-pdf-o me-1"></i> Ver Reporte
        </a>
    </div>
    <p class="text-muted">
        Deudas que ya no siguen el cronograma original y se cobran con un nuevo acuerdo mediante abonos parciales.
        Por cobrar: <b class="text-danger"><?= $S($total_por_cobrar) ?></b> en <?= count($prestamos_antiguos) ?> préstamo(s).
    </p>

    <div class="card shadow mb-4">
        <div class="card-header bg-gradient-primary py-3">
            <h5 class="m-0 font-weight-bold">Pendientes</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="dataTable">
                    <thead class="thead-light">
                    <tr class="text-center">
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Deuda original</th>
                        <th>Acuerdo</th>
                        <th>Pagado</th>
                        <th>Falta pagar</th>
                        <th>Próximo abono</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $a = 1;
                    foreach ($prestamos_antiguos as $c) {
                        $deuda_original = floatval($c->prestamo_monto) + floatval($c->prestamo_monto_interes);
                        $prox = $c->prestamo_acuerdo_proxima_fecha;
                        $atrasado = $prox && $prox < $hoy;
                        ?>
                        <tr class="text-center">
                            <td class="align-middle"><?= $a++ ?></td>
                            <td class="align-middle text-left">
                                <div class="fw-bold"><?= $h($c->cliente_nombre . ' ' . $c->cliente_apellido_paterno . ' ' . $c->cliente_apellido_materno) ?></div>
                                <small class="text-muted">DNI <?= $h($c->cliente_dni) ?> · Préstamo #<?= $c->id_prestamos ?>
                                    del <?= date('d/m/Y', strtotime($c->prestamo_fecha_emision)) ?></small>
                            </td>
                            <td class="align-middle text-nowrap"><?= $S($deuda_original) ?></td>
                            <td class="align-middle">
                                <?php if ($c->prestamo_acuerdo_monto !== null) { ?>
                                    <div class="text-nowrap fw-bold"><?= $S($c->prestamo_acuerdo_monto) ?></div>
                                    <small class="text-muted">
                                        <?= $c->prestamo_acuerdo_abono_sugerido ? $S($c->prestamo_acuerdo_abono_sugerido) . ' · ' : '' ?><?= $h($c->prestamo_acuerdo_frecuencia) ?>
                                    </small>
                                <?php } else { ?>
                                    <span class="badge bg-warning text-dark">Sin acuerdo</span>
                                <?php } ?>
                            </td>
                            <td class="align-middle text-nowrap text-success"><?= $S($c->total_pagado) ?></td>
                            <td class="align-middle text-nowrap fw-bold text-danger"><?= $S($c->prestamo_saldo_pagar) ?></td>
                            <td class="align-middle text-nowrap">
                                <?php if ($prox) { ?>
                                    <span class="<?= $atrasado ? 'text-danger fw-bold' : '' ?>"><?= date('d/m/Y', strtotime($prox)) ?></span>
                                    <?php if ($atrasado) { ?><div><span class="badge bg-danger text-white">Atrasado</span></div><?php } ?>
                                <?php } else { echo '—'; } ?>
                            </td>
                            <td class="align-middle">
                                <a href="<?= _SERVER_ ?>Prestamos/recuperacion/<?= $c->id_prestamos ?>" class="btn btn-sm btn-primary text-white text-nowrap">
                                    <i class="fa fa-folder-open"></i> Gestionar
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-gradient-primary py-3">
            <h5 class="m-0 font-weight-bold">Recuperados (deuda saldada)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="tablaRecuperados">
                    <thead class="thead-light">
                    <tr class="text-center">
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Deuda original</th>
                        <th>Total pagado</th>
                        <th>Último pago</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $a = 1;
                    foreach ($prestamos_antiguos_cancelados as $c) { ?>
                        <tr class="text-center">
                            <td class="align-middle"><?= $a++ ?></td>
                            <td class="align-middle text-left">
                                <div class="fw-bold"><?= $h($c->cliente_nombre . ' ' . $c->cliente_apellido_paterno . ' ' . $c->cliente_apellido_materno) ?></div>
                                <small class="text-muted">DNI <?= $h($c->cliente_dni) ?> · Préstamo #<?= $c->id_prestamos ?></small>
                            </td>
                            <td class="align-middle text-nowrap"><?= $S(floatval($c->prestamo_monto) + floatval($c->prestamo_monto_interes)) ?></td>
                            <td class="align-middle text-nowrap text-success"><?= $S($c->total_pagado) ?></td>
                            <td class="align-middle text-nowrap"><?= $c->ultimo_pago ? date('d/m/Y', strtotime($c->ultimo_pago)) : '—' ?></td>
                            <td class="align-middle">
                                <a href="<?= _SERVER_ ?>Prestamos/recuperacion/<?= $c->id_prestamos ?>" class="btn btn-sm btn-secondary text-white text-nowrap">
                                    <i class="fa fa-eye"></i> Historial
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if (empty($prestamos_antiguos_cancelados)) { ?>
                        <tr><td colspan="6" class="text-center text-muted">Aún no hay deudas recuperadas por completo.</td></tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .table thead th { background-color: #3487C9; color: white !important; font-weight: 500; }
</style>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>prestamos.js"></script>
