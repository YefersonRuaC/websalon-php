<h1 class="nombre-pagina">Panel de administracion</h1>

<?php
    include_once __DIR__ . '/../templates/barra.php';
?>

<h2>Buscar citas</h2>

<div class="busqueda">
    <form action="" class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input 
                type="date" 
                name="fecha" 
                id="fecha"
                value="<?= $fecha; ?>">
        </div>
    </form>
</div>

<?php 
    if(count($citas) === 0) {
        echo '<h2>No hay citas en esta fecha</h2>';
    } 
?>

<div id="citas-admin">
    <ul class="citas">
        <?php 
            $idCita = 0;//Iniciamos esta vrble para que no nos tire un undefined
            foreach($citas as $key => $cita){//Iterando sobre cada una de las citas 
                if($idCita !== $cita->id) {//No repetira el id. Si hay varios servicios con el mismo id, no mostrara
                    //el id en todas la ocasiones. Cuando itere y cambie el valor del id, hay imprimira el siguiente
                    $total = 0;//La vrble total inicia en cero
        ?>
        <li>
            <p>ID: <span><?= $cita->id; ?></span></p>
            <p>Hora: <span><?= $cita->hora; ?></span></p>
            <p>Cliente: <span><?= $cita->cliente; ?></span></p>
            <p>Email: <span><?= $cita->email; ?></span></p>
            <p>Telefono: <span><?= $cita->telefono; ?></span></p>

            <h3>Servicios</h3>
        <?php
                $idCita = $cita->id;
            } 
                $total += $cita->precio;
        ?>
            <p class="servicio"><?= $cita->servicio . " $" . $cita->precio; ?></p>
        <!--</li>--><!--Comentamos para que HTML lo cierre automaticamente y no tener un error con los estilos-->
        <?php
            $actual = $cita->id;//Nos retorna el id en el cual nos encontramos
            $proximo = $citas[$key +1]->id ?? 0;//Es el indicie en el arreglo de la BD 

            if(esUltimo($actual, $proximo)) { ?>
                <p class="total">Total a pagar: <span>$<?= $total; ?></span></p>

                <form action="/api/eliminar" method="POST">
                    <input 
                        type="hidden" 
                        name="id" 
                        id="id" 
                        value="<?= $cita->id; ?>"><!--Pasamos el id que se va eliminar-->

                    <input 
                        class="boton-eliminar" 
                        type="submit" 
                        name="" 
                        id="" 
                        value="Eliminar">

                </form>

        <?php   }   ?>

        <?php   }   ?>
    </ul>
</div>

<?php
    $script = "<script src='build/js/buscador.js'></script>";
?>