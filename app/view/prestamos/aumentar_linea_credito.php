<!--Contenido-->
<div class="container-fluid">
    <div>
        <h1 class="h3 mb-4 text-gray-800">Ajustar Línea de Crédito</h1>
        <div class="row">
            <div class="col-lg-3"></div>
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-9">
                                <input type="hidden" id="id_cliente" name="id_cliente" value="<?= $data_cliente->id_cliente ?>">
                                <input type="hidden" id="credito_actual_hidden" value="<?= $data_cliente->cliente_credito ?? 0 ?>">
                                <label for="">Nombre Cliente:</label>
                                <input type="text" value="<?= $data_cliente->cliente_nombre . ' ' .
                                    $data_cliente->cliente_apellido_paterno . ' ' . $data_cliente->cliente_apellido_materno ?>"
                                       class="form-control" readonly>
                            </div>
                            <div class="col-lg-3">
                                <label for="">DNI</label>
                                <input type="text" maxlength="8" value="<?= $data_cliente->cliente_dni ?>"
                                       class="form-control" readonly>
                            </div>

                            <div class="col-lg-4 mt-2">
                                <label for="">Crédito Actual</label>
                                <input type="text" value="S/. <?= $data_cliente->cliente_credito ?? 0 ?>"
                                       class="form-control fw-bold text-primary" readonly>
                            </div>
                            <div class="col-lg-4 mt-2">
                                <label for="incremento" id="label_monto_ajuste">Monto de Incremento (S/.)</label>
                                <div class="input-group">
                                    <select id="tipo_ajuste" name="tipo_ajuste" class="form-select"
                                            style="max-width:150px" onchange="calcular_nuevo_credito()">
                                        <option value="incremento">Incremento</option>
                                        <option value="disminucion">Disminución</option>
                                        <option value="correccion">Corrección</option>
                                    </select>
                                    <input type="text" id="incremento" name="incremento" class="form-control"
                                           placeholder="0.00"
                                           oninput="calcular_nuevo_credito()"
                                           onkeyup="validar_numeros_decimales_dos(this.id)">
                                </div>
                            </div>
                            <div class="col-lg-4 mt-2">
                                <label for="nuevo_credito">Nuevo Crédito (S/.)</label>
                                <input type="text" id="nuevo_credito" class="form-control fw-bold text-success"
                                       readonly placeholder="—">
                            </div>

                            <div class="col-lg-6 mt-2">
                                <label for="clave_validacion">Clave de Validación</label>
                                <input type="password" id="clave_validacion" name="clave_validacion" class="form-control">
                                <label class="text-danger"><small>*Ingresar clave del administrador</small></label>
                            </div>
                            <div class="col-lg-12 mt-2">
                                <label for="motivo_aumento">Motivo del ajuste</label>
                                <textarea id="motivo_aumento" name="motivo_aumento" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="col-lg-4 mt-3">
                                <a id="btn_alc" onclick="ajustar_linea_credito()" class="btn btn-success text-white">
                                    <i class="fa fa-save"></i> Guardar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>prestamos.js"></script>
