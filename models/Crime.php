<?php
require_once 'config.php';

class Crime {
    public $id, $crime_type, $description, $location, $date_reported, $reported_by, $status;
    
    // Save new or updated record
    public function save() {
        $conn = self::getConnection();
        if (isset($this->id)) {
            // Update record
            $stmt = $conn->prepare("UPDATE crimes SET crime_type = ?, description = ?, location = ?, status = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $this->crime_type, $this->description, $this->location, $this->status, $this->id);
            $stmt->execute();
        } else {
            // Insert new record
            $stmt = $conn->prepare("INSERT INTO crimes (crime_type, description, location, date_reported, reported_by, status) VALUES (?, ?, ?, ?, ?, ?)");
            $date_reported = $this->date_reported;
            $stmt->bind_param("ssssis", $this->crime_type, $this->description, $this->location, $date_reported, $this->reported_by, $this->status);
            $stmt->execute();
            $this->id = $stmt->insert_id;
        }
        $stmt->close();
        $conn->close();
    }
    
    // Get connection from a common method or use dependency injection
    private static function getConnection() {
        // Create connection using config settings
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }
    
    // Static method to retrieve all records
    public static function getAll() {
        $conn = self::getConnection();
        $result = $conn->query("SELECT * FROM crimes ORDER BY date_reported DESC");
        $crimes = [];
        while ($row = $result->fetch_assoc()) {
            $crime = new Crime();
            foreach ($row as $key => $value) {
                $crime->$key = $value;
            }
            $crimes[] = $crime;
        }
        $conn->close();
        return $crimes;
    }
}
?>
