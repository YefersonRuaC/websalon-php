<?php
namespace Controllers;

use Model\Servicio;
use MVC\Router;

class ServicioController {

    public static function index(Router $router) {
        // session_start();
        isAdmin();//Protegiendo las rutas, si el usuario no es admin, no lo dejara entrar

        $servicios = Servicio::listar();

        $router->render('servicios/index', [
            'nombre' => $_SESSION['nombre'],
            'servicios' => $servicios
        ]);
    }

    public static function crear(Router $router) {//Parde del $router->get, que muestra nuestra pagina (render)
        isAdmin();

        $servicio = new Servicio;

        $alertas = [];

        //Parte del $router->post en el index.php, que el lo que el usuario (o admin en este caso) envia
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $servicio->sincronizar($_POST);

            $alertas = $servicio->validar();

            if(empty($alertas)) {
                $servicio->guardar();

                header('Location: /servicios');
            }
        }

        $router->render('servicios/crear', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function actualizar(Router $router) {//Parde del $router->get, que muestra nuestra pagina (render)
        isAdmin();

        if(!is_numeric($_GET['id'])){//Si no es el id el que esta en la url
            header('Location: /404');
        };
        $servicio = Servicio::find($_GET['id']);

        $alertas = [];

        //Parte del $router->post en el index.php, que el lo que el usuario (o admin en este caso) envia
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST);

            $alertas = $servicio->validar();

            if(empty($alertas)) {
                $servicio->guardar();

                header('Location: /servicios');
            }
        }

        $router->render('servicios/actualizar', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function eliminar(Router $router) {//Parde del $router->get, que muestra nuestra pagina (render)
        isAdmin();

        //Parte del $router->post en el index.php, que el lo que el usuario (o admin en este caso) envia
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'];

            $servicio = Servicio::find($id);

            $servicio->eliminar();

            header('Location: /servicios');
        }
    }
}