<?php

namespace Controllers;

use Model\Proyecto;
use Model\Tarea;

class TareaController
{
    public static function index()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }
    }
    public static function crear()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            session_start();

            $proyectoId = $_POST['proyectoId'];

            $proyecto = Proyecto::where('url', $proyectoId);

            if (!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un Error al agregar la tarea'
                ];

                echo json_encode($respuesta);
            }

            // To.do bien, instanciar y crear la tarea
            $tarea = new Tarea($_POST);

            $tarea->proyectoId = $proyecto->id; // reescribir para usar el id

            $resultado = $tarea->guardar();

            $respuesta = [
                'tipo' => 'exito',
                'id' => $resultado['id'],
                'mensaje' => 'Tarea Creada Correctamente'
            ];

            echo json_encode($respuesta);
        }
    }
    public static function actualizar()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }
    }
    public static function eliminar()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }
    }
}
