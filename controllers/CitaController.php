<?php
namespace Controllers;

use MVC\Router;

class CitaController {
    public static function index(Router $router) {

    //Arrancamos la sesion de nuevo (esta tiene el id, nombre, email y el login como "true")
    //session_start();//Aunque esta sesion ya esta iniciada en el Router.php, no es necesario volverla a iniciar
        
        isAuth();//Funcion en funciones.php que revisa si el usuario ya esta autenticado
        
        $router->render('cita/index', [
            //Variables de sesion, nombre y id del usuario
            'nombre' => $_SESSION['nombre'],//Con la sesion iniciada, le pasamos el nombre del usuario de la sesion
            //a las vistas
            'id' => $_SESSION['id']
        ]);
    }
}