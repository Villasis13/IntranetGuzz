<!--Contenido-->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="m-0 font-weight-bold text-white">
                            <i class="fa fa-user-circle-o me-2"></i>Gestión de Garantes de:  
                            <?= $data_cliente->cliente_nombre .' '.$data_cliente->cliente_apellido_paterno . ' - ' . 
                            $data_cliente->cliente_dni ?>
                        </h4>
                        <a href="<?= _SERVER_ ?>Clientes/inicio" class="btn btn-light">
                            <i class="fa fa-arrow-left me-2"></i>Volver a Clientes
                        </a>
                    </div>
                </div>
                <br>

                <div class="card-body bg-white">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">DNI del Garante</label>
                            <div class="input-group">
                                <input type="hidden" id="id_cliente" name="id_cliente" value="<?= $id_cliente ?>">
                                <input id="btn_dni_garante_nuevo" name="btn_dni_garante_nuevo" type="text" 
                                       class="form-control" placeholder="Ingrese DNI">
                                <button onclick="buscar_cliente_garante()" class="btn btn-warning">
                                    <i class="fa fa-search me-2"></i>Buscar
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Recomendado por</label>
                            <input id="id_cliente_recomendado" name="id_cliente_recomendado" type="hidden">
                            <input id="input_recomendado" name="input_recomendado" type="text" class="form-control"
                                   readonly>
                        </div>

                        <div class="col-md-2 mt-4">
                            <a onclick="guardar_garante()" class="btn text-white btn-success w-100">
                                <i class="fa fa-save me-2"></i>Guardar
                            </a>
                        </div>
                    </div>
                    
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header bg-info py-3">
                    <h5 class="m-0 font-weight-bold text-white">
                        <i class="fa fa-address-book me-2"></i>Garantes Registrados
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover m-0">
                            <thead class="bg-light">
                            <tr class="text-center">
                                <th>#</th>
                                <th>DNI</th>
                                <th>Nombre Completo</th>
                                <th>Dirección</th>
                                <th>Contacto</th>
                                <th>Estado</th>
<!--                                <th>Acción</th>-->
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $a = 1;
                            foreach ($garantes as $g){
                                if($g->cliente_garante_estado==1){
                                    $class = 'text-success';
                                    $estado = 'Activo';
                                }else{
                                    $class = 'text-danger';
                                    $estado = 'Inactivo';
                                }
                                ?>
                                <tr class="text-center">
                                    <td><?= $a ?></td>
                                    <td><?= $g->cliente_dni ?></td>
                                    <td><?= $g->cliente_nombre . ' ' . $g->cliente_apellido_paterno . ' ' . $g->cliente_apellido_materno ?></td>
                                    <td><?= $g->cliente_direccion ?></td>
                                    <td><?= $g->cliente_dni ?></td>
                                    <td><span class="badge <?= $class ?>"><?= $estado ?></span></td>
                                    <td>
                                        
                                    </td>
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
    </div>
</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>clientes.js"></script>