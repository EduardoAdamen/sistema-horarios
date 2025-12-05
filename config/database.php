<?php
// =====================================================
// config/database.php
// Conexión a Base de Datos (Producción Railway + Local)
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
        // ESTRATEGIA: Buscar variables de entorno (Railway)
        // Busca tanto 'MYSQLVARIABLE' como 'MYSQL_VARIABLE' para máxima compatibilidad
        
        $this->host     = $this->getRawEnv(['MYSQLHOST', 'MYSQL_HOST']);
        $this->db_name  = $this->getRawEnv(['MYSQLDATABASE', 'MYSQL_DATABASE']);
        $this->username = $this->getRawEnv(['MYSQLUSER', 'MYSQL_USER']);
        $this->password = $this->getRawEnv(['MYSQLPASSWORD', 'MYSQL_PASSWORD']);
        $this->port     = $this->getRawEnv(['MYSQLPORT', 'MYSQL_PORT']);

        // Fallbacks para entorno LOCAL (XAMPP)
        // Se activa solo si no se detectaron las variables críticas de la nube
        if (!$this->host) {
            $this->host     = 'localhost';
            $this->db_name  = 'sistema_horarios';
            $this->username = 'root';
            $this->password = 'admineduardox624'; // Tu contraseña local
            $this->port     = '3306';
        }
    }

    /**
     * Busca el valor de una variable de entorno en múltiples ubicaciones
     * y acepta múltiples nombres posibles (alias).
     */
    private function getRawEnv($keys) {
        foreach ($keys as $key) {
            // 1. $_ENV (Estándar moderno)
            if (isset($_ENV[$key]) && !empty($_ENV[$key])) return $_ENV[$key];
            
            // 2. $_SERVER (Inyección de algunos hostings)
            if (isset($_SERVER[$key]) && !empty($_SERVER[$key])) return $_SERVER[$key];
            
            // 3. getenv (Compatibilidad legacy)
            $val = getenv($key);
            if ($val !== false && $val !== '') return $val;
        }
        return null; // No encontrada
    }

    public function getConnection() {
        $this->conn = null;
        
        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset={$this->charset}";
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_TIMEOUT => 15,
            ];
            
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch(PDOException $exception) {
            // En producción, solo registramos el error en el log del servidor
            // y mostramos un mensaje genérico al usuario por seguridad.
            error_log("Connection Error: " . $exception->getMessage());
            die("Error de conexión a la base de datos. Por favor, intente más tarde.");
        }
        
        return $this->conn;
    }
}
?>