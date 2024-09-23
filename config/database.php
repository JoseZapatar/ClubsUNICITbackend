<?php
class Database {
    private $host = "sql3.freesqldatabase.com";
    private $db_name = "sql3732711";
    private $username = "sql3732711";
    private $password = "v24vIxG2Z7";
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