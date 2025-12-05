<?php
// =====================================================
// config/database.php
// Conexión Robusta para Railway (Soporte MYSQL_URL)
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
        // 1. INTENTO PRIMARIO: Usar la URL completa de Railway (Más seguro)
        // Railway suele dar una variable MYSQL_URL que tiene todo junto.
        $mysql_url = $this->getEnv('MYSQL_URL');
        
        if ($mysql_url) {
            $url = parse_url($mysql_url);
            $this->host     = $url['host'] ?? null;
            $this->username = $url['user'] ?? null;
            $this->password = $url['pass'] ?? null;
            $this->db_name  = isset($url['path']) ? ltrim($url['path'], '/') : null;
            $this->port     = $url['port'] ?? 3306;
        } else {
            // 2. INTENTO SECUNDARIO: Variables individuales
            // Usamos el operador ?: para evitar que 'false' se quede como valor
            $this->host     = $this->getEnv('MYSQLHOST') ?: 'localhost';
            $this->db_name  = $this->getEnv('MYSQLDATABASE') ?: 'sistema_horarios';
            $this->username = $this->getEnv('MYSQLUSER') ?: 'root';
            $this->password = $this->getEnv('MYSQLPASSWORD') ?: 'admineduardox624';
            $this->port     = $this->getEnv('MYSQLPORT') ?: '3306';
        }
    }

    // Helper MEJORADO para obtener variables limpias
    private function getEnv($key) {
        // Prioridad 1: $_ENV (Estándar moderno)
        if (isset($_ENV[$key])) return $_ENV[$key];
        
        // Prioridad 2: $_SERVER (A veces Railway las inyecta aquí)
        if (isset($_SERVER[$key])) return $_SERVER[$key];
        
        // Prioridad 3: getenv (Estándar antiguo)
        $val = getenv($key);
        return ($val !== false) ? $val : null; 
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
            // Mensaje de depuración detallado
            $errorMsg = "DB Error: " . $exception->getMessage();
            $debugInfo = " | Host detectado: [" . ($this->host ? $this->host : 'VACÍO') . "]";
            $debugInfo .= " | Puerto: " . $this->port;
            
            // Ojo: En producción esto es inseguro, pero necesario ahora para debug
            die($errorMsg . $debugInfo);
        }
        
        return $this->conn;
    }
}