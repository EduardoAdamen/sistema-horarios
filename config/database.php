<?php
class Database {
    // Definir propiedades
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $port;
    public $conn;

    public function getConnection() {
        $this->conn = null;

        // 1. ASIGNAR CREDENCIALES
        // Detectar si estamos en Railway buscando variables de entorno
        // Railway suele usar MYSQLHOST, MYSQLUSER, etc.
        // Ojo: Si usas Postgres, cambia MYSQL por PG
        
        $this->host = $_ENV['MYSQLHOST'] ?? $_ENV['DB_HOST'] ?? 'localhost';
        $this->db_name = $_ENV['MYSQLDATABASE'] ?? $_ENV['DB_NAME'] ?? 'mindbox_db';
        $this->username = $_ENV['MYSQLUSER'] ?? $_ENV['DB_USER'] ?? 'root';
        $this->password = $_ENV['MYSQLPASSWORD'] ?? $_ENV['DB_PASSWORD'] ?? '';
        $this->port = $_ENV['MYSQLPORT'] ?? $_ENV['DB_PORT'] ?? '3306';

        // 2. INTENTAR CONEXIÓN
        try {
            // Cadena de conexión (DSN)
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            
            $this->conn = new PDO($dsn, $this->username, $this->password);
            
            // Configurar manejo de errores y modo de fetch
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            // Opcional: Esto ayuda con caracteres especiales
            $this->conn->exec("set names utf8mb4");

        } catch(PDOException $exception) {
            // En producción, es mejor usar error_log que echo para no exponer datos
            error_log("Error de conexión: " . $exception->getMessage());
            
            // Si estamos en modo debug (ver config.php), mostramos el error
            // Si no, mostramos mensaje genérico
            $debug = $_ENV['APP_DEBUG'] ?? 1;
            if ($debug) {
                echo "Error de conexión: " . $exception->getMessage();
            } else {
                echo "Error de conexión a la base de datos.";
            }
        }

        return $this->conn;
    }
}
?>