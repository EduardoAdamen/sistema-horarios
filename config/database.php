<?php
// =====================================================
// Conexión a MySQL "Inteligente" (Local y Railway)
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
        // Lógica: Si existen variables de entorno (Railway), úsalas.
        // Si no, usa las credenciales locales (XAMPP).
        
        $this->host     = $_ENV['MYSQLHOST'] ?? 'localhost';
        $this->db_name  = $_ENV['MYSQLDATABASE'] ?? 'sistema_horarios';
        $this->username = $_ENV['MYSQLUSER'] ?? 'root';
        $this->password = $_ENV['MYSQLPASSWORD'] ?? 'admineduardox624'; // Tu contraseña local
        $this->port     = $_ENV['MYSQLPORT'] ?? '3306';
    }

    public function getConnection() {
        $this->conn = null;
        
        try {
            // Se agrega el puerto al DSN
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name . ";charset=" . $this->charset;
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch(PDOException $exception) {
            // En producción, es mejor registrar el error y no mostrar detalles sensibles
            error_log("Error de conexión: " . $exception->getMessage());
            die("Error de conexión a la base de datos.");
        }
        
        return $this->conn;
    }
}
?>