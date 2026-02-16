<div>
    <div class="col-12 px-3">
        <div class="card shadow mb-3 my-3">
            <div class="card-body mt-3" >
                <div class="card-header py-3 bg-primary">
                    <h5 class="m-0 font-weight-bold text-white">Pagos del reporte</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered w-100">
                        <thead class="text-center">
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Monto</th>
                            <th>Fecha/Hora</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $style = "";
                        foreach ($reporte as $rp){
                            $c=1;
                            ?>
                            <tr class="text-center">
                                <td><?=$c ?></td>
                                <td><?=$rp->cliente_nombre. "  ". $rp->cliente_apellido_paterno ?></td>
                                <td><?=$rp->pago_monto ?></td>
                                <td><?=$rp->pago_fecha ?></td>
                            </tr>
                        <?php
                        $a++;
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(!empty($egresos)){ ?>

    <div>
        <div class="col-12 px-3">
            <div class="card shadow mb-3 my-3">
                <div class="card-body mt-3" >
                    <div class="card-header py-3 bg-primary">
                        <h5 class="m-0 font-weight-bold text-white">Prestamos del reporte</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered w-100">
                            <thead class="text-center">
                            <tr>
                                <th>#</th>
                                <th>Cliente</th>
                                <th>Monto (Con Intereses)</th>
                                <th>Fecha/Hora</th>
                                <th>Próximo cobro</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $style = "";
                            foreach ($egresos as $eg){
                                $c=1;
                                ?>
                                <tr class="text-center">
                                    <td><?=$c ?></td>
                                    <td><?=$eg->cliente_nombre. "  ". $rp->cliente_apellido_paterno ?></td>
                                    <td><?=$eg->prestamo_monto + $eg->prestamo_monto_interes ?></td>
                                    <td><?=$eg->prestamo_fecha ?></td>
                                    <td><?=$eg->prestamo_prox_cobro ?></td>
                                </tr>
                                <?php
                                $a++;
                            } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php } ?>
