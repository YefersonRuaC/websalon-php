<div class="barra">
    <p>Hola: <?= $nombre ?? ''; ?></p>

    <a href="/logout" class="boton">Cerrar sesion</a>
</div>

<?php 
    if(isset($_SESSION['admin'])) { //Si el usuario es de tipo admin, le aparecera esta parte del html
?>
    <div class="barra-servicios">
        <a class="boton" href="/admin">Ver citas</a>
        <a class="boton" href="/servicios">Ver servicios</a>
        <a class="boton" href="/servicios/crear">Nuevo servicio</a>
    </div>
<?php
    } //else {
        //echo 'NO es admin';
    //}
?>