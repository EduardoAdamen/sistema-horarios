<?php
// =====================================================
// config/database.php
// Conexión Blindada para Railway (Prioridad MYSQL_URL)
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
        // ESTRATEGIA 1: Intentar parsear MYSQL_URL (La más confiable en Railway)
        $mysql_url = $this->findEnv('MYSQL_URL');
        
        if ($mysql_url) {
            $url = parse_url($mysql_url);
            $this->host     = $url['host'] ?? null;
            $this->username = $url['user'] ?? null;
            $this->password = $url['pass'] ?? null;
            $this->db_name  = isset($url['path']) ? ltrim($url['path'], '/') : null;
            $this->port     = $url['port'] ?? null;
        }

        // ESTRATEGIA 2: Si falta algo, buscar variables individuales
        // El operador ?: asegura que no se usen valores vacíos
        if (empty($this->host))     $this->host     = $this->findEnv('MYSQLHOST') ?: 'localhost';
        if (empty($this->db_name))  $this->db_name  = $this->findEnv('MYSQLDATABASE') ?: 'sistema_horarios';
        if (empty($this->username)) $this->username = $this->findEnv('MYSQLUSER') ?: 'root';
        if (empty($this->password)) $this->password = $this->findEnv('MYSQLPASSWORD') ?: 'admineduardox624';
        if (empty($this->port))     $this->port     = $this->findEnv('MYSQLPORT') ?: '3306';
    }

    // Helper agresivo para encontrar variables no vacías
    private function findEnv($key) {
        // 1. Revisar getenv()
        $val = getenv($key);
        if ($val !== false && $val !== '') return $val;

        // 2. Revisar $_ENV
        if (isset($_ENV[$key]) && $_ENV[$key] !== '') return $_ENV[$key];

        // 3. Revisar $_SERVER
        if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') return $_SERVER[$key];

        return null;
    }

    public function getConnection() {
        $this->conn = null;
        
        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset={$this->charset}";
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_TIMEOUT => 30, // Aumentamos timeout por si la red es lenta
            ];
            
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch(PDOException $exception) {
            // DEBUG FINAL: Mostrar qué datos intentó usar
            // OJO: Si ves esto, significa que Railway no está pasando NADA de info
            
            echo "<div style='background:#fff0f0; border:2px solid red; padding:20px; font-family:monospace; color:#333;'>";
            echo "<h2 style='color:red; margin-top:0;'>❌ Error Crítico de Conexión</h2>";
            echo "<p><strong>Mensaje del Sistema:</strong> " . $exception->getMessage() . "</p>";
            echo "<hr>";
            echo "<h3>Datos de Intento de Conexión:</h3>";
            echo "<ul>";
            echo "<li><strong>Host:</strong> [" . ($this->host ?: '<span style="color:red">VACÍO</span>') . "]</li>";
            echo "<li><strong>Puerto:</strong> [" . ($this->port ?: '<span style="color:red">VACÍO</span>') . "]</li>";
            echo "<li><strong>Usuario:</strong> [" . ($this->username ?: '<span style="color:red">VACÍO</span>') . "]</li>";
            echo "<li><strong>Base de Datos:</strong> " . ($this->db_name ?: '<span style="color:red">VACÍO</span>') . "</li>";
            echo "</ul>";
            echo "<hr>";
            echo "<p><em>Nota: Si los datos están vacíos o son 'localhost', Railway no está inyectando las variables correctamente. Reinicia el servicio (Redeploy).</em></p>";
            echo "</div>";
            die();
        }
        
        return $this->conn;
    }
}
?>