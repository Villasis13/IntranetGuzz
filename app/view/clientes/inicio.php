<!-- Modal Agregar-->
<div class="modal fade" id="gestionCliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">AGREGAR/EDITAR CLIENTES</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <input type="hidden" id="id_cliente" name="id_cliente">
                                        <label for="cliente_nombre" class="col-form-label">Nombre del cliente</label>
                                        <input class="form-control" type="text" id="cliente_nombre" name="cliente_nombre" maxlength="20" placeholder="Ingrese Información...">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="cliente_apellidos" class="col-form-label">Apellidos del cliente</label>
                                        <input class="form-control" type="text" id="cliente_apellidos" name="cliente_apellidos" maxlength="20" placeholder="Ingrese Información...">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="cliente_dni" class="col-form-label">DNI del cliente</label>
                                        <input class="form-control" type="text" id="cliente_dni" name="cliente_dni" maxlength="10" placeholder="Ingrese Información...">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="cliente_celular" class="col-form-label">Celular del cliente</label>
                                        <input class="form-control" type="text" id="cliente_celular" name="cliente_celular" maxlength="10" placeholder="Ingrese Información...">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            <label class="col-form-label">Email del cliente</label>
                                            <input class="form-control" type="email" id="cliente_email" name="cliente_email" maxlength="50"  placeholder="Ingrese Información...">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-form-label">Genero</label>
                                            <select class="form-control"  name="cliente_genero" id="cliente_genero">
                                                <option value="">Selecciona...</option>
                                                <option value="MASCULINO">MASCULINO</option>
                                                <option value="FEMENINO">FEMENINO</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btn-agregar-cliente" onclick="guardar_editar_clientes()"><i class="fa fa-save fa-sm text-white-50"></i> Guardar</button>
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
                    <button onclick="limpiar_clientes()" style="float: right; position: relative;" data-toggle="modal" id="botonmodal"  data-target="#gestionCliente" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i class="fa fa-plus fa-sm text-white-50"></i> Agregar Nuevo</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
                            <thead class="text-capitalize">
                            <tr>
                                <th>ID</th>
                                <th>Nombres y Apellidos</th>
                                <th>DNI</th>
                                <th>Celular</th>
                                <th>Genero</th>
                                <th>Email</th>
                                <th>Opciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $contodor_cliente = 0;
                            foreach ($clientes as $c){
                                ?>
                                <tr>
                                    <td><?=$c->id_cliente;?></td>
                                  <!--  <td><center><?php /*= $contodor_cliente = $contodor_cliente + 1; */?></center></td>-->
                                    <td>
                                        <?=$c->cliente_nombre;?>
										<?=$c->cliente_apellidos;?>
                                    </td>
                                    <td><?=$c->cliente_dni;?></td>
                                    <td><?=$c->cliente_celular;?></td>
                                    <td><?=$c->cliente_genero;?></td>
                                    <td><?=$c->cliente_email;?></td>
                                    <td>
                                        <a type="button" data-toggle="modal" data-target="#gestionCliente"  class="btn btn-sm btn-warning text-white" onclick="editar_clientes(<?= $c->id_cliente ?>)"><i class="fa fa-pencil"></i> Editar</a>
                                        <a type="button" onclick="preguntar('¿Estas seguro de eliminar este Cliente?', 'eliminar_cliente', 'Si','No','<?= $c->id_cliente ?>')" class="btn btn-sm btn-danger text-white"><i class="fa fa-trash"></i> Eliminar</a>
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
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>clientes.js"></script>