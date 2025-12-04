<?php
// =====================================================
// config/database.php
// Versión DIRECTA para Railway (Sin lógica compleja)
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
        // LÓGICA DIRECTA:
        // Buscamos las variables directamente en los arreglos globales.
        // El operador '??' toma el primero que encuentre que no sea nulo.
        
        $this->host     = $_ENV['MYSQLHOST'] ?? $_SERVER['MYSQLHOST'] ?? getenv('MYSQLHOST') ?? 'localhost';
        $this->db_name  = $_ENV['MYSQLDATABASE'] ?? $_SERVER['MYSQLDATABASE'] ?? getenv('MYSQLDATABASE') ?? 'sistema_horarios';
        $this->username = $_ENV['MYSQLUSER'] ?? $_SERVER['MYSQLUSER'] ?? getenv('MYSQLUSER') ?? 'root';
        $this->password = $_ENV['MYSQLPASSWORD'] ?? $_SERVER['MYSQLPASSWORD'] ?? getenv('MYSQLPASSWORD') ?? 'admineduardox624';
        $this->port     = $_ENV['MYSQLPORT'] ?? $_SERVER['MYSQLPORT'] ?? getenv('MYSQLPORT') ?? '3306';
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
            // DIAGNÓSTICO EN CASO DE FALLO
            // Si esto falla, veremos exactamente qué valores intentó usar.
            
            echo "<div style='background:#ffebee; border:2px solid #ef5350; padding:20px; font-family:sans-serif; border-radius:8px;'>";
            echo "<h2 style='color:#c62828; margin-top:0;'>❌ Error Crítico de Conexión</h2>";
            echo "<p style='font-size:1.1em;'><strong>El sistema intentó conectarse con estos datos:</strong></p>";
            echo "<ul>";
            echo "<li><strong>Host:</strong> " . ($this->host == 'localhost' ? '<span style="color:red">localhost (MAL - Debería ser un dominio de Railway)</span>' : "<span style='color:green'>{$this->host}</span>") . "</li>";
            echo "<li><strong>Puerto:</strong> " . ($this->port == '3306' ? '<span style="color:orange">3306 (Sospechoso - Railway usa puertos aleatorios)</span>' : "<span style='color:green'>{$this->port}</span>") . "</li>";
            echo "<li><strong>Usuario:</strong> {$this->username}</li>";
            echo "<li><strong>Base de Datos:</strong> {$this->db_name}</li>";
            echo "</ul>";
            echo "<hr>";
            echo "<p><strong>Detalle Técnico:</strong> " . $exception->getMessage() . "</p>";
            echo "</div>";
            die();
        }
        
        return $this->conn;
    }
}
?>