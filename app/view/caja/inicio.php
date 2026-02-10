<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12 col-md-8 col-lg-4">
			<div class="row">
				<div class="col-lg-12">
					<div class="card">
						<div class="card-body">
                            <?php
                            $fecha_actual = date('Y-m-d');
                            $fecha_caja_sin_hora = date('Y-m-d', strtotime($fecha_caja));
                            if($fecha_caja_sin_hora == $fecha_actual){
                            ?>
                                <h4 class="text-success">CAJA ABIERTA</h4>
                                <h5>Tiene en caja: S/.<?= $monto_caja_abierta ?></h5>
                                <h5>Abierto el: <?= $fecha_caja ?></h5>
                                <a onclick="habilitar_input_editar_monto()" class="btn btn-sm btn-warning text-white">
                                    <i class="fa fa-pencil"></i>
                                    Editar Monto
                                </a>
                                <input id="input_editar_monto" name="input_editar_monto" style="display: none" 
                                       class="form-control w-25"
                                       placeholder="Ingrese nuevo monto"
                                       onkeyup="validar_numeros_decimales_dos(this.id)"
                                >
                                <a style="display: none" id="btn_editar" onclick="guardar_nuevo_monto()" class="btn btn-sm btn-success text-white">
                                    <i class="fa fa-save"></i>
                                </a>
                            <?php
                            }else{
                            ?>
                                <input type="hidden" id="id_caja">
                                <h4  style="color: red">CAJA CERRADA</h4>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label for="fecha">Fecha</label>
                                        <input id="fecha" class="form-control" readonly>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="caja_monto">Monto en soles:</label>
                                        <input onkeyup="validar_numeros_decimales_dos(this.id)" type="text" id="caja_monto" name="caja_monto" class="form-control">
                                    </div>
                                    <div class="col-lg-2">
                                        <button id="btn-abrir_caja" data-toggle="modal" class="form-control btn-success mt-4"  
                                                onclick="gestionar_caja()">Abrir caja
                                        </button>
                                    </div>
                                    <div class="col-lg-3">
                                        <button id="btn-abrir_caja_ultimo_monto" data-toggle="modal" 
                                                class="form-control btn-warning mt-4"  
                                                onclick="gestionar_caja_ultimo_monto()">Abrir con último monto
                                        </button>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    <div class="row">
        <div class="col-lg-12 mt-3">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" width="100%">
                            <thead class="text-center">
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Monto de apertura</th>
                                <th>Ingresos del día</th>
                                <th>Egresos del día</th>
                                <th>Gastos del día</th>
                                <th>Saldo del día</th>
<!--                                <th>Ver reporte</th>-->
                            </tr>
                            </thead>
                            <tbody>
							<?php
                            $con = 1;
							foreach ($datos_caja_general as $cg){
								?>
                                <tr class="text-center">
                                    <td><?= $con?></td>
                                    <td><?= date("Y-m-d", strtotime($cg->fecha_caja))?></td>
                                    <td><?= $cg->monto_apertura_caja?></td>
                                    <td>
                                        <?php
										$ingresos_hoy = $this->cobros->prestamos_hoy_fecha(date("Y-m-d", strtotime($cg->fecha_caja)))->total;
                                        ?>
                                        <?= $ingresos_hoy ?>
                                    </td>
                                    <td>
										<?php
										$egresos_hoy = $this->prestamos->egresos_hoy_fecha(date("Y-m-d", strtotime($cg->fecha_caja)))->total;
										?>
										<?= $egresos_hoy ?>
                                    </td>
                                    <td>
                                        0.00
                                    </td>
                                    <td>
                                        <?= $cg->monto_caja ?>
                                    </td>
<!--                                    <td>-->
<!--                                        -->
<!--                                    </td>-->
                                </tr>
								<?php
								$con++;
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
    </div>
</div>

<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>caja.js"></script>

<script>
    function actualizarHora() {
        const fechaCampo = document.getElementById("fecha");
        const fechaActual = new Date();
        const hora = fechaActual.getHours().toString().padStart(2, '0');
        const minutos = fechaActual.getMinutes().toString().padStart(2, '0');
        const segundos = fechaActual.getSeconds().toString().padStart(2, '0');
        const fechaFormateada = `${fechaActual.getFullYear()}-${(fechaActual.getMonth() + 1).toString().padStart(2, '0')}-${fechaActual.getDate().toString().padStart(2, '0')} ${hora}:${minutos}:${segundos}`;
        fechaCampo.value = fechaFormateada;
    }

    // Actualizar la hora cada segundo (1000 ms)
    setInterval(actualizarHora, 1000);

    // Llamar a la función para mostrar la hora actual
    actualizarHora();
</script>