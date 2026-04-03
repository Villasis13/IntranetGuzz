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
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Movimientos de caja</h6>
                </div>
                <div class="card-body">
                    <?php if($ultima_caja->estado_caja != 1): ?>
                        <div class="text-center py-5">
                            <i class="fa fa-lock fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay una caja abierta</h5>
                            <p class="text-muted">Debes aperturar la caja para ver los movimientos del día.</p>
                        </div>
                    <?php elseif(empty($movimientos_caja)): ?>
                        <div class="text-center py-5">
                            <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Sin movimientos aún</h5>
                            <p class="text-muted">La caja está abierta pero no registra movimientos todavía.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped text-center" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Monto</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $con = 1; foreach($movimientos_caja as $mov): ?>
                                    <tr>
                                        <td><?= $con ?></td>
                                        <td><?= $mov->caja_movimiento_fecha ?></td>
                                        <td>
                                            <?php if($mov->caja_movimiento_tipo == 1): ?>
                                                <span class="badge badge-success" style="color: #697a8d">Ingreso</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger" style="color: #697a8d">Egreso</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($mov->caja_movimiento_tipo == 2): ?>
                                                <span class="text-danger">-S/. <?= number_format($mov->caja_movimiento_monto, 2) ?></span>
                                            <?php else: ?>
                                                <span class="text-success">S/. <?= number_format($mov->caja_movimiento_monto, 2) ?></span>
                                            <?php endif; ?>
                                        </td>                                    </tr>
                                    <?php $con++; endforeach; ?>
                                </tbody>
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