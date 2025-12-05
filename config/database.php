<?php
// =====================================================
// config/database.php
// Conexión a Base de Datos con DIAGNÓSTICO DE ERRORES
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
        // Buscamos variables de entorno (Railway / Docker / Local)
        // Prioridad: $_ENV > $_SERVER > getenv() > Localhost
        
        $this->host     = $this->getRawEnv(['MYSQLHOST', 'MYSQL_HOST', 'DB_HOST']);
        $this->db_name  = $this->getRawEnv(['MYSQLDATABASE', 'MYSQL_DATABASE', 'DB_NAME']);
        $this->username = $this->getRawEnv(['MYSQLUSER', 'MYSQL_USER', 'DB_USER']);
        $this->password = $this->getRawEnv(['MYSQLPASSWORD', 'MYSQL_PASSWORD', 'DB_PASSWORD']);
        $this->port     = $this->getRawEnv(['MYSQLPORT', 'MYSQL_PORT', 'DB_PORT']);

        // Fallbacks para entorno LOCAL (XAMPP) si no hay variables de nube
        if (!$this->host) {
            $this->host     = 'localhost';
            $this->db_name  = 'sistema_horarios';
            $this->username = 'root';
            $this->password = 'admineduardox624'; 
            $this->port     = '3306';
        }
    }

    /**
     * Busca el valor de una variable de entorno en múltiples ubicaciones
     */
    private function getRawEnv($keys) {
        foreach ($keys as $key) {
            if (isset($_ENV[$key]) && !empty($_ENV[$key])) return $_ENV[$key];
            if (isset($_SERVER[$key]) && !empty($_SERVER[$key])) return $_SERVER[$key];
            $val = getenv($key);
            if ($val !== false && $val !== '') return $val;
        }
        return null;
    }

    public function getConnection() {
        $this->conn = null;
        
        try {
            // Aseguramos que el puerto tenga un valor válido
            $port = $this->port ?: 3306;
            
            $dsn = "mysql:host={$this->host};port={$port};dbname={$this->db_name};charset={$this->charset}";
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_TIMEOUT => 5, // Timeout corto para no colgar el servidor
            ];
            
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch(PDOException $exception) {
            // =================================================================
            // ZONA DE DIAGNÓSTICO (Solo visible si falla la conexión)
            // =================================================================
            
            // Ocultar contraseña real para no exponerla en pantalla
            $passDisplay = $this->password ? str_repeat('*', 5) : '(VACÍA)';
            
            $errorHtml = "
            <div style='font-family: sans-serif; background: #fff1f2; border: 2px solid #e11d48; padding: 20px; margin: 20px; border-radius: 8px; color: #881337;'>
                <h2 style='margin-top: 0;'>❌ Error de Conexión a Base de Datos</h2>
                <p><strong>Mensaje Técnico:</strong> " . htmlspecialchars($exception->getMessage()) . "</p>
                <hr style='border-color: #fda4af;'>
                <h3>🔍 Datos que se intentaron usar:</h3>
                <ul style='line-height: 1.6;'>
                    <li><strong>Host:</strong> [" . ($this->host ?: '<span style="color:red">NULL</span>') . "]</li>
                    <li><strong>Puerto:</strong> [" . ($this->port ?: '3306 (Default)') . "]</li>
                    <li><strong>Usuario:</strong> [" . ($this->username ?: '<span style="color:red">NULL</span>') . "]</li>
                    <li><strong>Base de Datos:</strong> [" . ($this->db_name ?: '<span style="color:red">NULL</span>') . "]</li>
                    <li><strong>Contraseña:</strong> $passDisplay</li>
                </ul>
                <p><em>Si ves 'localhost' arriba estando en Railway, las variables de entorno NO se están cargando correctamente.</em></p>
                <p><em>Si ves los datos correctos pero dice 'Access denied', verifica tu contraseña.</em></p>
            </div>
            ";
            
            // Registrar error real en logs del servidor
            error_log("DB Connection Failed: " . $exception->getMessage());
            
            // Matar la ejecución y mostrar diagnóstico
            die($errorHtml);
        }
        
        return $this->conn;
    }
}
?>