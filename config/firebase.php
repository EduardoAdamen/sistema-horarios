<?php

class FirebaseConfig {
    private static $instance = null;
    private $firebase_url;
    private $firebase_secret;
    
    private function __construct() {
        // Intentar obtener de variables de entorno (Railway)
        $this->firebase_url = $_ENV['FIREBASE_URL'] ?? getenv('FIREBASE_URL');
        $this->firebase_secret = $_ENV['FIREBASE_SECRET'] ?? getenv('FIREBASE_SECRET');

        // FALLBACK: Si no hay variables de entorno, usar valores directos (solo para pruebas)
        if (empty($this->firebase_url)) {
            // Pega aquí tu URL si fallan las variables
            $this->firebase_url = 'https://horarios-tecnm-default-rtdb.firebaseio.com/'; 
        }
        if (empty($this->firebase_secret)) {
            // Pega aquí tu Secreto si fallan las variables
            $this->firebase_secret = 'LMLmjhBKjG0xOZDNy9aFBQqCHyIHWVmH5mdduzGF';
        }
    }
    
    public function isEnabled() {
        return !empty($this->firebase_url) && !empty($this->firebase_secret);
    }
    
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new FirebaseConfig();
        }
        return self::$instance;
    }
    
    public function sendData($path, $data, $method = 'PUT') {
        if (!$this->isEnabled()) {
            return ['error' => 'Firebase no configurado (Faltan URL o Secreto)'];
        }

        // Limpieza de URL para evitar dobles slashes
        $baseUrl = rtrim($this->firebase_url, '/');
        $cleanPath = ltrim($path, '/');
        $url = "$baseUrl/$cleanPath.json?auth=" . $this->firebase_secret;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        
        // Timeouts para evitar que el proceso se cuelgue en Railway
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        // SSL (En producción idealmente debería ser true, pero false ayuda a depurar)
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        if ($method != 'DELETE' && $data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);
        
        // 1. Error de Conexión (cURL falló a nivel de red)
        if ($response === false) {
            error_log("Firebase Network Error: " . $curl_error);
            return ['error' => "Error de red conectando a Firebase: $curl_error"];
        }
        
        // 2. Error HTTP (Firebase respondió, pero con error, ej: 401, 403, 404)
        if ($http_code >= 400) {
            error_log("Firebase HTTP Error ($http_code): " . $response);
            return ['error' => "Firebase rechazó la conexión (Código $http_code). Verifique Permisos/Secreto. Respuesta: $response"];
        }
        
        // 3. Éxito
        return json_decode($response, true);
    }
    
    public function deleteData($path) {
        return $this->sendData($path, null, 'DELETE');
    }
}
?>