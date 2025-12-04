<?php
// =====================================================
// config/database.php
// Conexión a MySQL corregida para Railway
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
        // MÉTODO INFALIBLE:
        // Buscamos primero en $_ENV (Nube), luego en getenv() y al final usamos los datos locales.
        // El operador '??' asigna el primer valor que no sea nulo.

        $this->host     = $_ENV['MYSQLHOST'] ?? getenv('MYSQLHOST') ?? 'localhost';
        $this->port     = $_ENV['MYSQLPORT'] ?? getenv('MYSQLPORT') ?? '3306';
        $this->db_name  = $_ENV['MYSQLDATABASE'] ?? getenv('MYSQLDATABASE') ?? 'sistema_horarios';
        $this->username = $_ENV['MYSQLUSER'] ?? getenv('MYSQLUSER') ?? 'root';
        $this->password = $_ENV['MYSQLPASSWORD'] ?? getenv('MYSQLPASSWORD') ?? 'admineduardox624';
    }

    public function getConnection() {
        $this->conn = null;
        
        try {
            // Construimos el DSN asegurando que el puerto esté incluido
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset={$this->charset}";
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_TIMEOUT => 10, // Esperar máx 10 segundos
            ];
            
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch(PDOException $exception) {
            // DEBUG: Mostrar qué host intentó usar para saber si leyó la variable
            // OJO: En producción real esto no se hace por seguridad, pero ahora lo necesitas para debug
            die("DB ERROR: " . $exception->getMessage() . " | Intentando conectar a Host: " . $this->host);
        }
        
        return $this->conn;
    }
}
?>