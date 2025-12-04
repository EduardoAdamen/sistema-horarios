<?php
// =====================================================
// Configuración Global
// =====================================================

define('APP_NAME', 'Sistema de Gestión de Horarios');
define('APP_VERSION', '1.0.0');

// URL Dinámica: Si existe la variable de entorno APP_URL (Railway), úsala.
// Si no, usa localhost.
$url = $_ENV['APP_URL'] ?? 'http://localhost/mindbox-tec/';
// Asegurar que termine en slash /
if (substr($url, -1) !== '/') {
    $url .= '/';
}
define('APP_URL', $url);

// Zona horaria
date_default_timezone_set('America/Mexico_City');

// Configuración de sesiones
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);

// En Railway (HTTPS) esto debe ser 1, en Localhost 0
// Detectamos si estamos en HTTPS para activarlo automáticamente
$is_https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
ini_set('session.cookie_secure', $is_https ? 1 : 0);

// Configuración de errores
// En producción (Railway) deberías poner esto en 0
$debug_mode = $_ENV['APP_DEBUG'] ?? 1; 
error_reporting($debug_mode ? E_ALL : 0);
ini_set('display_errors', $debug_mode);

// Rutas del sistema (Esto está perfecto, usa rutas absolutas)
define('ROOT_PATH', dirname(__DIR__) . '/');
define('CONTROLLERS_PATH', ROOT_PATH . 'controllers/');
define('MODELS_PATH', ROOT_PATH . 'models/');
define('VIEWS_PATH', ROOT_PATH . 'views/');
define('INCLUDES_PATH', ROOT_PATH . 'includes/');
define('UPLOADS_PATH', ROOT_PATH . 'uploads/');

// Configuración de archivos
define('MAX_UPLOAD_SIZE', 5242880); // 5MB
define('ALLOWED_EXTENSIONS', ['csv', 'xlsx', 'xls']);

// Constantes de Roles
define('ROLE_SUBDIRECTOR', 'subdirector');
define('ROLE_JEFE_DEPTO', 'jefe_depto');
define('ROLE_DOCENTE', 'docente');
define('ROLE_DEP', 'dep');

// Horarios
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

// Autoload
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