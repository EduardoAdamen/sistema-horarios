<?php
// =====================================================
// config/database.php
// Conexión a MySQL "A prueba de fallos"
// =====================================================

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $port;
    private $charset = 'utf8mb4';
    public $conn;

    public function __construct() {
        // Función auxiliar para obtener variables de entorno con prioridades
        $this->host     = $this->getEnvVar('MYSQLHOST', 'localhost');
        $this->db_name  = $this->getEnvVar('MYSQLDATABASE', 'sistema_horarios');
        $this->username = $this->getEnvVar('MYSQLUSER', 'root');
        $this->password = $this->getEnvVar('MYSQLPASSWORD', 'admineduardox624'); // Tu pass local
        $this->port     = $this->getEnvVar('MYSQLPORT', '3306');
    }

    // Helper para buscar la variable donde sea que esté
    private function getEnvVar($key, $default) {
        if (isset($_ENV[$key])) return $_ENV[$key];
        if (getenv($key) !== false) return getenv($key);
        if (isset($_SERVER[$key])) return $_SERVER[$key];
        return $default;
    }

    public function getConnection() {
        $this->conn = null;
        
        try {
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name . ";charset=" . $this->charset;
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                // Timeout para evitar que se cuelgue si no conecta
                PDO::ATTR_TIMEOUT => 5 
            ];
            
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch(PDOException $exception) {
            // En producción, esto se verá en los logs de Railway
            error_log("Connection Error: " . $exception->getMessage());
            
            // Mensaje genérico al usuario
            die("Error crítico: No se pudo conectar a la base de datos.");
        }
        
        return $this->conn;
    }
}
?>