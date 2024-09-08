<?php
class Database {
    private $host = "sql3.freesqldatabase.com";
    private $db_name = "sql3729814";
    private $username = "sql3729814";
    private $password = "c737KhBII";
    private $conn;

    // Conectar a la base de datos
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo"Conectado correctamente";
        } catch (PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>