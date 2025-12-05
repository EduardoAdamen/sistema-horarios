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

        // 1. ASIGNAR CREDENCIALES (ROBUSTO)
        // Usamos una lógica que intenta leer de $_ENV y si falla usa getenv()
        // Esto soluciona el error "No such file or directory" al evitar el fallback a localhost
        
        $this->host = $this->getEnvVar('MYSQLHOST') ?? $this->getEnvVar('DB_HOST') ?? 'localhost';
        $this->db_name = $this->getEnvVar('MYSQLDATABASE') ?? $this->getEnvVar('DB_NAME') ?? 'mindbox_db';
        $this->username = $this->getEnvVar('MYSQLUSER') ?? $this->getEnvVar('DB_USER') ?? 'root';
        $this->password = $this->getEnvVar('MYSQLPASSWORD') ?? $this->getEnvVar('DB_PASSWORD') ?? '';
        $this->port = $this->getEnvVar('MYSQLPORT') ?? $this->getEnvVar('DB_PORT') ?? '3306';

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
            
            // Si estamos en modo debug mostramos el error
            $debug = $this->getEnvVar('APP_DEBUG') ?? 1;
            if ($debug) {
                echo "Error de conexión (Detalle): " . $exception->getMessage();
            } else {
                echo "Error de conexión a la base de datos.";
            }
            // Importante: retornamos null
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