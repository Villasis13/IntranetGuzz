<!--Contenido-->
<div class="container-fluid">
    <div>
        <h1 class="h3 mb-4 text-gray-800">Aumentar Línea de Crédito</h1>
        <div class="row">
            <div class="col-lg-3"></div>
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-9">
                                <input type="hidden" id="id_cliente" name="id_cliente" value="<?= $data_cliente->id_cliente ?>">
                                <label for="">Nombre Cliente:</label>
                                <input type="text" id="" name="" value="<?= $data_cliente->cliente_nombre . ' ' .
								$data_cliente->cliente_apellido_paterno . ' ' . $data_cliente->cliente_apellido_materno?>"
                                       class="form-control" readonly>
                            </div>
                            <div class="col-lg-3">
                                <label for="">DNI</label>
                                <input type="text" maxlength="8" id="" name="" value="<?= $data_cliente->cliente_dni ?>"
                                       class="form-control" readonly>
                            </div>
                            <div class="col-lg-4">
                                <label for="">Crédito Actual</label>
                                <input type="text" id="" name="" value="S/. <?= $data_cliente->cliente_credito ?? 0 ?>"

                                       class="form-control" readonly>
                            </div>
                            <div class="col-lg-4">
                                <label for="incremento">Incremento (S/.)</label>
                                <input type="text" id="incremento" name="incremento" class="form-control" 
                                       onkeyup="validar_numeros_decimales_dos(this.id)">
                            </div>
                            <div class="col-lg-4">
                                <label for="clave_validacion">Clave de Validación</label>
                                <input type="password" id="clave_validacion" name="clave_validacion" class="form-control">
                                <label class="text-danger" for="">*Ingresar clave del administrador</label>
                            </div>
                            <div class="col-lg-12">
                                <label for="motivo_aumento">Motivo de aumento</label>
                                <textarea type="text" id="motivo_aumento" name="motivo_aumento" class="form-control"></textarea>
                            </div>
                            <div class="col-lg-4 mt-2">
                                <a id="btn_alc" name="btn_alc" onclick="guardar_nuevo_linea_credito()" 
                                   class="btn btn-success text-white">
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