<?php
session_start();
require('../vendor/autoload.php');
require "../bootstrap.php";
use App\Core\Router;
use App\Controllers\ContactosController;

$router = new Router();

$router->add(array(
    'name' => 'home',
    'path' => '/^\/$/',
    'action' => [ContactosController::class, 'IndexAction'],
    'auth' => ['Invitado', 'Usuario']
));

$router->add(array(
    'name' => 'login',
    'path' => '/^\/login$/',
    'action' => [ContactosController::class, 'loginAction'],
    'auth' => ['Invitado']
));

$router->add(array(
    'name' => 'registrar',
    'path' => '/^\/registrar$/',
    'action' => [ContactosController::class, 'registrarAction'],
    'auth' => ['Invitado']
));

$router->add(array(
    'name' => 'cerrarSesion',
    'path' => '/^\/cerrarsesion$/',
    'action' => [ContactosController::class, 'cerrarSesionAction'],
    'auth' => ['Usuario']
));

// Verificar con token enviado por email y el token enviado por la url y lo capturas con una expresion regular
$router->add(array(
    'name' => 'verificar',
    'path' => '/^\/verificar\/.*$/',
    'action' => [ContactosController::class, 'verificarAction'],
    'auth' => ['Invitado']
));

$router->add(array(
    'name' => 'perfil',
    'path' => '/^\/perfil$/',
    'action' => [ContactosController::class, 'perfilAction'],
    'auth' => ['Usuario']
));

// Router para eliminar trabajos
$router->add(array(
    'name' => 'eliminarTrabajo',
    'path' => '/^\/eliminartrabajo\/.*$/',
    'action' => [ContactosController::class, 'eliminarTrabajoAction'],
    'auth' => ['Usuario']
));

// Router para eliminar proyectos
$router->add(array(
    'name' => 'eliminarProyecto',
    'path' => '/^\/eliminarproyecto\/.*$/',
    'action' => [ContactosController::class, 'eliminarProyectoAction'],
    'auth' => ['Usuario']
));

// Router para eliminar redes sociales
$router->add(array(
    'name' => 'eliminarRedSocial',
    'path' => '/^\/eliminarredsocial\/.*$/',
    'action' => [ContactosController::class, 'eliminarRedSocialAction'],
    'auth' => ['Usuario']
));

// Router para eliminar habilidades
$router->add(array(
    'name' => 'eliminarHabilidad',
    'path' => '/^\/eliminarhabilidad\/.*$/',
    'action' => [ContactosController::class, 'eliminarHabilidadAction'],
    'auth' => ['Usuario']
));

// Router para ocultar trabajos
$router->add(array(
    'name' => 'ocultarTrabajo',
    'path' => '/^\/ocultartrabajo\/.*$/',
    'action' => [ContactosController::class, 'ocultarTrabajoAction'],
    'auth' => ['Usuario']
));

// Router para ocultar proyectos
$router->add(array(
    'name' => 'ocultarProyecto',
    'path' => '/^\/ocultarproyecto\/.*$/',
    'action' => [ContactosController::class, 'ocultarProyectoAction'],
    'auth' => ['Usuario']
));

// Router para ocultar redes sociales
$router->add(array(
    'name' => 'ocultarRedSocial',
    'path' => '/^\/ocultarredsocial\/.*$/',
    'action' => [ContactosController::class, 'ocultarRedSocialAction'],
    'auth' => ['Usuario']
));


// Router para ocultar habilidades
$router->add(array(
    'name' => 'ocultarHabilidad',
    'path' => '/^\/ocultarhabilidad\/.*$/',
    'action' => [ContactosController::class, 'ocultarHabilidadAction'],
    'auth' => ['Usuario']
));


$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$route = $router->match($request);
if ($router) {
    $controllerName = $route['action'][0];
    $actionName = $route['action'][1];

    $controller = new $controllerName;
    $controller->$actionName($request);
} else {
    echo 'error';
}
