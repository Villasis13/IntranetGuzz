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
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label fw-bold mb-0">Direcciones <span class="text-danger">*</span></label>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="agregarDireccion()">
                                        <i class="fa fa-plus me-1"></i>Agregar
                                    </button>
                                </div>
                                <div id="direcciones_container" style="max-height:290px; overflow-y:auto; padding-right:2px;"></div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Celular <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="cliente_celular" name="cliente_celular"
                                               inputmode="numeric" maxlength="15"
                                               oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
                                        <button type="button" class="btn btn-outline-secondary" id="btn_add_celular2"
                                                onclick="toggle_celular2()" title="Agregar segundo celular">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-12" id="div_celular2" style="display:none;">
                                    <label class="form-label">Celular 2</label>
                                    <input type="text" class="form-control" id="cliente_celular2" name="cliente_celular2"
                                           inputmode="numeric" maxlength="15" placeholder="Segundo número de contacto"
                                           oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                                </div>
                            </div>

                            <div class="row g-2 mb-3" style="display: none">
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
                            <div class="mb-3">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="cliente_correo"
                                       name="cliente_correo">
                            </div>
                        </div>
                    </div>

                    <!-- Documentos y fotos del cliente -->
                    <div class="border rounded p-3 mt-2 bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-bold mb-0">
                                <i class="fa fa-paperclip me-1"></i> Documentos y Fotos
                            </label>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="agregarDocumento()">
                                <i class="fa fa-plus me-1"></i>Adjuntar
                            </button>
                        </div>
                        <small class="text-muted d-block mb-2">
                            DNI, recibo de luz, foto personal u otros. Formatos: JPG, PNG, WEBP, PDF, DOC, DOCX (máx. 10 MB por archivo).
                        </small>

                        <!-- Documentos ya guardados (al editar) -->
                        <div id="documentos_guardados"></div>
                        <!-- Archivos nuevos por subir -->
                        <div id="documentos_container"></div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fa fa-times me-2"></i>Cerrar
                </button>
                <button type="button" class="btn btn-success" id="btn-agregar-cliente" onclick="guardar_editar_clientes()">
                    <i class="fa fa-save me-2"></i>Guardar
                </button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="PasarMorosoCliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-edit me-2 text-white"></i>PASAR A MOROSO AL CLIENTE
                </h5>
                <button type="button" class="btn-close btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="container-fluid">
                    <input type="hidden" id="id_cliente_moroso" name="id_cliente_moroso">

                    <!-- Historial de pagos del cliente: un solo atraso no basta para marcarlo moroso -->
                    <div id="resumen_comportamiento" class="mb-3"></div>

                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Motivo por el cual pasa a moroso</label>
                                <textarea class="form-control" id="cliente_historial_moroso_comentario" name="cliente_historial_moroso_comentario"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fa fa-times me-2"></i>Cerrar
                </button>
                <button id="btn_pasar_moroso_cliente" name="btn_pasar_moroso_cliente" type="button" class="btn btn-success" onclick="guardar_editar_clientes_moroso()">
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
                        <h5 class="m-0 font-weight-bold text-black">
                            <i></i>CLIENTES REGISTRADOS (Busqueda por DNI)
                        </h5>
                        <button onclick="limpiar_clientes()" data-toggle="modal" data-target="#gestionCliente" 
                                class="btn btn-success btn-sm shadow-sm">
                            <i class="fa fa-plus-circle me-2"></i>Nuevo Cliente
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="dataTable">
                            <thead class="thead-light">
                            <tr class="text-center">
                                <th class="align-middle">#</th>
                                <th class="align-middle">Nombre Completo</th>
                                <th class="align-middle">DNI</th>
                                <th class="align-middle">Contacto</th>
                                <th class="align-middle">Dirección</th>
                                <th class="align-middle">Línea Crédito</th>
                                <th class="align-middle">Estado</th>
                                <th class="align-middle">Acciones</th>
<!--                                <th class="align-middle">Acciones</th>-->
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            $contador_cliente = 1;
                            $hoy_atraso = new DateTime(date('Y-m-d'));
                            foreach ($clientes as $c){
                                $es_moroso = intval($c->cliente_estado) !== 1;
                                $atraso = $clientes_atraso[$c->id_cliente] ?? null;
                                ?>
                                <tr class="text-center<?= $es_moroso ? ' fila-morosa' : ($atraso ? ' fila-atraso' : '') ?>"
                                    <?= $es_moroso ? 'title="Cliente marcado como moroso"' : ($atraso ? 'title="Cliente con pagos atrasados"' : '') ?>>
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
                                    <td class="align-middle">
                                        <div><?=$c->cliente_celular?></div>
                                        <?php if(!empty($c->cliente_celular2)): ?>
                                            <div class="text-muted"><small><?=$c->cliente_celular2?></small></div>
                                        <?php endif; ?>
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
                                    <td>
                                        <?php
                                        if($c->cliente_estado == 1){
                                            echo '<span class="badge bg-success">Activo</span>';
                                        }else{
                                            echo '<span class="badge bg-danger">Moroso</span>';
                                        }
                                        if ($atraso) {
                                            $dias_atraso = $atraso->cuota_mas_antigua
                                                ? $hoy_atraso->diff(new DateTime($atraso->cuota_mas_antigua))->days
                                                : null;
                                            ?>
                                            <div class="mt-1">
                                                <?php if ($atraso->atrasados > 0) { ?>
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fa fa-clock-o"></i> Con atraso
                                                    </span>
                                                    <?php if ($dias_atraso !== null) { ?>
                                                        <div><small class="text-warning-emphasis fw-bold" style="color:#9a6700;"><?= $dias_atraso ?> día<?= $dias_atraso == 1 ? '' : 's' ?> de atraso</small></div>
                                                    <?php } ?>
                                                <?php } ?>
                                                <?php if ($atraso->en_recuperacion > 0) { ?>
                                                    <div><span class="badge bg-secondary text-white mt-1">En recuperación</span></div>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="d-flex flex-wrap gap-2 justify-content-center">

                                            <a class="btn btn-primary btn-sm btn-square"
                                               data-toggle="modal" data-target="#gestionCliente"
                                               onclick="editar_clientes(<?= $c->id_cliente ?>)"
                                               title="Editar">
                                                <i class="fa fa-edit bg-white"></i>
                                            </a>

                                            <a href="<?=_SERVER_?>Clientes/historial_cliente/<?= $c->id_cliente ?>"
                                               class="btn btn-warning btn-sm btn-square"
                                               title="Historial">
                                                <i class="fa fa-history"></i>
                                            </a>

                                            <a href="<?=_SERVER_?>Clientes/garante/<?= $c->id_cliente ?>"
                                               class="btn btn-info btn-sm btn-square text-white"
                                               title="Garantes">
                                                <i class="fa fa-user-circle bg-white"></i>
                                            </a>

                                            <?php if($c->cliente_estado == 1){ ?>
                                                <a style="cursor: pointer"
                                                   class="btn btn-danger btn-sm btn-square"
                                                   data-toggle="modal" data-target="#PasarMorosoCliente"
                                                   onclick="poner_id_modal_moroso(<?= $c->id_cliente ?>)"
                                                   title="Marcar moroso">
                                                    <i class="fa fa-warning bg-white"></i>
                                                </a>
                                            <?php } else { ?>
                                                <a onclick="preguntar('¿Está seguro que desea activar a este cliente?',
                                                        'actualizar_cliente_a_moroso','SI','NO',<?= $c->id_cliente ?>,'1')"
                                                   style="cursor: pointer"
                                                   class="btn btn-secondary btn-sm btn-square"
                                                   title="Activar">
                                                    <i class="fa fa-check"></i>
                                                </a>
                                            <?php } ?>

                                        </div>
                                    </td>

                                    <!--<td class="align-middle">
                                        <div class="d-flex gap-2 justify-content-center">
                                            
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
    /* Clientes marcados como morosos (a mano, tras revisar su historial): fila en rojo */
    .table tbody tr.fila-morosa,
    .table-hover tbody tr.fila-morosa:hover {
        background-color: #f8d7da;
    }
    .table tbody tr.fila-morosa td {
        color: #842029;
    }
    .table tbody tr.fila-morosa td:first-child {
        border-left: 4px solid #dc3545;
    }
    /* Clientes con pagos atrasados: solo una alerta en ámbar; NO es el estado moroso */
    .table tbody tr.fila-atraso td:first-child {
        border-left: 4px solid #f6c23e;
    }
    .form-control:focus {
        border-color: #3487C9;
        box-shadow: 0 0 0 2px rgba(52,135,201,0.25);
    }
</style>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>clientes.js"></script>