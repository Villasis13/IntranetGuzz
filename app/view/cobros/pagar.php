<!--Contenido-->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-hand-holding-usd me-2"></i>Pago de Deuda
        </h1>
        <a href="<?= _SERVER_ ?>prestamos/prestamos" class="btn btn-success">
            <i class="fa fa-arrow-left me-2"></i>Volver
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-7">
                    <div class="info-group mb-3">
                        <label class="fw-bold">Nombre:</label>
                        <input type="hidden" id="id_prestamo" name="id_prestamo" value="<?= $id_prestamo ?>">
                        <?= $cliente_data->cliente_nombre . ' ' .
						$cliente_data->cliente_apellido_paterno . ' ' . $cliente_data->cliente_apellido_materno ?>
                    </div>
                    <div class="info-group mb-3">
                        <label class="fw-bold">DNI:</label>
                        <?= $cliente_data->cliente_dni ?>
                    </div>
                    <div class="info-group mb-3">
                        <label class="fw-bold">Monto Inicial:</label>
                        <span class="text-success">S/ <?= $prestamos_data->prestamo_monto +  ($prestamos_data->prestamo_monto *$prestamos_data->prestamo_interes / 100)?></span>
                    </div>
                    <div class="info-group mb-3">
                        <label class="fw-bold">Resta por Pagar:</label>
                        <span class="badge bg-danger"><?= !empty( $prestamos_data->prestamo_saldo_pagar)? "S/. ".$prestamos_data->prestamo_saldo_pagar : "Sin saldo restante"  ?>
                            <input id="input_resta_por_pagar" name="input_resta_por_pagar" type="hidden" value="<?= $valor_resta_por_pagar ?>">
                        </span>
                    </div>
                    <div class="info-group mb-3">
                        <label class="fw-bold">Aplicar Descuento</label>
                        <input onchange="aplicar_descuento()" type="radio" name="desc" id="descSi"><label for="descSi">Si</label>
                        <input onchange="aplicar_descuento()" type="radio" name="desc" checked id="descNo"><label for="descNo">No</label>
                    </div>
                    <div style="display: none" id="div_descontar" class="info-group mb-3">
                        <div class="d-flex align-items-center justify-content-between w-100">
                            <label class="fw-bold me-3 mb-0">Ingrese Cantidad a Descontar:</label>

                            <input class="form-control w-25 me-3"
                                   type="text"
                                   onkeyup="validar_numeros_decimales_dos(this.id)"
                                   id="descontar_cantidad"
                                   name="descontar_cantidad">

                            <a onclick="preguntar('¿Está seguro que desea aplicar este descuento?','guardar_aplicar_descuento','SI','NO',<?= $id_prestamo ?>)" style="cursor: pointer;"
                               class="btn btn-sm btn-primary text-white">Aplicar Descuento
                            </a>
                        </div>
                    </div>

                </div>

                <!-- Detalles Préstamo -->
                <div class="col-md-5">
                    <div class="info-group mb-3">
                        <label class="fw-bold">Interés Aplicado:</label>
                        <?= $prestamos_data->prestamo_interes ?> %
                    </div>
                    <div class="info-group mb-3">
                        <label class="fw-bold">Tipo de Pago:</label>
                        <span class="badge bg-info"><?= $prestamos_data->prestamo_tipo_pago ?></span>
                    </div>
                    <div class="info-group mb-3">
                        <label class="fw-bold">Fecha del Préstamo:</label>
                        <?= $prestamos_data->prestamo_fecha ?>
                    </div>
                    <div class="info-group mb-3">
                        <label class="fw-bold">Fecha Vencimiento:</label>
                        <span class="badge bg-danger">
                            <?php
							function calcularProxCobro(string $tipo, string $fecha): string {
								$dt = new DateTime($fecha);

								switch (strtolower(trim($tipo))) {
									case 'diario':
										$dt->add(new DateInterval('P1D')); // +1 día
										break;
									case 'semanal':
										$dt->add(new DateInterval('P1W')); // +1 semana
										break;
									case 'mensual':
										$dt->add(new DateInterval('P1M')); // +1 mes (respeta fin de mes)
										break;
									default:
										// Si no coincide, no modifica la fecha
										break;
								}

								return $dt->format('Y-m-d');
							}
                            
                            if($prestamos_data->prestamo_fecha == $prestamos_data->prestamo_prox_cobro){
								
								$fechaPrestamo = (new DateTime($prestamos_data->prestamo_fecha))->format('Y-m-d');
								$proxCobro     = (new DateTime($prestamos_data->prestamo_prox_cobro))->format('Y-m-d');
								$tipoPago      = $prestamos_data->prestamo_tipo_pago;
                                
								if ($fechaPrestamo == $proxCobro) {
									echo calcularProxCobro($tipoPago, $fechaPrestamo);
								} else {
									echo $proxCobro;
								}
                            }else{
                                echo $prestamos_data->prestamo_prox_cobro;
                            }
                            ?>
                        </span>
                    </div>
                </div>
            </div>


            <div class="row g-3 mb-4 align-items-end">
                <div class="col-md-2">
                    <div class="form-floating">
                        <input id="pago_recepcion" name="pago_recepcion" type="text" class="form-control" placeholder=" ">
                        <label>Recepción</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-floating">
                        <select id="pago_metodo" name="pago_metodo" class="form-select" placeholder=" ">
                            <option value="">Seleccione</option>
                            <option value="transferencia">Transferencia</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="plin">Plin</option>
                            <option value="yape">Yape</option>
                        </select>
                        <label style="margin-left: -3px;">Método de Pago</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <input id="pago_recepcion_yp" name="pago_recepcion_yp" type="text" class="form-control" placeholder=" ">
                        <label>Titular del yape/plin</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <select class="form-select" id="id_pago" name="id_pagoid_pago">
                            <option value="">Seleccionar</option>
                            <?php foreach ($cuotas as $c) { ?>
                                <option value="<?= $c->id_pago_diario?>">
                                    <?= $c->pago_diario_monto . '/' . $c->pago_diario_fecha ?>
                                </option>
                            <?php } ?>
                        </select>
                        <label>Cuotas</label>
                    </div>
                </div>
            </div>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card border-primary">
                            <div class="card-header bg-gray" style="color: white;padding: 13px;">
                                Garantías
                            </div>
                            <br>
                            <div class="card-body" style="padding: 8px;">
                                <p class="mb-1">
                                    <strong>Garantía:</strong>
                                    <?= $prestamos_data->prestamo_garantia ?>
                                </p>
                                <div class="mt-2">
                                    <label class="fw-bold">Garantes:</label>
                                    <ul class="list-unstyled">
                                        <li class="text-muted"><?= $garante->cliente_nombre .' ' . $garante->cliente_apellido_paterno ?></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($prestamos_data->prestamo_num_cuotas == $total_pagos_cuenta->cuenta) { ?>
                    <div class="col-md-6">
                        <div class="form-floating mb-3" style="height: 50%;">
                            <label><b>Sin pagos pendientes.</b></label>
                        </div>
                    </div>
                    <?php } else { ?>

                    <div class="col-md-6">
                        <div class="form-floating mb-3" style="height: 50%;">
                            <input value="<?= $prestamos_data->prestamo_prox_cobro ?>" type="date" class="form-control w-100" style="height: 86%;" readonly>
                            <label>Fecha Esperada</label>
                        </div>
                        <div class="form-floating mb-3" style="height: 50%;">
                            <input id="prestamo_prox_cobro" name="prestamo_prox_cobro" value="<?= date('Y-m-d') ?>" type="date" class="form-control w-100" style="height: 86%;">
                            <label>Próximo Cobro</label>
                        </div>
                    </div>

                    <?php } ?>

                </div>

                <div class="text-center mt-4">
                    <button onclick="guardar_pago_prestamo()" type="submit" class="btn btn-lg btn-primary px-5">
                        <i class="fa fa-check-circle me-2"></i>Confirmar Pago
                    </button>
                </div>
            
        </div>
    </div>
</div>
<style>
    .info-group label {
        width: 150px;
        color: #4a5568;
    }
    .card-header {
        font-weight: 500;
    }
</style>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>prestamos.js"></script>
