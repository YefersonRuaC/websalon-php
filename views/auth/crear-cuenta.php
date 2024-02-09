<h1 class="nombre-pagina">Crear cuenta</h1>
<p class="descripcion-pagina">Llena el formulario para crear una nueva cuenta</p>

<?php 
    //Recordemos que con __DIR__ hacemos referencia al archivo que estamos actualmente y desde ahi podemos 
    //apuntar mas facilmente al archivo que requerimos
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form class="formulario" action="/crear-cuenta" method="POST">

    <div class="campo">
        <label for="nombre">Nombre</label>
        <input 
            type="text" 
            name="nombre" 
            id="nombre" 
            placeholder="Ingresa tu nombre"
            value="<?= sanitizar($usuario->nombre); ?>">
    </div>

    <div class="campo">
        <label for="apellido">Apellido</label>
        <input 
            type="text" 
            name="apellido" 
            id="apellido" 
            placeholder="Ingresa tu apellido"
            value="<?= sanitizar($usuario->apellido); ?>">
    </div>

    <div class="campo">
        <label for="telefono">Telefono</label>
        <input 
            type="tel" 
            name="telefono" 
            id="telefono" 
            placeholder="Ingresa tu telefono"
            value="<?= sanitizar($usuario->telefono); ?>">
    </div>

    <div class="campo">
        <label for="email">Email</label>
        <input  
            type="email" 
            name="email" 
            id="email" 
            placeholder="Ingresa tu email"
            value="<?= sanitizar($usuario->email); ?>">
    </div>

    <div class="campo">
        <label for="password">Password</label>
        <input 
            type="password" 
            name="password" 
            id="password" 
            placeholder="Ingresa tu password">
    </div>
    
    <input class="boton" type="submit" name="" id="" value="Crear cuenta">

</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia sesion</a>
    <a href="/olvide">¿Olvidaste tu password?</a>
</div>