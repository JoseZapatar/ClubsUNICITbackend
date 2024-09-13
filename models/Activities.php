<?php
class Activities {
    private $conn;
    public $idActivities;
    public $description;
    public $activityName;
    public $activityDate;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function readActivities(): mixed {
        $sql = "SELECT * FROM activities";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    public function createActivity(): mixed {
        $sql = "INSERT INTO activities (Description, ActivityName, ActivityDate) 
                VALUES (:description, :activityName, :activityDate)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':activityName', $this->activityName);
        $stmt->bindParam(':activityDate', $this->activityDate);
        return $stmt->execute();
    }

    public function updateActivity(): mixed {
        $sql = "UPDATE activities SET Description = :description, ActivityName = :activityName, 
                ActivityDate = :activityDate WHERE IdActivities = :idActivities";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':activityName', $this->activityName);
        $stmt->bindParam(':activityDate', $this->activityDate);
        $stmt->bindParam(':idActivities', $this->idActivities, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteActivity(): mixed {
        $sql = "DELETE FROM activities WHERE IdActivities = :idActivities";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':idActivities', $this->idActivities, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
