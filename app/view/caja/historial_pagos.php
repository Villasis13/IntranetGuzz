<!-- MODAL PARA EDITAR MONTOS -->
<div class="modal fade" id="editar_monto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form class="modal-content" onsubmit="return false;">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleAlumno">Editar Monto de Ingreso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    <!-- NOMBRES -->
                    <div class="col-md-6">
                        <label class="form-label">Monto Actual/Por Cambiar</label>
                        <input type="number" id="pago_monto" name="pago_monto" class="form-control"  required>
                        <input type="hidden" id="id_pago" name="id_pago" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contraseña</label>
                        <input type="password" id="contrasenha" name="pago_monto" class="form-control"  required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btn-agregar-monto" onclick="guardar_monto()">
                    <i class="fa fa-save fa-sm text-white-50"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>


<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 mt-3">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h5 class="mb-3 font-weight-bold text-primary">Historial ingresos de hoy (<?=$fecha?>)</h5>
                    <div class="table-responsive">
                        <table class="table table-striped" width="100%">
                            <thead class="text-center">
                            <tr>
                                <th>#</th>
                                <th>Ingreso Monto</th>
                                <th>Hora</th>
                                <th>Metodo de Pago</th>
                                <th>Recepción (Cajero/a)</th>
                                <th>Acción</th>
<!--                                <th>Ver reporte</th>-->
                            </tr>
                            </thead>
                            <tbody>
							<?php
                            $con = 1;
							foreach ($pagos_hoy as $cg){
								?>
                                <tr class="text-center">
                                    <td><?= $con?></td>
                                    <td><?= $cg->pago_monto?></td>
                                    <td><?= date('H:i',strtotime($cg->pago_fecha)) ?></td>
                                    <td><?= $cg->pago_metodo ?> </td>
                                    <td><?= $cg->pago_recepcion ?> </td>
                                    <td>
                                        <button data-toggle="modal"
                                                data-target="#editar_monto"
                                                onclick="pago_x_id(<?= $cg->id_pago?>)"
                                                class="btn btn-xs btn-warning btne">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    </td>


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
            <a href="<?= _SERVER_ ?>Caja/inicio" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Regresar
            </a>
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