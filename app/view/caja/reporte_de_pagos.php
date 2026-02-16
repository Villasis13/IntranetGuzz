<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 mt-3">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h5 class="mb-3 font-weight-bold text-primary">Reporte de pagos – Última Caja Cerrada</h5>
                    <div class="table-responsive">
                        <table class="table table-striped" width="100%">
                            <thead class="text-center">
                            <tr>
                                <th>#</th>
                                <th>Pago Monto</th>
                                <th>Fecha/Hora</th>
                                <th>Metodo de Pago</th>
                                <th>Recepción (Cajero/a)</th>
                            </tr>
                            </thead>
                            <tbody>
							<?php
                            $con = 1;
							foreach ($reporte_pagos as $cg){
								?>
                                <tr class="text-center">
                                    <td><?= $con?></td>
                                    <td><?= $cg->pago_monto?></td>
                                    <td><?= date('Y-m-d',strtotime($cg->pago_fecha)) ?>
                                    <small><?= date('H:i',strtotime($cg->pago_fecha)) ?></small>
                                    </td>
                                    <td><?= $cg->pago_metodo ?> </td>
                                    <td><?= $cg->pago_recepcion ?> </td>
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

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 mt-3">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h5 class="mb-3 font-weight-bold text-primary">Reporte de Prestamos – Última Caja Cerrada</h5>
                    <div class="table-responsive">
                        <table class="table table-striped" width="100%">
                            <thead class="text-center">
                            <tr>
                                <th>#</th>
                                <th>Prestamo Monto</th>
                                <th>Fecha/Hora</th>
                                <th>Prestamo Monto Interes</th>
                                <th>Prestamo Garantía</th>
                                <th>Prestamo Fecha de Pago</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $con = 1;
                            foreach ($reporte_prestamos as $cg){
                                ?>
                                <tr class="text-center">
                                    <td><?= $con?></td>
                                    <td><?= $cg->prestamo_monto?></td>
                                    <td><?= date('Y-m-d',strtotime($cg->prestamo_fecha)) ?>
                                        <small><?= date('H:i',strtotime($cg->prestamo_fecha)) ?></small>
                                    </td>
                                    <td><?= $cg->prestamo_monto_interes ?> </td>
                                    <td><?= $cg->prestamo_garantia ?> </td>
                                    <td><?= date('Y-m-d',strtotime($cg->prestamo_prox_cobro)) ?>
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

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 mt-3">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h5 class="mb-3 font-weight-bold text-primary">Reporte de Movimientos – Última Caja Cerrada</h5>
                    <div class="table-responsive">
                        <table class="table table-striped" width="100%">
                            <thead class="text-center">
                            <tr>
                                <th>#</th>
                                <th>Movimiento Monto</th>
                                <th>Fecha/Hora</th>
                                <th>Tipo de movimiento</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $con = 1;
                            foreach ($movimientos_caja as $cg){
                                ?>
                                <tr class="text-center">
                                    <td><?= $con?></td>
                                    <td><?= $cg->caja_movimiento_monto?></td>
                                    <td><?= date('Y-m-d',strtotime($cg->pago_fecha)) ?>
                                        <small><?= date('H:i',strtotime($cg->pago_fecha)) ?></small>
                                    </td>
                                    <td><?=  ($cg->caja_movimiento_tipo ==1)? "Ingreso de dinero" : "Salida de Dinero" ?> </td>
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