<?php

namespace Controllers;

use MVC\Router;

class DashboardController {
    public static function index(Router $router) {

        session_start(); // Arrancar la sesion del anterior controller

        isAuth();

        // parte de proyectos, logica de negocio de la App

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos'
        ]);
    }
    public static function crear_proyecto(Router $router) {

        session_start(); 
        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto'
        ]);
    }
    public static function perfil(Router $router) {

        session_start(); 
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil'
        ]);
    }
}