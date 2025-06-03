<!-- Modal -->
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

<!--Contenido-->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header bg-gradient-primary py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="m-0 font-weight-bold text-white">
                            <i class="fas fa-users me-2"></i>CLIENTES REGISTRADOS
                        </h5>
                        <button onclick="limpiar_clientes()" data-toggle="modal" data-target="#gestionCliente" class="btn btn-success btn-sm shadow-sm">
                            <i class="fa fa-plus-circle me-2"></i>Nuevo Cliente
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive-md">
                        <table class="table table-hover table-bordered" id="dataTable">
                            <thead class="thead-light">
                            <tr class="text-center">
                                <th class="align-middle">#</th>
                                <th class="align-middle">Nombre Completo</th>
                                <th class="align-middle">DNI</th>
                                <th class="align-middle">Cónyuge</th>
                                <th class="align-middle">Contacto</th>
                                <th class="align-middle">Dirección</th>
                                <th class="align-middle">Línea Crédito</th>
                                <th class="align-middle">Garantes</th>
<!--                                <th class="align-middle">Acciones</th>-->
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            $contador_cliente = 1;
                            foreach ($clientes as $c){
                                ?>
                                <tr class="text-center">
                                    <td class="align-middle"><?=$contador_cliente?></td>
                                    <td class="align-middle text-left">
                                        <div class="fw-bold"><?=$c->cliente_nombre?></div>
                                        <small class="text-muted">
                                            <?=$c->cliente_apellido_paterno . ' ' . $c->cliente_apellido_materno?>
                                        </small>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-primary"><?=$c->cliente_dni?></span>
                                    </td>
                                    <td class="align-middle"><?=$c->cliente_pareja ?: '--'?></td>
                                    <td class="align-middle">
                                        <div><?=$c->cliente_celular?></div>
                                        <small class="text-info"><?=$c->cliente_correo ?: ''?></small>
                                    </td>
                                    <td class="align-middle">
                                        <div><?=$c->cliente_direccion?></div>
                                        <small class="text-muted"><?=$c->cliente_referencia?></small>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-success rounded-pill">
                                            S/ <?=number_format($c->cliente_credito, 2)?>
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <a href="<?=_SERVER_?>Clientes/garante/<?= $c->id_cliente ?>" class="btn btn-primary btn-sm">
                                            <i class="fa fa-user-circle"></i>
                                        </a>
                                    </td>
                                    <!--<td class="align-middle">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a class="btn btn-warning btn-sm px-3" data-toggle="modal" data-target="#gestionCliente" onclick="editar_clientes(<?php /*= $c->id_cliente */?>)">
                                                <i class="fa fa-edit text-white"></i>
                                            </a>
                                            <a class="btn btn-danger btn-sm px-3" onclick="preguntar('¿Estás seguro de eliminar este cliente?','eliminar_cliente','Confirmar','Cancelar','<?php /*= $c->id_cliente */?>')">
                                                <i class="fa fa-trash text-white"></i>
                                            </a>
                                        </div>
                                    </td>-->
                                </tr>
                                <?php
                                $contador_cliente++;
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

<style>
    .table thead th {
        background-color: #3487C9;
        color: white !important;
        font-weight: 500;
    }
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
    .form-control:focus {
        border-color: #3487C9;
        box-shadow: 0 0 0 2px rgba(52,135,201,0.25);
    }
</style>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>clientes.js"></script>