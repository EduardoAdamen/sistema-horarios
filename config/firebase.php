<?php
// =====================================================
// config/firebase.php
// Configuración DIRECTA de Firebase
// =====================================================

class FirebaseConfig {
    private static $instance = null;
    private $firebase_url;
    private $firebase_secret;
    private $firebase_enabled;
    
    private function __construct() {
        // ============================================================
        // 1. URL DE TU PROYECTO FIREBASE
        // ============================================================
        // Borra la URL de abajo y pon la tuya.
        // DEBE terminar en "/" (diagonal).
        
        $this->firebase_url = 'https://horarios-tecnm-default-rtdb.firebaseio.com/'; 
        
        // ============================================================
        // 2. SECRET KEY (TOKEN)
        // ============================================================
        // Borra el token de abajo y pon el tuyo.
        
        $this->firebase_secret = 'LMLmjhBKjG0xOZDNy9aFBQqCHyIHWVmH5mdduzGF';
        
        // ============================================================
        // 3. ESTADO
        // ============================================================
        $this->firebase_enabled = true;
    }
    
    // --- NO MODIFICAR NADA DE AQUÍ PARA ABAJO ---
    
    public function isEnabled() {
        // Validación simplificada: Si hay URL y está activo, funciona.
        return $this->firebase_enabled && !empty($this->firebase_url);
    }
    
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new FirebaseConfig();
        }
        return self::$instance;
    }
    
    public function getUrl() {
        return $this->firebase_url;
    }
    
    public function getSecret() {
        return $this->firebase_secret;
    }
    
    public function sendData($path, $data, $method = 'PUT') {
        if (!$this->isEnabled()) return false;

        // Limpieza de la URL para asegurar que termine en /
        $baseUrl = rtrim($this->firebase_url, '/') . '/';
        $url = $baseUrl . $path . '.json?auth=' . $this->firebase_secret;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        
        // Ignorar verificación SSL si estás en local (Soluciona errores de cURL en Windows/XAMPP)
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        
        if ($method != 'DELETE' && $data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            error_log("Error Firebase cURL: " . $error);
            return false;
        }
        
        return json_decode($response, true);
    }
    
    public function getData($path) {
        if (!$this->isEnabled()) return false;

        $baseUrl = rtrim($this->firebase_url, '/') . '/';
        $url = $baseUrl . $path . '.json?auth=' . $this->firebase_secret;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Ignorar verificación SSL
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            error_log("Error Firebase cURL: " . $error);
            return false;
        }
        
        return json_decode($response, true);
    }
    
    public function deleteData($path) {
        return $this->sendData($path, null, 'DELETE');
    }
}
?>