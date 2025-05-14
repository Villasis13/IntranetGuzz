<!-- Modal Agregar-->
<div class="modal fade" id="gestionCliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">AGREGAR/EDITAR CLIENTES</h5>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <input type="hidden" id="id_cliente" name="id_cliente">
                    <div class="row">
                        <!-- Columna Izquierda -->
                        <div class="col-md-6">
                            <!-- DNI -->
                            <div class="form-group">
                                <label class="col-form-label">DNI <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cliente_dni" name="cliente_dni" maxlength="8" required>
                            </div>
                            <!-- Nombres -->
                            <div class="form-group">
                                <label class="col-form-label">Nombres <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cliente_nombre" name="cliente_nombre" maxlength="20" required>
                            </div>
                            <!-- Apellidos -->
                            <div class="form-group">
                                <label class="col-form-label">Apellido Paterno <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cliente_apellido_paterno" name="cliente_apellido_paterno" maxlength="20" required>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Apellido Materno <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cliente_apellido_materno" name="cliente_apellido_materno" maxlength="20" required>
                            </div>
                            <!-- Fecha Nacimiento -->
                            <div class="form-group">
                                <label class="col-form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" id="cliente_fecha_nacimiento" name="cliente_fecha_nacimiento">
                            </div>
                            <!-- Correo -->
                            <div class="form-group">
                                <label class="col-form-label">Lugar de Trabajo</label>
                                <input type="text" class="form-control" id="cliente_lugar_trabajo" name="cliente_lugar_trabajo" maxlength="100">
                            </div>
                        </div>
                        <!-- Columna Derecha -->
                        <div class="col-md-6">
                            <!-- Dirección -->
                            <div class="form-group">
                                <label class="col-form-label">Dirección <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cliente_direccion" name="cliente_direccion" maxlength="50" required>
                            </div>
                            <!-- Referencia -->
                            <div class="form-group">
                                <label class="col-form-label">Referencia de Dirección</label>
                                <input type="text" class="form-control" id="cliente_referencia" name="cliente_referencia" maxlength="100">
                            </div>
                            <!-- Celular -->
                            <div class="form-group">
                                <label class="col-form-label">Celular <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cliente_celular" name="cliente_celular" maxlength="12" required>
                            </div>
                            <!-- Tarjeta y Clave -->
                            <div class="form-group">
                                <label class="col-form-label">Número de Tarjeta</label>
                                <input type="text" class="form-control" id="cliente_nro_tarjeta" name="cliente_nro_tarjeta" maxlength="30">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Clave</label>
                                <input type="password" class="form-control" id="cliente_clave" name="cliente_clave" maxlength="30">
                            </div>
                            <!-- Lugar Trabajo y Otro -->
                            <div class="form-group">
                                <label class="col-form-label">Otro</label>
                                <input type="text" class="form-control" id="cliente_otro" name="cliente_otro" maxlength="200">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="cliente_correo" name="cliente_correo" maxlength="50">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fa fa-close fa-sm"></i>Cerrar
                </button>
                <button type="button" class="btn btn-success" id="btn-agregar-cliente" onclick="guardar_editar_clientes()">
                    <i class="fa fa-save fa-sm text-white-50"></i> Guardar
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
                <div class="card-header py-3">
                    <h5 style="font-size: 20px; color: #3487C9 !important" class="m-0 font-weight-bold text-primary">LISTADO DE CLIENTES REGISTRADOS</h5>
                    <button onclick="limpiar_clientes()" style="float: right; position: relative;" data-toggle="modal" id="botonmodal" data-target="#gestionCliente" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i class="fa fa-plus fa-sm text-white-50"></i> Agregar Nuevo</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
                            <thead class="text-capitalize">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>DNI</th>
                                <th>Cónyugue o Esposo</th>
                                <th>Teléfonos</th>
                                <th>Dirección</th>
                                <th>Recomendación</th>
                                <th>Tarjeta</th>
                                <th>Lugar Trabajo</th>
                                <th>Línea de Crédito</th>
                                <th>Editar</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $contador_cliente = 1;
                            foreach ($clientes as $c){
                                ?>
                                <tr>
                                    <td><?=$contador_cliente;?></td>
                                    <td><?=$c->cliente_nombre .' '. $c->cliente_apellido_paterno .' '. $c->cliente_apellido_materno;?></td>
                                    <td><?=$c->cliente_dni;?></td>
                                    <td><?=$c->cliente_pareja;?></td>
                                    <td><?=$c->cliente_celular;?></td>
                                    <td><?=$c->cliente_direccion;?></td>
                                    <td><?=$c->cliente_recomendacion;?></td>
                                    <td><?=$c->cliente_nro_tarjeta;?></td>
                                    <td><?=$c->cliente_lugar_trabajo;?></td>
                                    <td><?=$c->cliente_credito;?></td>
                                    <td>
                                        <a type="button" data-toggle="modal" data-target="#gestionCliente"  class="btn btn-sm btn-warning text-white" onclick="editar_clientes(<?= $c->id_cliente ?>)"><i class="fa fa-pencil"></i> Editar</a>
                                        <a type="button" onclick="preguntar('¿Estas seguro de eliminar este Cliente?', 'eliminar_cliente', 'Si','No','<?= $c->id_cliente ?>')" class="btn btn-sm btn-danger text-white"><i class="fa fa-trash"></i> Eliminar</a>
                                    </td>
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
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>clientes.js"></script>