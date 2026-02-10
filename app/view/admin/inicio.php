<div class="modal fade" id="gestionCliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-edit me-2 text-white"></i>AGREGAR/EDITAR CLIENTE
                </h5>
                <button type="button" class="btn-close btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="container-fluid">
                    <input type="hidden" id="id_cliente" name="id_cliente">

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">DNI <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cliente_dni" name="cliente_dni" maxlength="8" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nombres <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cliente_nombre" name="cliente_nombre" required>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Apellido Paterno <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="cliente_apellido_paterno" name="cliente_apellido_paterno" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Apellido Materno <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="cliente_apellido_materno" name="cliente_apellido_materno" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" id="cliente_fecha_nacimiento" name="cliente_fecha_nacimiento">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Lugar de Trabajo</label>
                                <input type="text" class="form-control" id="cliente_lugar_trabajo" name="cliente_lugar_trabajo">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Dirección <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cliente_direccion" name="cliente_direccion" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Referencia</label>
                                <input type="text" class="form-control" id="cliente_referencia" name="cliente_referencia">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Celular <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="cliente_celular" name="cliente_celular" required>
                                </div>

                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-md-8">
                                    <label class="form-label">N° Tarjeta</label>
                                    <input type="text" class="form-control" id="cliente_nro_tarjeta" name="cliente_nro_tarjeta">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Clave</label>
                                    <input type="password" class="form-control" id="cliente_clave" name="cliente_clave">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Información Adicional</label>
                                <input type="text" class="form-control" id="cliente_otro" name="cliente_otro">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="cliente_correo"
                                       name="cliente_correo">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fa fa-times me-2"></i>Cerrar
                </button>
                <button type="button" class="btn btn-success" onclick="guardar_editar_clientes()">
                    <i class="fa fa-save me-2"></i>Guardar
                </button>
            </div>
        </div>
    </div>
</div>

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
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">S/ 
                                        
										<?= is_array($ingresos_hoy) ? $ingresos_hoy : 0 ?>
                                    </div>
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
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">S/ 
										<?= is_array($egresos_hoy) ? $egresos_hoy : 0 ?>
                                    </div>
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
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $num_clientes ?></div>
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
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= is_array($prestamos_hoy) ? count($prestamos_hoy) : 0 ?>
                                    </div>
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
                                        <?php
                                        foreach ($proximos_cobros as $pc){
                                            ?>
                                            <tr class="text-center">
                                                <td><?= $pc->cliente_nombre . ' ' . $pc->cliente_apellido_paterno ?></td>
                                                <td>S/ <?= $pc->pago_diario_monto ?></td>
                                                <td><?= $pc->pago_diario_fecha ?></td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                        
                                    </tbody>
                                </table>
                            </div>
<!--                            <a href="cobros/inicio" class="btn btn-secondary mt-3">-->
<!--                                <i class="fa fa-list fa-sm text-white-50"></i> Ver Todos-->
<!--                            </a>-->
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
                                <a href="<?= _SERVER_ ?>Prestamos/prestamos_antiguos" class="btn btn-secondary">
                                    <i class="fa fa-history mr-2"></i> Préstamos Antiguos
                                </a>
                                <a href="<?= _SERVER_ ?>Ventas/inicio" class="btn btn-secondary">
                                    <i class="fa fa-eject mr-2"></i> Ventas a Plazo
                                </a>
                                <!--<a href="Inventario.php" class="btn btn-secondary">
                                    <i class="fa fa-chain mr-2"></i> Gestión Garantías
                                </a>-->
                                <a href="<?= _SERVER_ ?>Admin/documentos" class="btn btn-secondary">
                                    <i class="fa fa-folder mr-2"></i> Documentos
                                </a>
                                <a href="<?= _SERVER_ ?>Admin/reportes" class="btn btn-secondary">
                                    <i class="fa fa-folder mr-2"></i> Reportes
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
                            <h6 class="m-0 font-weight-bold text-white">Los siguientes clientes deben ser actualizados</h6>
                        </div>
                        <br>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" id="dataTable">
                                    <thead class="text-center">
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Dirección</th>
                                        <th>Última Actualización</th>
                                        <th>Editar</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($actualizar_clientes as $ac){
                                        ?>
                                        <tr class="text-center">
                                            <td><?= $ac->cliente_nombre . ' ' . $ac->cliente_apellido_paterno . ' ' . $ac->cliente_apellido_materno ?></td>
                                            <td><?= $ac->cliente_direccion ?></td>
                                            <td><?= $ac->cliente_fecha ?></td>
<!--                                            <td>-->
<!--                                                <a href="#" class="btn btn-sm btn-warning">-->
<!--                                                    <i class="fa fa-edit"></i>-->
<!--                                                </a>-->
<!--                                            </td>-->
                                            <td>
                                                <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#gestionCliente" onclick="editar_clientes(<?= $ac->id_cliente ?>)">
                                                    <i class="fa fa-edit text-white"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
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
<script src="<?php echo _SERVER_ . _JS_;?>clientes.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>