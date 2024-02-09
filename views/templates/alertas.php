<?php
//Creamos este template aparte ya que como tenemos varios formularios en el proyecto, desde aqui lo podemos reutilizar

//1re foreach: Iterara sobre el arreglo principal y accede al key (que en este caso es 'error')
    foreach($alertas as $key => $mensajes):
        //2do foreach: Accede e itera sobre los mensajes de error y poder mostrarlos
        foreach($mensajes as $mensaje):
?>

        <!--Con este $key dentro de la clase estamos agregando implicitamente la clase error a nuestro div
        es decir, explicitamente seria (class="alerta error") -->
    <div class="alerta <?= $key; ?>">
        <?= $mensaje; ?>
    </div>

<?php    
        endforeach;
    endforeach;
?>