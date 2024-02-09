<?php
namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;
//API como implica solo el backend (no muestra vistas), no requiere del Router, ni el render para mostrar datos
class APIController {
    public static function index() {
        $servicios = Servicio::listar();//Cambie all() por listar() en el ActiveRecord

        echo json_encode($servicios);//Exportamos el json de nuestra API
    }

    public static function guardar() {
        //Le pasamos a la vrble $cita todos datos que vienen desde el POST
        $cita = new Cita($_POST);

        //Con los metodos ya creados en ActiveRecord, creamos un nuevo registro en la BD
        $resultado = $cita->guardar();//Almacenando la cita y devolviendo el ID del servicio

        $id = $resultado['id'];

        //Separando uno a uno los id de los servicios, con las , (en JS se haria con split)
        //Para almacenar cada uno de los servicios con el id de la cita
        $idServicios = explode(",", $_POST['servicios']);

        //Este foreach va ir iterando y guardando cada uno de los servicios con la referencia de la cita
        foreach($idServicios as $idServicio) {
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];

            $citaServicio = new CitaServicio($args);

            $citaServicio->guardar();
        }

        //Almacena la cita y el servicio
        //resultado es el que ponemos el en app.js para que muestre la alerta (resultado.resultado)
        echo json_encode(['resultado' => $resultado]);
    }

    public static function eliminar() {
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];

            //Capturamos el id de la cita
            $cita = Cita::find($id);

            //Y eliminamos la cita
            $cita->eliminar();
            //Al eliminar la cita, nos redirecciona a $_SERVER['HTTP_REFERER'] que es la pagina de la que veniamos
            //(donde estan las citas)
            header('Location:' . $_SERVER['HTTP_REFERER']);
        }
    }
}