<?php
/**
 * Sanitizar input para prevenir XSS
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Generar token CSRF
 */
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verificar token CSRF
 */
function verifyCSRFToken($token) {
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Redirigir con mensaje
 */
function redirect($url, $message = null, $type = 'success') {
    if ($message) {
        $_SESSION[$type] = $message;
    }
    header("Location: $url");
    exit;
}

/**
 * Verificar que el usuario tiene permiso
 */
function checkPermission($required_roles) {
    if (!is_array($required_roles)) {
        $required_roles = [$required_roles];
    }
    
    if (!Auth::hasAnyRole($required_roles)) {
        redirect(APP_URL . 'index.php?c=dashboard&error=access');
    }
}

/**
 * Formatear fecha para MySQL
 */
function formatDateForDB($date) {
    return date('Y-m-d H:i:s', strtotime($date));
}

/**
 * Formatear fecha para mostrar
 */
function formatDateForDisplay($date) {
    return date('d/m/Y', strtotime($date));
}

/**
 * Validar archivo subido
 */
function validateUploadedFile($file, $allowed_types = [], $max_size = MAX_UPLOAD_SIZE) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Error al subir el archivo'];
    }
    
    if ($file['size'] > $max_size) {
        return ['success' => false, 'message' => 'El archivo es demasiado grande'];
    }
    
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!empty($allowed_types) && !in_array($ext, $allowed_types)) {
        return ['success' => false, 'message' => 'Tipo de archivo no permitido'];
    }
    
    return ['success' => true];
}

/**
 * Generar nombre único para archivo
 */
function generateUniqueFileName($original_name) {
    $ext = pathinfo($original_name, PATHINFO_EXTENSION);
    return uniqid() . '_' . time() . '.' . $ext;
}

/**
 * Procesar archivo CSV
 */
function processCSV($file_path) {
    $data = [];
    
    if (($handle = fopen($file_path, 'r')) !== false) {
        $headers = fgetcsv($handle);
        
        while (($row = fgetcsv($handle)) !== false) {
            $data[] = array_combine($headers, $row);
        }
        
        fclose($handle);
    }
    
    return $data;
}

/**
 * Obtener periodo activo
 */
function getPeriodoActivo() {
    $db = new Database();
    $conn = $db->getConnection();
    
    $sql = "SELECT * FROM periodos_escolares WHERE activo = 1 LIMIT 1";
    $stmt = $conn->query($sql);
    
    return $stmt->fetch();
}

/**
 * Convertir día de semana a número
 */
function diaToNumero($dia) {
    $dias = [
        'lunes' => 1,
        'martes' => 2,
        'miercoles' => 3,
        'jueves' => 4,
        'viernes' => 5
    ];
    
    return $dias[$dia] ?? 0;
}

/**
 * Generar PDF (requiere librería FPDF o similar)
 */
function generarPDF($titulo, $contenido, $orientacion = 'P') {
    // Implementar con librería FPDF o TCPDF
    // Este es un placeholder
    return true;
}

/**
 * Limpiar nombre de archivo para guardado seguro
 */
function sanitizeFileName($filename) {
    $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);
    return $filename;
}

/**
 * Obtener extensión de archivo
 */
function getFileExtension($filename) {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

/**
 * Verificar si es petición AJAX
 */
function isAjaxRequest() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

/**
 * Respuesta JSON
 */
function jsonResponse($data, $status_code = 200) {
    http_response_code($status_code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Escapar HTML
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Debug helper
 */
function dd($data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}
?>