<h1 class="nombre-pagina">Recuperar password</h1>

<p class="descripcion-pagina">Coloca tu nuevo password a continuacion</p>

<?php 
    //Recordemos que con __DIR__ hacemos referencia al archivo que estamos actualmente y desde ahi podemos 
    //apuntar mas facilmente al archivo que requerimos
    include_once __DIR__ . "/../templates/alertas.php";//Estamos añadiendo nuestras alertas
?>

<!--Con esto, al haber un $error (es decir que el token no es valido) entonces ya no mostramos el formulario-->
<?php if($error) return; ?>

<!--
    No ponemos el action ya que si lo ponemos, nos eliminaria el token de la url y no podriamos hacer la comprobacion
    De esta manera (sin action) lo envia (en realidad deja) en la misma url donde esta
-->
<form class="formulario" method="POST">
    <div class="campo">
        <label for="password">Password</label>
        <input 
            type="password"
            id="password"
            name="password"
            placeholder="Ingresa tu nuevo password">
    </div>

    <input class="boton" type="submit" name="" id="" value="Guardar nuevo password">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia sesion</a>
    <a href="/crear-cuenta">¿Aun no tienes cuenta? ¡Crea una!</a>
</div>