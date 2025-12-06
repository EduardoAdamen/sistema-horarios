<?php
class Database {
   
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $port;
    public $conn;

    public function getConnection() {
        $this->conn = null;

       // Credenciales para el host
        
        $this->host = $this->getEnvVar('MYSQLHOST') ?? $this->getEnvVar('DB_HOST') ?? 'localhost';
        $this->db_name = $this->getEnvVar('MYSQLDATABASE') ?? $this->getEnvVar('DB_NAME') ?? 'mindbox_db';
        $this->username = $this->getEnvVar('MYSQLUSER') ?? $this->getEnvVar('DB_USER') ?? 'root';
        $this->password = $this->getEnvVar('MYSQLPASSWORD') ?? $this->getEnvVar('DB_PASSWORD') ?? '';
        $this->port = $this->getEnvVar('MYSQLPORT') ?? $this->getEnvVar('DB_PORT') ?? '3306';

        // Intentar conexión
        try {
           
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            
            $this->conn = new PDO($dsn, $this->username, $this->password);
            
            // Configurar manejo de errores y modo de fetch
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            
            $this->conn->exec("set names utf8mb4");

        } catch(PDOException $exception) {
            
            error_log("Error de conexión: " . $exception->getMessage());
            
           // debug
            $debug = $this->getEnvVar('APP_DEBUG') ?? 1;
            if ($debug) {
                echo "Error de conexión (Detalle): " . $exception->getMessage();
            } else {
                echo "Error de conexión a la base de datos.";
            }
            
            return null;
        }

        return $this->conn;
    }

    // Función auxiliar para leer variables de entorno de forma segura
    private function getEnvVar($key) {
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        } elseif (getenv($key) !== false) {
            return getenv($key);
        }
        return null;
    }
}
?>