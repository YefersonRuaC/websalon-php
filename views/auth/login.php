<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sesion con tus datos</p>

<?php 
    //Recordemos que con __DIR__ hacemos referencia al archivo que estamos actualmente y desde ahi podemos 
    //apuntar mas facilmente al archivo que requerimos
    include_once __DIR__ . "/../templates/alertas.php";//Estamos añadiendo nuestras alertas
?>

<form class="formulario" method="POST" action="/">
    <div class="campo">
        <label for="email">Email</label>
        <input 
            type="email" 
            name="email" 
            id="email" 
            placeholder="Ingresa tu email">
    </div>

    <div class="campo">
        <label for="password">Password</label>
        <input 
            type="password" 
            name="password" 
            id="password" 
            placeholder="Ingresa tu password">
    </div>

    <input class="boton" type="submit" value="Iniciar sesion" name="" id="">

</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aun no tienes cuenta? ¡Crea una!</a>
    <a href="/olvide">¿Olvidaste tu password?</a>
</div>