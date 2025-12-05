<?php
// =====================================================
// Configuración Global - MEJORADO PARA RAILWAY
// =====================================================

define('APP_NAME', 'Sistema de Gestión de Horarios');
define('APP_VERSION', '1.0.1');

// --- LÓGICA DE DETECCIÓN DE URL ---

// 1. Prioridad: Variable de entorno explícita (Railway)
if (!empty($_ENV['APP_URL'])) {
    $url = $_ENV['APP_URL'];
} 
// 2. Automático: Detectar dominio actual (Host)
elseif (isset($_SERVER['HTTP_HOST'])) {
    // Detectar protocolo (HTTPS o HTTP)
    // Railway usa un balanceador de carga, así que revisamos los headers X-Forwarded
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
                (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ||
                (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
                ? "https" : "http";
    
    $url = $protocol . "://" . $_SERVER['HTTP_HOST'] . "/";
} 
// 3. Fallback: Localhost (Solo si todo lo anterior falla)
else {
    $url = 'http://localhost/mindbox-tec/';
}

// Asegurar que la URL termine siempre en slash '/'
if (substr($url, -1) !== '/') {
    $url .= '/';
}

define('APP_URL', $url);

// --- CONFIGURACIÓN REGIONAL Y SESIONES ---

date_default_timezone_set('America/Mexico_City');

ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);

// Detección de HTTPS para cookies seguras
$is_https = (strpos($url, 'https://') === 0);
ini_set('session.cookie_secure', $is_https ? 1 : 0);

// --- ERRORES ---

$debug_mode = $_ENV['APP_DEBUG'] ?? 1; 
error_reporting($debug_mode ? E_ALL : 0);
ini_set('display_errors', $debug_mode);

// --- RUTAS DEL SISTEMA (Absolutas) ---
define('ROOT_PATH', dirname(__DIR__) . '/');
define('CONTROLLERS_PATH', ROOT_PATH . 'controllers/');
define('MODELS_PATH', ROOT_PATH . 'models/');
define('VIEWS_PATH', ROOT_PATH . 'views/');
define('INCLUDES_PATH', ROOT_PATH . 'includes/');
define('UPLOADS_PATH', ROOT_PATH . 'uploads/');

// --- CONSTANTES DE NEGOCIO ---
define('MAX_UPLOAD_SIZE', 5242880); // 5MB
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

// --- AUTOLOAD ---
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