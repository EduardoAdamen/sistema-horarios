<?php

define('APP_NAME', 'Sistema de Gestión de Horarios');
define('APP_VERSION', '1.0.1');



if (!empty($_ENV['APP_URL'])) {
    $url = $_ENV['APP_URL'];
} 
// Detectar dominio actual (Host)
elseif (isset($_SERVER['HTTP_HOST'])) {
    
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
                (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ||
                (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
                ? "https" : "http";
    
    $url = $protocol . "://" . $_SERVER['HTTP_HOST'] . "/";
} 

else {
    $url = 'http://localhost/mindbox-tec/';
}


if (substr($url, -1) !== '/') {
    $url .= '/';
}

define('APP_URL', $url);

date_default_timezone_set('America/Mexico_City');

ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);


$is_https = (strpos($url, 'https://') === 0);
ini_set('session.cookie_secure', $is_https ? 1 : 0);



$debug_mode = $_ENV['APP_DEBUG'] ?? 1; 
error_reporting($debug_mode ? E_ALL : 0);
ini_set('display_errors', $debug_mode);


define('ROOT_PATH', dirname(__DIR__) . '/');
define('CONTROLLERS_PATH', ROOT_PATH . 'controllers/');
define('MODELS_PATH', ROOT_PATH . 'models/');
define('VIEWS_PATH', ROOT_PATH . 'views/');
define('INCLUDES_PATH', ROOT_PATH . 'includes/');
define('UPLOADS_PATH', ROOT_PATH . 'uploads/');


define('MAX_UPLOAD_SIZE', 5242880); 
define('ALLOWED_EXTENSIONS', ['csv', 'xlsx', 'xls']);

define('ROLE_SUBDIRECTOR', 'subdirector');
define('ROLE_JEFE_DEPTO', 'jefe_depto');
define('ROLE_DOCENTE', 'docente');
define('ROLE_DEP', 'dep');

define('HORA_INICIO', '07:00:00');
define('HORA_FIN', '21:00:00');

// Arrays Globales
$DIAS_SEMANA = [
    'lunes' => 'Lunes',
    'martes' => 'Martes',
    'miercoles' => 'Miércoles',
    'jueves' => 'Jueves',
    'viernes' => 'Viernes'
];

$ESTADOS_HORARIO = [
    'borrador' => 'Borrador',
    'conciliado' => 'Conciliado',
    'publicado' => 'Publicado'
];


spl_autoload_register(function ($class_name) {
    $file_model = MODELS_PATH . $class_name . '.php';
    $file_controller = CONTROLLERS_PATH . $class_name . '.php';
    
    if (file_exists($file_model)) {
        require_once $file_model;
    } elseif (file_exists($file_controller)) {
        require_once $file_controller;
    }
});
?>