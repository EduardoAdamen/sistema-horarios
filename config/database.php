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

        // 1) Railway: MYSQL_URL disponible
        $mysql_url = getenv("MYSQL_URL");

        if (!empty($mysql_url)) {
            $url = parse_url($mysql_url);

            $this->host     = $url['host'] ?? null;
            $this->username = $url['user'] ?? null;
            $this->password = $url['pass'] ?? null;
            $this->port     = $url['port'] ?? 3306;
            $this->db_name  = isset($url['path']) ? ltrim($url['path'], '/') : null;
        }

        // 2) Railway: variables separadas
        elseif (!empty(getenv("MYSQLHOST"))) {
            $this->host     = getenv("MYSQLHOST");
            $this->db_name  = getenv("MYSQLDATABASE");
            $this->username = getenv("MYSQLUSER");
            $this->password = getenv("MYSQLPASSWORD");
            $this->port     = getenv("MYSQLPORT");
        }

        // 3) Modo Local
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
