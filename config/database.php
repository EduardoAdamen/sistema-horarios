<?php
// =====================================================
// config/database.php
// Versión DIAGNÓSTICO para Railway
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
        // Intenta obtener MYSQL_URL o DATABASE_URL
        $mysql_url = $this->getEnv('MYSQL_URL') ?? $this->getEnv('DATABASE_URL');
        
        if ($mysql_url) {
            $url = parse_url($mysql_url);
            $this->host     = $url['host'] ?? null;
            $this->username = $url['user'] ?? null;
            $this->password = $url['pass'] ?? null;
            $this->db_name  = isset($url['path']) ? ltrim($url['path'], '/') : null;
            $this->port     = $url['port'] ?? 3306;
        } else {
            // Intenta variables individuales
            $this->host     = $this->getEnv('MYSQLHOST') ?: 'localhost';
            $this->db_name  = $this->getEnv('MYSQLDATABASE') ?: 'sistema_horarios';
            $this->username = $this->getEnv('MYSQLUSER') ?: 'root';
            $this->password = $this->getEnv('MYSQLPASSWORD') ?: 'admineduardox624';
            $this->port     = $this->getEnv('MYSQLPORT') ?: '3306';
        }
    }

    // Buscador agresivo de variables
    private function getEnv($key) {
        if (isset($_ENV[$key])) return $_ENV[$key];
        if (isset($_SERVER[$key])) return $_SERVER[$key];
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
                PDO::ATTR_TIMEOUT => 10,
            ];
            
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch(PDOException $exception) {
            // ========================================================
            // ZONA DE DIAGNÓSTICO (Solo se verá si falla)
            // ========================================================
            echo "<div style='background:#fee; border:1px solid red; padding:20px; font-family:monospace;'>";
            echo "<h2 style='color:red; margin-top:0;'>❌ Error de Conexión</h2>";
            echo "<p><strong>Mensaje SQL:</strong> " . $exception->getMessage() . "</p>";
            echo "<hr>";
            echo "<h3>🔍 Diagnóstico de Variables:</h3>";
            echo "<ul>";
            echo "<li><strong>Host intentado:</strong> [" . $this->host . "]</li>";
            echo "<li><strong>Puerto intentado:</strong> [" . $this->port . "]</li>";
            echo "</ul>";
            
            echo "<h3>📋 Variables de Entorno Disponibles (Claves):</h3>";
            echo "<div style='max-height:300px; overflow:auto; background:#fff; padding:10px; border:1px solid #ccc;'>";
            
            // Recolectar todas las claves disponibles
            $keys_env = array_keys($_ENV);
            $keys_server = array_keys($_SERVER);
            $all_keys = array_unique(array_merge($keys_env, $keys_server));
            sort($all_keys);
            
            $found_mysql = false;
            foreach ($all_keys as $key) {
                // Resaltar las que nos interesan
                if (strpos($key, 'MYSQL') !== false || strpos($key, 'DB') !== false || strpos($key, 'RAILWAY') !== false) {
                    echo "<strong style='color:green'>FOUND: $key</strong><br>";
                    $found_mysql = true;
                } else {
                    echo "$key<br>";
                }
            }
            
            if (!$found_mysql) {
                echo "<br><strong style='color:red; font-size:1.2em;'>¡ALERTA! No se encontraron variables de MySQL. Railway no las está inyectando.</strong>";
            }
            
            echo "</div></div>";
            die(); // Detener ejecución
        }
        
        return $this->conn;
    }
}
?>