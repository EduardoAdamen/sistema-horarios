<?php
// =====================================================
// config/database.php
// Versión CORREGIDA y con diagnóstico seguro
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
        // Intentar MYSQL_URL primero (Railway)
        $mysql_url = $this->safeGetEnv('MYSQL_URL') ?? $this->safeGetEnv('DATABASE_URL');

        if ($mysql_url) {
            $url = parse_url($mysql_url);
            $this->host     = $url['host'] ?? null;
            $this->username = $url['user'] ?? null;
            $this->password = $url['pass'] ?? null;
            $this->db_name  = isset($url['path']) ? ltrim($url['path'], '/') : null;
            $this->port     = $url['port'] ?? '3306';
        } else {
            // Variables separadas
            $this->host     = $this->safeGetEnv('MYSQLHOST') ?: null;
            $this->db_name  = $this->safeGetEnv('MYSQLDATABASE') ?: null;
            $this->username = $this->safeGetEnv('MYSQLUSER') ?: null;
            $this->password = $this->safeGetEnv('MYSQLPASSWORD') ?: null;
            $this->port     = $this->safeGetEnv('MYSQLPORT') ?: null;
        }

        // Si host está vacío, no asumir localhost: mostrar error claro
        if (empty($this->host)) {
            error_log("[DB DIAG] MYSQLHOST vacío. Variables de entorno disponibles: " . implode(', ', array_keys($_SERVER)));
            die("ERROR FATAL: VARIABLE MYSQLHOST NO CONFIGURADA. Revisa 'Add Variable Reference' en tu servicio Web y enlaza las variables del servicio MySQL.");
        }

        // En caso de que el valor sea 'localhost', preferimos forzar TCP a 127.0.0.1
        // (pero en Railway no debería ser localhost; lo dejamos solo como precaución)
        if ($this->host === 'localhost') {
            $this->host = '127.0.0.1';
        }
    }

    /**
     * safeGetEnv: intenta obtener la variable de entorno de forma segura
     * Prioriza getenv() y evita aceptar cadena vacía como valor válido.
     */
    private function safeGetEnv(string $key) {
        // 1) getenv()
        $val = getenv($key);
        if ($val !== false && $val !== '') return $val;

        // 2) $_SERVER
        if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') return $_SERVER[$key];

        // 3) $_ENV
        if (isset($_ENV[$key]) && $_ENV[$key] !== '') return $_ENV[$key];

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
                PDO::ATTR_TIMEOUT => 10,
            ];

            // Log seguro (no contraseñas completas)
            $maskedPass = $this->password ? substr($this->password, 0, 2) . '***' : '(empty)';
            error_log("[DB DIAG] Conectando a MySQL host={$this->host} port={$this->port} db={$this->db_name} user={$this->username} pass={$maskedPass}");

            $this->conn = new PDO($dsn, $this->username, $this->password, $options);

        } catch(PDOException $exception) {
            // Mensaje amigable en pantalla para debugging (temporal)
            echo "<div style='background:#fee; border:1px solid red; padding:20px; font-family:monospace;'>";
            echo "<h2 style='color:red; margin-top:0;'>❌ Error de Conexión</h2>";
            echo "<p><strong>Mensaje SQL:</strong> " . htmlspecialchars($exception->getMessage()) . "</p>";
            echo "<hr>";
            echo "<h3>🔍 Diagnóstico de Variables:</h3>";
            echo "<ul>";
            echo "<li><strong>Host intentado:</strong> [" . htmlspecialchars($this->host) . "]</li>";
            echo "<li><strong>Puerto intentado:</strong> [" . htmlspecialchars($this->port) . "]</li>";
            echo "<li><strong>DB:</strong> [" . htmlspecialchars($this->db_name) . "]</li>";
            echo "<li><strong>Usuario:</strong> [" . htmlspecialchars($this->username) . "]</li>";
            echo "<li><strong>Password:</strong> [" . ($this->password ? substr($this->password,0,2) . '***' : '(empty)') . "]</li>";
            echo "</ul>";
            echo "</div>";

            // También registrar en logs (útil para Railway logs)
            error_log("[DB ERROR] " . $exception->getMessage());
            die();
        }

        return $this->conn;
    }
}
?>
