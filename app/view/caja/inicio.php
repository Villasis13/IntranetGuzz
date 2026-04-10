<!-- MODAL AÑADIR DINERO A CAJA -->
<div class="modal fade" id="dinero_caja" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form class="modal-content" onsubmit="return false;">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleAlumno">Editar Monto de Ingreso</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    <!-- NOMBRES -->
                    <div class="col-md-6">
                        <label class="form-label">Monto a añadir</label>
                        <input type="number" id="monto_caja" name="monto_caja" class="form-control"  required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contraseña</label>
                        <input type="password" id="contrasenha" name="pago_monto" class="form-control"  required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btn-agregar-monto" onclick="guardar_movimiento_caja()">
                    <i class="fa fa-save fa-sm text-white-50"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12 col-md-8 col-lg-4">
			<div class="row">
				<div class="col-lg-12">
					<div class="card">
						<div class="card-body">
                            <?php


                            if($ultima_caja->estado_caja == 1){
                            ?>
                                <h4 class="text-success">CAJA ABIERTA</h4>
                                <h5>Tiene en caja: S/.<?= $monto_caja_abierta ?></h5>
                                <h5>Abierto el: <?= $fecha_caja ?></h5>
                                <!--<a onclick="habilitar_input_editar_monto()" class="btn btn-sm btn-warning text-white">
                                    <i class="fa fa-pencil"></i>
                                    Editar Monto
                                </a> -->
                                 <input id="input_editar_monto" name="input_editar_monto" style="display: none"
                                       class="form-control w-25"
                                       placeholder="Ingrese nuevo monto"
                                       onkeyup="validar_numeros_decimales_dos(this.id)"
                                >
                                <!-- <a href="<?= _SERVER_ ?>Caja/historial_pagos" class="btn btn-sm btn-info text-white ml-2 ">
                                    <i class="fa fa-list"></i>
                                    Historial Pagos Hoy
                                </a> -->
                                <button type="button"
                                        data-toggle="modal"
                                        data-target="#dinero_caja"
                                        onclick="limpiar_modal_alumno()"
                                        class="btn btn-sm btn-primary text-white ml-2">
                                    <i class="fa fa-plus"></i> Añadir a caja
                                </button>
                                <button type="button"
                                        id="btn-cerrar-caja"
                                        class="btn btn-sm btn-danger text-white ml-2"
                                        onclick="cerrar_caja(<?= $ultima_caja->id_caja ?>)">
                                    <i class="fa fa-lock"></i> Cerrar caja
                                </button>
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
                                                onclick="preguntar_apertura_caja(<?=$ultima_caja->monto_caja?>)">Apertura rápida
                                        </button>
                                    </div>
                                    <div class="col-lg-2">
                                        <button id="btn-abrir_caja_manual"
                                                class="form-control btn-info mt-4"
                                                onclick="gestionar_caja()">
                                            Abrir con monto manual
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
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Detalle de Arqueo de Caja</h6>
                    <?php if($ultima_caja->estado_caja == 1): ?>
                        <span class="badge bg-success text-white px-3 py-2">Caja Activa</span>
                    <?php endif; ?>
                </div>

                <div class="card-body">
                    <?php if($ultima_caja->estado_caja != 1): ?>
                        <div class="text-center py-5">
                            <i class="fa fa-lock fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay una caja abierta</h5>
                            <p class="text-muted">Debes aperturar la caja para ver el arqueo actual.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm text-center align-middle" width="100%">
                                <thead class="bg-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Usuario / Cliente</th>
                                    <th>Descripción / Método</th>
                                    <th class="text-success">Ingresos</th>
                                    <th class="text-danger">Egresos</th>
                                </tr>
                                </thead>
                                <tbody>

                                <tr class="table-secondary">
                                    <td colspan="6" class="text-left font-weight-bold">Apertura de Caja</td>
                                </tr>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($fecha_caja)) ?></td>
                                    <td><?= date('H:i:s', strtotime($fecha_caja)) ?></td>
                                    <td><?= $usuario_actual ?></td>
                                    <td>Saldo del día Anterior / Monto inicial</td>
                                    <td class="text-success font-weight-bold">S/ <?= number_format($ultima_caja->monto_caja, 2) ?></td>
                                    <td></td>
                                </tr>

                                <tr class="table-secondary">
                                    <td colspan="6" class="text-left font-weight-bold">Pago de Cuotas</td>
                                </tr>
                                <?php
                                $suma_pagos = 0;
                                if(!empty($pagos_caja)):
                                    foreach($pagos_caja as $pago):
                                        $suma_pagos += $pago->pago_monto;
                                        ?>
                                        <tr>
                                            <td><?= date('d/m/Y', strtotime($pago->pago_fecha)) ?></td>
                                            <td><?= date('H:i:s', strtotime($pago->pago_fecha)) ?></td>
                                            <td><?= $pago->cliente_nombre . ' ' . $pago->cliente_apellido_paterno ?></td>
                                            <td><?= ucfirst($pago->pago_metodo) ?></td>
                                            <td class="text-success">S/ <?= number_format($pago->pago_monto, 2) ?></td>
                                            <td></td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                    <tr><td colspan="6" class="text-muted fst-italic">No se han registrado pagos en este turno.</td></tr>
                                <?php endif; ?>

                                <tr class="table-secondary">
                                    <td colspan="6" class="text-left font-weight-bold">Préstamos</td>
                                </tr>
                                <?php
                                $suma_prestamos = 0;
                                if(!empty($prestamos_caja)):
                                    foreach($prestamos_caja as $prestamo):
                                        $suma_prestamos += $prestamo->prestamo_monto;
                                        ?>
                                        <tr>
                                            <td><?= date('d/m/Y', strtotime($prestamo->prestamo_fecha)) ?></td>
                                            <td><?= date('H:i:s', strtotime($prestamo->prestamo_fecha)) ?></td>
                                            <td><?= $prestamo->cliente_nombre . ' ' . $prestamo->cliente_apellido_paterno ?></td>
                                            <td>Préstamo <?= ucfirst($prestamo->prestamo_tipo_pago) ?></td>
                                            <td></td>
                                            <td class="text-danger">S/ <?= number_format($prestamo->prestamo_monto, 2) ?></td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                    <tr><td colspan="6" class="text-muted fst-italic">No se han otorgado préstamos en este turno.</td></tr>
                                <?php endif; ?>

                                <tr class="table-secondary">
                                    <td colspan="6" class="text-left font-weight-bold">Ingreso de Monto Manual</td>
                                </tr>
                                <?php
                                $suma_ingresos_manuales = 0;
                                if(!empty($ingresos_manuales)):
                                    foreach($ingresos_manuales as $mov):
                                        $suma_ingresos_manuales += $mov->caja_movimiento_monto;
                                        ?>
                                        <tr>
                                            <td><?= date('d/m/Y', strtotime($mov->caja_movimiento_fecha)) ?></td>
                                            <td><?= date('H:i:s', strtotime($mov->caja_movimiento_fecha)) ?></td>
                                            <td><?= $usuario_actual ?></td>
                                            <td>Añadido a Caja Manualmente</td>
                                            <td class="text-success">S/ <?= number_format($mov->caja_movimiento_monto, 2) ?></td>
                                            <td></td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                    <tr><td colspan="6" class="text-muted fst-italic">No hay ingresos manuales registrados.</td></tr>
                                <?php endif; ?>

                                </tbody>

                                <tfoot class="bg-light">
                                <?php
                                // Cálculo final para el cuadre
                                $monto_apertura = $ultima_caja->monto_apertura_caja; // Asegúrate de que esta sea la variable del saldo inicial
                                $total_ingresos = $suma_pagos + $suma_ingresos_manuales;
                                $total_egresos = $suma_prestamos;

                                // Saldo Actual = Apertura + Ingresos - Egresos
                                $saldo_actual_calculado = $monto_apertura + $total_ingresos - $total_egresos;
                                ?>
                                <tr>
                                    <td colspan="3" class="border-0"></td>
                                    <td class="text-right font-weight-bold">Total Ingresos Turno:</td>
                                    <td class="text-success font-weight-bold">S/ <?= number_format($total_ingresos, 2) ?></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="border-0"></td>
                                    <td class="text-right font-weight-bold">Total Egresos Turno:</td>
                                    <td></td>
                                    <td class="text-danger font-weight-bold">S/ <?= number_format($total_egresos, 2) ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="border-0 bg-white"></td>
                                    <td class="text-right text-primary h5 font-weight-bold py-3">Saldo Actual en Caja:</td>
                                    <td colspan="2" class="text-center text-primary h5 font-weight-bold py-3 bg-white" style="border: 2px solid #4e73df;">
                                        S/ <?= number_format($saldo_actual_calculado, 2) ?>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php endif; ?>
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