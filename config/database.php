<?php
// =====================================================
// config/database.php
// Versión Final: Configuración Manual (Railway) + Local
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
        // -----------------------------------------------------
        // 1. INTENTAR CARGAR CONFIGURACIÓN DE NUBE (RAILWAY)
        // -----------------------------------------------------
        // Buscamos las variables que configuraste manualmente.
        // Aceptamos variaciones (MYSQLHOST o MYSQL_HOST) por compatibilidad.
        
        $this->host     = $this->getEnvVar(['MYSQLHOST', 'MYSQL_HOST']);
        $this->db_name  = $this->getEnvVar(['MYSQLDATABASE', 'MYSQL_DATABASE']);
        $this->username = $this->getEnvVar(['MYSQLUSER', 'MYSQL_USER']);
        $this->password = $this->getEnvVar(['MYSQLPASSWORD', 'MYSQL_PASSWORD']);
        $this->port     = $this->getEnvVar(['MYSQLPORT', 'MYSQL_PORT']);

        // -----------------------------------------------------
        // 2. FALLBACK A ENTORNO LOCAL (XAMPP)
        // -----------------------------------------------------
        // Si no se detectó el HOST de la nube, asumimos que estás en tu PC.
        
        if (empty($this->host)) {
            $this->host     = 'localhost';
            $this->db_name  = 'sistema_horarios';
            $this->username = 'root';
            $this->password = 'admineduardox624'; // Tu contraseña local
            $this->port     = '3306';
        }
    }

    /**
     * Helper para buscar variables de entorno en cualquier lugar
     * (Soporta getenv, $_ENV y $_SERVER)
     */
    private function getEnvVar($keys) {
        foreach ($keys as $key) {
            // 1. Revisar getenv() (Estándar)
            $val = getenv($key);
            if ($val !== false && $val !== '') return $val;
            
            // 2. Revisar $_ENV (Contenedores modernos)
            if (isset($_ENV[$key]) && !empty($_ENV[$key])) return $_ENV[$key];
            
            // 3. Revisar $_SERVER (Servidores web clásicos)
            if (isset($_SERVER[$key]) && !empty($_SERVER[$key])) return $_SERVER[$key];
        }
        return null;
    }

    public function getConnection() {
        $this->conn = null;
        
        try {
            // Usamos el puerto detectado (en Railway no es 3306)
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset={$this->charset}";
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_TIMEOUT => 10, // Tiempo de espera prudente
            ];
            
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch(PDOException $exception) {
            // En producción NO mostramos el error real al usuario por seguridad.
            // Lo guardamos en el log del servidor.
            error_log("Connection Error: " . $exception->getMessage());
            
            // Mensaje genérico para el usuario
            die("Error de conexión a la base de datos. El sistema no puede acceder a los datos en este momento.");
        }
        
        return $this->conn;
    }
}
?>