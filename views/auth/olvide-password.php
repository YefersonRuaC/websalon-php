<h1 class="nombre-pagina">Olvide password</h1>
<p class="descripcion-pagina">Reestablece tu password escribiendo tu email a continuacion</p>

<?php 
    //Recordemos que con __DIR__ hacemos referencia al archivo que estamos actualmente y desde ahi podemos 
    //apuntar mas facilmente al archivo que requerimos
    include_once __DIR__ . "/../templates/alertas.php";//Estamos añadiendo nuestras alertas
?>

<form action="/olvide" method="POST" class="formulario">
    <div class="campo">
        <label for="email">Email</label>
        <input 
            type="email" 
            name="email" 
            id="email" 
            placeholder="Ingresa tu email">
    </div>

    <input type="submit" class="boton" value="Enviar instrucciones" name="" id="">
    
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia sesion</a>
    <a href="/crear-cuenta">¿Aun no tienes cuenta? ¡Crea una!</a>
</div>