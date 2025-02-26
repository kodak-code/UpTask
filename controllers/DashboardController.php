<?php

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;

class DashboardController
{
    public static function index(Router $router)
    {

        session_start(); // Arrancar la sesion del anterior controller

        isAuth();

        $id = $_SESSION['id'];

        $proyectos = Proyecto::belongsto('propietarioId', $id);

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }
    public static function crear_proyecto(Router $router)
    {

        session_start();

        isAuth();

        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $proyecto = new Proyecto($_POST);

            // Validacion
            $alertas = $proyecto->validarProyecto();

            if (empty($alertas)) {
                // Generar una url unica
                $hash = md5(uniqid());
                $proyecto->url = $hash;

                // Almacenar el creador del proyecto
                $proyecto->propietarioId = $_SESSION['id'];

                // Guardar el proyecto
                $proyecto->guardar();

                // Redireccionar
                header('Location: /proyecto?id=' . $proyecto->url);
            }
        }

        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }
    public static function proyecto(Router $router)
    {

        session_start();

        isAuth();

        $token = $_GET['id']; // la url

        if (!$token) header('Location: dashboard');

        // Revisar que la persona que visita el proyecto es quien lo creo
        $proyecto = Proyecto::where('url', $token);

        if ($proyecto->propietarioId !== $_SESSION['id']) header('Location: dashboard');

        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto
        ]);
    }
    public static function perfil(Router $router)
    {

        session_start();

        isAuth();

        $alertas = [];

        $usuario = Usuario::find($_SESSION['id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario->sincronizar($_POST);

            $alertas = $usuario->validar_Perfil();

            if (empty($alertas)) {

                //Verificar que el email nuevo no exista
                $existeUsuario = Usuario::where('email', $usuario->email);

                if ($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    // Mensaje de error
                    Usuario::setAlerta('error', 'Email no valido, ya pertenece a otra cuenta');
                    $alertas = $usuario->getAlertas();
                } else {
                    // Guardar el registro
                    $usuario->guardar();

                    Usuario::setAlerta('exito', 'Guardado Correctamente');
                    $alertas = $usuario->getAlertas();

                    // Asignar el nombre nuevo estatico
                    $_SESSION['nombre'] = $usuario->nombre;
                }
            }
        }

        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }
    public static function cambiar_password(Router $router)
    {
        session_start();

        isAuth();

        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario = Usuario::find($_SESSION['id']);

            // Sincronizar con los datos del usuario
            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevo_password();

            if(empty($alertas)) {
                $resultado = $usuario->comprobar_password();

                if($resultado) {

                    $usuario->password =  $usuario->password_nuevo;

                    // Eliminar propiedades innecesarias
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);

                    // Hashear
                    $usuario->hashPassword();

                    // Actualizar
                    $resultado = $usuario->guardar();

                    if($resultado) {
                        Usuario::setAlerta('exito', 'Password Actualizado');
                        $alertas = $usuario->getAlertas();
                    }

                } else {
                    Usuario::setAlerta('error', 'Password Incorrecto');
                    $alertas = $usuario->getAlertas();
                }
            }

        }

        $router->render('dashboard/cambiar-password', [
            'titulo' => 'Cambiar Password',
            'alertas' => $alertas
        ]);
    }
}
