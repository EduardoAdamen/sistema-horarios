<?php
// =====================================================
// config/database.php
// Conexión "Directa" con Diagnóstico de Valores
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
        // ESTRATEGIA: Leer directamente las variables individuales.
        // No usamos parse_url para evitar errores con caracteres especiales en la contraseña.
        
        $this->host     = $this->getRawEnv(['MYSQLHOST', 'MYSQL_HOST']);
        $this->db_name  = $this->getRawEnv(['MYSQLDATABASE', 'MYSQL_DATABASE']);
        $this->username = $this->getRawEnv(['MYSQLUSER', 'MYSQL_USER']);
        $this->password = $this->getRawEnv(['MYSQLPASSWORD', 'MYSQL_PASSWORD']);
        $this->port     = $this->getRawEnv(['MYSQLPORT', 'MYSQL_PORT']);

        // Fallbacks para local (Solo si NO estamos en Railway)
        // Detectamos si estamos en Railway buscando alguna variable típica
        $in_railway = getenv('RAILWAY_ENVIRONMENT') !== false || isset($_ENV['RAILWAY_ENVIRONMENT']);
        
        if (!$in_railway) {
            if (!$this->host) $this->host = 'localhost';
            if (!$this->db_name) $this->db_name = 'sistema_horarios';
            if (!$this->username) $this->username = 'root';
            if (!$this->password) $this->password = 'admineduardox624';
            if (!$this->port) $this->port = '3306';
        }
    }

    // Busca la variable en todos lados y devuelve el valor crudo
    private function getRawEnv($keys) {
        foreach ($keys as $key) {
            // 1. $_ENV
            if (isset($_ENV[$key]) && !empty($_ENV[$key])) return $_ENV[$key];
            // 2. $_SERVER
            if (isset($_SERVER[$key]) && !empty($_SERVER[$key])) return $_SERVER[$key];
            // 3. getenv
            $val = getenv($key);
            if ($val !== false && $val !== '') return $val;
        }
        return null; // No encontrada
    }

    public function getConnection() {
        $this->conn = null;
        
        try {
            // Validar que tengamos datos mínimos antes de intentar conectar
            if (empty($this->host) || empty($this->username)) {
                throw new Exception("Variables de entorno vacías o no detectadas.");
            }

            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset={$this->charset}";
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_TIMEOUT => 15,
            ];
            
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch(Exception $exception) {
            // =================================================================
            // DIAGNÓSTICO FINAL: Muestra qué está leyendo realmente
            // =================================================================
            echo "<div style='background:#fff0f0; border:2px solid red; padding:20px; font-family:monospace; color:#333; max-width:800px; margin:20px auto;'>";
            echo "<h2 style='color:red; margin-top:0;'>❌ Error de Conexión (Modo Directo)</h2>";
            echo "<p><strong>Error:</strong> " . $exception->getMessage() . "</p>";
            
            echo "<hr>";
            echo "<h3>🔍 Valores leídos del Entorno:</h3>";
            echo "<ul>";
            
            $vars = [
                'HOST' => $this->host,
                'PORT' => $this->port,
                'DATABASE' => $this->db_name,
                'USER' => $this->username,
                'PASSWORD' => $this->password ? '(Oculto - Longitud: '.strlen($this->password).')' : '(VACÍO)'
            ];
            
            foreach ($vars as $name => $val) {
                $status = $val ? "<span style='color:green'>DETECTADO</span>" : "<span style='color:red'>VACÍO / NULL</span>";
                echo "<li><strong>$name:</strong> $status <br><small>Valor: " . ($val ?: 'Ninguno') . "</small></li>";
            }
            echo "</ul>";
            
            echo "<hr>";
            echo "<p><em>Si ves los valores como 'VACÍO', significa que Railway no está pasando los valores a PHP. Ve a la pestaña 'Variables' en Railway y asegúrate de que no sean referencias rotas.</em></p>";
            echo "</div>";
            die();
        }
        
        return $this->conn;
    }
}
?>