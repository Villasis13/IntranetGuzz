<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12 col-md-8 col-lg-4">
			<div class="row">
				<div class="col-lg-4">
					<div class="card">
						<div class="card-body">
                            <?php
                            $fecha_actual = date('Y-m-d');
                            $fecha_caja_sin_hora = date('Y-m-d', strtotime($fecha_caja));
                            if($fecha_caja_sin_hora == $fecha_actual){
                            ?>
                                <h2 class="text-success">CAJA ABIERTA</h2>
                                <h4>Tiene en caja: S/.<?= $monto_caja_abierta ?></h4>
                                <h4>Abierto el: <?= $fecha_caja ?></h4>
                            <?php
                            }else{
                            ?>
                                <input type="hidden" id="id_caja">
                                <h2 style="color: red">CAJA CERRADA</h2>
                                <label for="fecha">Fecha</label>
                                <input id="fecha" class="form-control" readonly>

                                <label class="mt-4" for="caja_monto">Monto en soles:</label>
                                <input type="text" id="caja_monto" name="caja_monto" class="form-control mt-1">

                                <button id="btn-abrir_caja" data-toggle="modal" href="#abrir_caja" class="form-control btn-success mt-4" onclick="gestionar_caja()">Abrir caja</button>
                            <?php
                            }
                            ?>
						</div>
					</div>
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