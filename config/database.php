<?php
// =====================================================
// config/database.php
// Database "robusta" - Prioriza MYSQL_URL y acepta variantes
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
        // 1) Intentar leer directamente MYSQL_URL / DATABASE_URL (Railway suele poner una de estas)
        $mysql_url = $this->envAny(['MYSQL_URL', 'DATABASE_URL']);

        if ($mysql_url) {
            // Ejemplo: mysql://user:pass@host:3306/database
            $url = parse_url($mysql_url);

            $this->username = isset($url['user']) ? rawurldecode($url['user']) : null;
            $this->password = isset($url['pass']) ? rawurldecode($url['pass']) : null;
            $this->host     = $url['host'] ?? null;
            $this->port     = $url['port'] ?? null;
            $this->db_name  = isset($url['path']) ? ltrim($url['path'], '/') : null;
        }

        // 2) Si algo quedó vacío, intentar variantes individuales (con y sin underscore)
        $this->host     = $this->host     ?? $this->envAny(['MYSQLHOST', 'MYSQL_HOST', 'DB_HOST']);
        $this->db_name  = $this->db_name  ?? $this->envAny(['MYSQLDATABASE', 'MYSQL_DATABASE', 'DB_NAME']);
        $this->username = $this->username ?? $this->envAny(['MYSQLUSER', 'MYSQL_USER', 'DB_USER']);
        $this->password = $this->password ?? $this->envAny(['MYSQLPASSWORD', 'MYSQL_PASSWORD', 'DB_PASSWORD']);
        $this->port     = $this->port     ?? $this->envAny(['MYSQLPORT', 'MYSQL_PORT', 'DB_PORT']);

        // 3) No asumir localhost en Railway: si no hay host, detener con mensaje claro
        if (empty($this->host)) {
            // Mensaje claro para que linkees las variables (temporal)
            die("ERROR FATAL: VARIABLE MYSQLHOST NO CONFIGURADA. Ve a tu servicio Web en Railway → Variables → Add Variable Reference y enlaza las variables desde tu servicio MySQL.");
        }

        // 4) Si host es 'localhost' y estamos en Railway, convertir a 127.0.0.1 (prevención)
        if ($this->host === 'localhost') {
            $this->host = '127.0.0.1';
        }

        // 5) Valores por defecto de puerto
        $this->port = $this->port ?: '3306';
    }

    /**
     * envAny: devuelve la primera variable no vacía entre las dadas
     */
    private function envAny(array $keys) {
        foreach ($keys as $k) {
            // Priorizar getenv()
            $v = getenv($k);
            if ($v !== false && $v !== '') return $v;

            if (isset($_SERVER[$k]) && $_SERVER[$k] !== '') return $_SERVER[$k];
            if (isset($_ENV[$k]) && $_ENV[$k] !== '') return $_ENV[$k];
        }
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
                PDO::ATTR_TIMEOUT => 30,
            ];

            // Log seguro para Railway (contraseña enmascarada)
            $masked = $this->password ? substr($this->password, 0, 2) . '***' : '(empty)';
            error_log("[DB] Conectando host={$this->host} port={$this->port} db={$this->db_name} user={$this->username} pass={$masked}");

            $this->conn = new PDO($dsn, $this->username, $this->password, $options);

        } catch (PDOException $e) {
            // Mensaje de diagnóstico (temporal)
            $msg = htmlspecialchars($e->getMessage());
            $html = "<div style='font-family: sans-serif; padding:20px; background:#fff1f2; border:1px solid #fca5a5;'>";
            $html .= "<h2 style='color:#b91c1c'>❌ Error de Conexión</h2>";
            $html .= "<p><strong>Mensaje técnico:</strong> {$msg}</p>";
            $html .= "<ul>";
            $html .= "<li><strong>Host:</strong> [" . htmlspecialchars($this->host) . "]</li>";
            $html .= "<li><strong>Puerto:</strong> [" . htmlspecialchars($this->port) . "]</li>";
            $html .= "<li><strong>DB:</strong> [" . htmlspecialchars($this->db_name) . "]</li>";
            $html .= "<li><strong>User:</strong> [" . htmlspecialchars($this->username) . "]</li>";
            $html .= "<li><strong>Pass:</strong> " . ($this->password ? substr($this->password,0,2) . '***' : '(empty)') . "</li>";
            $html .= "</ul>";
            $html .= "</div>";

            error_log("[DB ERROR] " . $e->getMessage());
            die($html);
        }

        return $this->conn;
    }
}
?>
