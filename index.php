<?php

require_once 'config/config.php';
require_once 'config/database.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado
if (!Auth::isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Obtener el controlador y acción
$controller = $_GET['c'] ?? 'dashboard';
$action = $_GET['a'] ?? 'index';


$allowed_controllers = [
    'dashboard' => 'DashboardController',
    'usuarios'  => 'UsuariosController',
    'materias'  => 'MateriasController',
    'grupos'    => 'GruposController',
    'aulas'     => 'AulasController',
    'docentes'  => 'DocentesController',
    'horarios'  => 'HorariosController',
    'reportes'  => 'ReportesController',
    'periodos'  => 'PeriodosController'
];

// Validar controlador
if (!isset($allowed_controllers[$controller])) {
    $controller = 'dashboard';
}

$controller_name = $allowed_controllers[$controller];
$controller_file = CONTROLLERS_PATH . $controller_name . '.php';

// Cargar el controlador
if (file_exists($controller_file)) {
    require_once $controller_file;
    
    // Verificar que la clase existe
    if (class_exists($controller_name)) {
        $controller_instance = new $controller_name();
        
        // Verificar si el método existe
        if (method_exists($controller_instance, $action)) {
            $controller_instance->$action();
        } else {
            // Si el método no existe, cargar index por defecto
            if (method_exists($controller_instance, 'index')) {
                $controller_instance->index();
            } else {
                die("Error: Método '$action' no encontrado en el controlador '$controller_name'");
            }
        }
    } else {
        die("Error: Clase '$controller_name' no encontrada en el archivo '$controller_file'");
    }
} else {
    die("Error: Controlador no encontrado - Archivo esperado: $controller_file");
}
?>