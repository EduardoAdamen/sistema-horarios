<?php
class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $port;
    private $charset = 'utf8mb4';
    public $conn;

    public function __construct() {
        // ================================
        // 1) Si Railway usa MYSQL_URL
        // ================================
        if (!empty($_ENV['MYSQL_URL'])) {
            $url = parse_url($_ENV['MYSQL_URL']);

            $this->host     = $url['host'];
            $this->username = $url['user'];
            $this->password = $url['pass'];
            $this->port     = $url['port'];
            $this->db_name  = ltrim($url['path'], '/');
        }
        // ================================
        // 2) Si Railway expuso las variables por separado
        // ================================
        elseif (!empty($_ENV['MYSQLHOST'])) {
            $this->host     = $_ENV['MYSQLHOST'];
            $this->db_name  = $_ENV['MYSQLDATABASE'];
            $this->username = $_ENV['MYSQLUSER'];
            $this->password = $_ENV['MYSQLPASSWORD'];
            $this->port     = $_ENV['MYSQLPORT'];
        }
        // ================================
        // 3) Modo Local (XAMPP)
        // ================================
        else {
            $this->host     = 'localhost';
            $this->db_name  = 'sistema_horarios';
            $this->username = 'root';
            $this->password = 'admineduardox624';
            $this->port     = '3306';
        }
    }

    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset={$this->charset}";
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);

        } catch(PDOException $exception) {
            error_log("Error de conexión: " . $exception->getMessage());
            die("Error de conexión a la base de datos.");
        }

        return $this->conn;
    }
}
?>
