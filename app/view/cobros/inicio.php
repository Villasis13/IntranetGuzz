<!--Contenido-->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Cobros Pendientes</h1>
        <a href="Descuentos.php" class="btn btn-danger shadow-sm">
            <i class="fa fa-percent me-2"></i>Ver Descuentos
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-primary py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-calendar-day me-2"></i>Cobros Diarios
                </h5>
                <a href="Reporte_Cobranza_Diario.php" target="_blank" class="btn btn-success btn-sm">
                    <i class="fa fa-print me-2"></i>Imprimir
                </a>
            </div>
        </div>
        <div class="card-body" style="padding: 0px;">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="example1">
                    <thead>
                    <tr class="text-center">
                        <th>DNI</th>
                        <th>Vencimiento</th>
                        <th>Nombre</th>
                        <th>Capital</th>
                        <th>Saldo</th>
                        <th>Próximo Cobro</th>
                        <th>Mora</th>
                        <th>Pagar</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php ?>
                    <tr class="text-center">
                        <td class="align-middle">
                            71423949
                        </td>
                        <td class="align-middle">
                            5 días
                        </td>
                        <td class="align-middle text-right">Bryan Diaz</td>
                        <td class="align-middle text-success fw-bold">S/ 1500.00</td>
                        <td class="align-middle text-danger fw-bold">S/ 500.00</td>
                        <td class="align-middle">15-07-2024</td>
                        <td class="align-middle">
                            2 días
                        </td>
                        <td class="align-middle">
                            <a href="pagar" class="btn btn-primary btn-sm">
                                <i class="fa fa-hand-lizard-o"></i>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">

        <div class="card-header bg-info py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-calendar-day me-2"></i>Cobros Semanales
                </h5>
            </div>
        </div>
        <div class="card-body" style="padding: 0px;">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="example2">
                    <thead>
                    <tr class="text-center">
                        <th>DNI</th>
                        <th>Vencimiento</th>
                        <th>Nombre</th>
                        <th>Capital</th>
                        <th>Saldo</th>
                        <th>Próximo Cobro</th>
                        <th>Mora</th>
                        <th>Pagar</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php ?>
                    <tr class="text-center">
                        <td class="align-middle">
                            71423949
                        </td>
                        <td class="align-middle">
                            5 días
                        </td>
                        <td class="align-middle text-right">Bryan Diaz</td>
                        <td class="align-middle text-success fw-bold">S/ 1500.00</td>
                        <td class="align-middle text-danger fw-bold">S/ 500.00</td>
                        <td class="align-middle">15-07-2024</td>
                        <td class="align-middle">
                            2 días
                        </td>
                        <td class="align-middle">
                            <a href="pagar" class="btn btn-primary btn-sm">
                                <i class="fa fa-hand-lizard-o"></i>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">

        <div class="card-header bg-secondary py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-calendar-day me-2"></i>Cobros Mensuales
                </h5>
            </div>
        </div>
        <div class="card-body" style="padding: 0px;">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="example3">
                    <thead>
                    <tr class="text-center">
                        <th>DNI</th>
                        <th>Vencimiento</th>
                        <th>Nombre</th>
                        <th>Capital</th>
                        <th>Saldo</th>
                        <th>Próximo Cobro</th>
                        <th>Mora</th>
                        <th>Pagar</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php ?>
                    <tr class="text-center">
                        <td class="align-middle">
                            71423949
                        </td>
                        <td class="align-middle">
                            5 días
                        </td>
                        <td class="align-middle text-right">Bryan Diaz</td>
                        <td class="align-middle text-success fw-bold">S/ 1500.00</td>
                        <td class="align-middle text-danger fw-bold">S/ 500.00</td>
                        <td class="align-middle">15-07-2024</td>
                        <td class="align-middle">
                            2 días
                        </td>
                        <td class="align-middle">
                            <a href="pagar" class="btn btn-primary btn-sm">
                                <i class="fa fa-hand-lizard-o"></i>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<style>
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }
    .badge {
        font-size: 0.85em;
        padding: 0.5em 0.75em;
    }
</style>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>