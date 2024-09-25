<?php
// Incluir la clase de conexión a la base de datos
include_once '../config/database.php';

class UserClub
{
    private $conn;

    // Constructor que establece la conexión con la base de datos
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Método para asociar un usuario con un club
    public function registerUserToClub($userId, $clubId)
{
    try {
        // Verificar si el usuario ya está registrado en el club
        $checkQuery = "SELECT * FROM user_club WHERE IdUser = :userId AND IdClub = :clubId";
        $stmt = $this->conn->prepare($checkQuery);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':clubId', $clubId);
        $stmt->execute();

        // Si ya existe el registro, devolver un mensaje
        if ($stmt->rowCount() > 0) {
            return ["success" => false, "message" => "El usuario ya está registrado en este club."];
        }

        // Si no está registrado, proceder con la inserción
        $query = "INSERT INTO user_club (IdUser, IdClub) VALUES (:userId, :clubId)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':clubId', $clubId);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return ["success" => true, "message" => "Usuario registrado en el club exitosamente."];
        } else {
            return ["success" => false, "message" => "Error al registrar usuario en el club."];
        }
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Error en la consulta: " . $e->getMessage()];
    }
}


    // Método para eliminar la relación entre un usuario y un club
    public function unregisterUserFromClub($userId, $clubId)
    {
        try {
            // Consulta SQL para eliminar una relación
            $query = "DELETE FROM user_club WHERE IdUser = :userId AND IdClub = :clubId";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':clubId', $clubId);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                return ["success" => true, "message" => "Usuario desvinculado del club exitosamente"];
            } else {
                return ["success" => false, "message" => "Error al desvincular usuario del club"];
            }
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Error en la consulta: " . $e->getMessage()];
        }
    }

    // Método para verificar si un usuario ya está registrado en un club
    public function isUserRegisteredToClub($userId, $clubId)
    {
        try {
            // Consulta SQL para verificar la existencia de una relación
            $query = "SELECT * FROM user_club WHERE IdUser = :userId AND IdClub = :clubId";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':clubId', $clubId);

            // Ejecutar la consulta
            $stmt->execute();

            // Verificar si existe la relación
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Error en la consulta: " . $e->getMessage()];
        }
    }
}