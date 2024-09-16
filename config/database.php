<?php
class Database {
    private $host = "sql3.freesqldatabase.com";
    private $db_name = "sql3731406";
    private $username = "sql3731406";
    private $password = "CRe5asLEWP";
    private $conn;

    // Conectar a la base de datos
    public function getConnection(): PDO {
        $this->conn = null;

        try {
            $this->conn = new PDO(dsn: "mysql:host=" . $this->host . ";dbname=" . $this->db_name, username: $this->username, password: $this->password);
            $this->conn->setAttribute(attribute: PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);
            
        } catch (PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>