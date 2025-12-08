<?php

class FirebaseConfig {
    private static $instance = null;
    private $firebase_url;
    private $firebase_secret;
    private $firebase_enabled;
    
    private function __construct() {
        
        
        $this->firebase_url = 'https://horarios-tecnm-default-rtdb.firebaseio.com/'; 
        
        
        $this->firebase_secret = 'LMLmjhBKjG0xOZDNy9aFBQqCHyIHWVmH5mdduzGF';
       
        $this->firebase_enabled = true;
    }
    
  
    
    public function isEnabled() {
       
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

        
        $baseUrl = rtrim($this->firebase_url, '/') . '/';
        $url = $baseUrl . $path . '.json?auth=' . $this->firebase_secret;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        
        
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