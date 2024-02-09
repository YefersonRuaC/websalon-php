<h1 class="nombre-pagina">Crear nueva cita</h1>
<p class="descripcion-pagina">Elige tus servicios e ingresa tus datos</p>

<?php
    include_once __DIR__ . '/../templates/barra.php';
?>

<div id="app">
    <nav class="tabs"><!--Podemos hacer atributos personalizados en html con data-(cualquier palabra)=""-->
        <button class="actual" type="button" data-paso="1">Servicios</button>
        <button type="button" data-paso="2">informacion cita</button>
        <button type="button" data-paso="3">Resumen</button>
    </nav>

    <div class="seccion" id="paso-1">
        <h2>Servicios</h2>
        <p class="text-center">Elije tus servicios a continuacion</p>
    <!--
        Este div lo dejaremos por el momento vacio porque con javascript vamos a consultar la BD en php,
        la vamos a exporta a json y vamos a insertar aqui los datos
    -->
        <div class="listado-servicios" id="servicios"></div>
    </div>
    <div class="seccion" id="paso-2">
        <h2>Tus datos y cita</h2>
        <p class="text-center">Coloca tus datos y la fecha de tu cita</p>

        <form action="" class="formulario">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input 
                    type="text" 
                    name="" 
                    id="nombre"
                    placeholder="Ingresa tu nombre"
                    value="<?= $nombre; ?>"
                    disabled>
            </div>
            <div class="campo">
                <label for="fecha">Fecha</label>
                <input 
                    type="date" 
                    name="" 
                    id="fecha"
                    min="<?= date('Y-m-d', strtotime('+1 day') ); ?>">
            </div>
            <div class="campo">
                <label for="hora">Hora</label>
                <input 
                    type="time" 
                    name="" 
                    id="hora"
                    value="">
            </div>
            <input type="hidden" name="" id="id" value="<?= $id; ?>">
        </form>
    </div>
    <div class="seccion contenido-resumen" id="paso-3">
        <h2>Resumen</h2>
        <p>Verifica que la informacion sea correcta</p>
    </div>
    <div class="paginacion">
        <button
            id="anterior"
            class="boton"    
        >&laquo; Anterior</button><!--&laquo; añade las fechitas en el boton (left)-->
        <button
            id="siguiente"
            class="boton"    
        >Siguiente &raquo;</button><!--&laquo; añade las fechitas en el boton (right)-->
    </div>
</div>

<?php
    //Podemos incluir mas de un script distinto en cada archivo
    $script = "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script src='build/js/app.js'></script>
    ";
?>