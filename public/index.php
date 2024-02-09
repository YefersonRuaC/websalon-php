<?php 
//EN ESTE ARCHIVO CREAMOS EL "ROUTING" DE NUESTRO PROYECTO
require_once __DIR__ . '/../includes/app.php';

use Controllers\AdminController;
use Controllers\APIController;
use Controllers\CitaController;
use Controllers\LoginController;
use Controllers\ServicioController;
use MVC\Router;

$router = new Router();

//INICIAR SESION
$router->get('/', [LoginController::class, 'login']);//get es el que muestra la vista del login en este caso
$router->post('/', [LoginController::class, 'login']);//post sera cuando llenemos el formulario del login y lo enviemos

//CERRAR SESION
$router->get('/logout', [LoginController::class, 'logout']);

//RECUPERAR PASSWORD
$router->get('/olvide', [LoginController::class, 'olvide']);
$router->post('/olvide', [LoginController::class, 'olvide']);
//Cuando dan click en el link que se envia al correo del usuario
$router->get('/recuperar', [LoginController::class, 'recuperar']);
$router->post('/recuperar', [LoginController::class, 'recuperar']);

//CREAR CUENTA
$router->get('/crear-cuenta', [LoginController::class, 'crear']);
$router->post('/crear-cuenta', [LoginController::class, 'crear']);//Por medio de este endpoint (post en este caso)
//escuchamos las acciones o funciones que hacemos en el LoginController

//CONFIRMAR CUENTA
$router->get('/confirmar-cuenta', [LoginController::class, 'confirmar']);
$router->get('/mensaje', [LoginController::class, 'mensaje']);


//---------------------------AREA PRIVADA---------------------------------
$router->get('/cita', [CitaController::class, 'index']);
$router->get('/admin', [AdminController::class, 'index']);


//---------------------------API DE CITAS---------------------------------
$router->get('/api/servicios', [APIController::class, 'index']);//endpoint que lista los registros de los servicios
$router->post('/api/citas', [APIController::class, 'guardar']);
$router->post('/api/eliminar', [APIController::class, 'eliminar']);

//------------------------CRUD  de servicios------------------------------
$router->get('/servicios', [ServicioController::class, 'index']);
$router->get('/servicios/crear', [ServicioController::class, 'crear']);
$router->post('/servicios/crear', [ServicioController::class, 'crear']);
$router->get('/servicios/actualizar', [ServicioController::class, 'actualizar']);
$router->post('/servicios/actualizar', [ServicioController::class, 'actualizar']);
$router->post('/servicios/eliminar', [ServicioController::class, 'eliminar']);


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();