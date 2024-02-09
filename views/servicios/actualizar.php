<h1 class="nombre-pagina">Actualizar servicio</h1>
<p class="descripcion-pagina">Modifica los valores del formulario</p>

<?php
    include_once __DIR__ . '/../templates/barra.php';
    include_once __DIR__ . '/../templates/alertas.php';
?>
<!--No ponemos el action ya que dario errores por el queryString que pusimos en la url en el archivo
/servicios/index.php en el input de actualizar-->
<form method="POST" class="formulario">

    <?php
        include_once __DIR__ .'/formulario.php';
    ?>

    <input type="submit" class="boton" name="" id="" value="Actualizar servicio">
</form>