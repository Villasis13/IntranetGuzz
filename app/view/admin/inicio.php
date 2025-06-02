<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Datos Financieros</h1>
                <div class="d-flex align-items-center">
                    <a href="<?= _SERVER_ ?>Caja/inicio" class="btn btn-danger shadow-sm">
                        <i class="fas fa-cash-register fa-sm text-white-50"></i> Caja Chica
                    </a>
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Ingresos Hoy</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">S/ 1,850.00</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-money fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Egresos Hoy</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">S/ 920.00</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-hand-o-down fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Clientes Activos</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">38</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-user fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Préstamos Hoy</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">5</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-file fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 bg-primary">
                            <h6 class="m-0 font-weight-bold text-white">Próximos Cobros</h6>
                        </div>
                        <br>
                        <div class="card-body" style="margin-bottom: 21px;">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%">
                                    <thead class="text-center">
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Monto</th>
                                        <th>Fecha</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="text-center">
                                        <td>Juan Pérez</td>
                                        <td>S/ 150.00</td>
                                        <td>15-05-2023</td>
                                    </tr>
                                    <tr class="text-center">
                                        <td>María Gómez</td>
                                        <td>S/ 200.00</td>
                                        <td>16-05-2023</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <a href="cobros/inicio" class="btn btn-secondary mt-3">
                                <i class="fa fa-list fa-sm text-white-50"></i> Ver Todos
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <!-- Acciones Rápidas -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 bg-success">
                            <h6 class="m-0 font-weight-bold text-white">Acciones Rápidas</h6>
                        </div>
                        <br>
                        <div class="card-body">
                            <div class="d-grid gap-3">
                                <a href="Prestamos_Antiguos.php" class="btn btn-secondary">
                                    <i class="fa fa-history mr-2"></i> Préstamos Antiguos
                                </a>
                                <a href="VentasCasuales.php" class="btn btn-secondary">
                                    <i class="fa fa-eject mr-2"></i> Ventas a Plazo
                                </a>
                                <a href="Inventario.php" class="btn btn-secondary">
                                    <i class="fa fa-chain mr-2"></i> Gestión Garantías
                                </a>
                                <a href="Documentos_Registrados.php" class="btn btn-secondary">
                                    <i class="fa fa-folder mr-2"></i> Documentos
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Logo Central -->
<!--                    <div class="card shadow">-->
<!--                        <div class="card-body text-center">-->
<!--                            <img style="width: 300px; height: 300px;"-->
<!--                                 src="--><?php //= _SERVER_._STYLES_bt5_ ?><!--assets/img/icons/unicons/MG1.png"-->
<!--                                 alt="Logo MG"-->
<!--                                 class="img-fluid">-->
<!--                        </div>-->
<!--                    </div>-->
                </div>
                <div class="col-lg-12">
                    <!-- Actualizaciones Requeridas -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 bg-info">
                            <h6 class="m-0 font-weight-bold text-white">Actualización de Clientes</h6>
                        </div>
                        <br>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%">
                                    <thead class="text-center">
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Dirección</th>
                                        <th>Última Actualización</th>
                                        <th>Editar</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="text-center">
                                        <td>Carlos Rojas</td>
                                        <td>Calle Secada N° 456</td>
                                        <td>15/01/2023</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-warning">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>caja.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>