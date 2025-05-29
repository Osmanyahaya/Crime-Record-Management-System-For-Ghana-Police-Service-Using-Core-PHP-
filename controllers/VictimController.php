<?php
class VictimController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function store($data)
    {
        $name = trim($data['name']);
        $age = intval($data['age']);
        $gender = trim($data['gender']);
        $contact = trim($data['contact']);
        $address = trim($data['address']);
        $statement = trim($data['statement']);
        $crime_id = intval($data['crime_id']);

        if (empty($name) || empty($crime_id)) {
            $_SESSION['error'] = "Name and Crime ID are required.";
            header("Location: ../views/create_victim.php?crime_id=$crime_id");
            exit();
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO victims (crime_id, name, age, gender, contact, address, statement)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$crime_id, $name, $age, $gender, $contact, $address, $statement]);

        $_SESSION['success'] = "Victim added successfully.";
        header("Location: ../views/crime_details.php?id=$crime_id");
        exit();
    }

    public function getByCrime($crime_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM victims WHERE crime_id = ?");
        $stmt->execute([$crime_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM victims WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("
            UPDATE victims SET name = ?, age = ?, gender = ?, contact = ?, address = ?, statement = ?
            WHERE id = ?
        ");
        $stmt->execute([
            trim($data['name']),
            intval($data['age']),
            trim($data['gender']),
            trim($data['contact']),
            trim($data['address']),
            trim($data['statement']),
            $id
        ]);

        $_SESSION['success'] = "Victim updated.";
        header("Location: ../views/crime_details.php?id=" . $data['crime_id']);
        exit();
    }

    public function delete($id)
    {
        // Get crime_id before deletion
        $stmt = $this->pdo->prepare("SELECT crime_id FROM victims WHERE id = ?");
        $stmt->execute([$id]);
        $victim = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$victim) {
            $_SESSION['error'] = "Victim not found.";
            header("Location: ../views/dashboard.php");
            exit();
        }

        $stmt = $this->pdo->prepare("DELETE FROM victims WHERE id = ?");
        $stmt->execute([$id]);

        $_SESSION['success'] = "Victim deleted.";
        header("Location: ../views/crime_details.php?id=" . $victim['crime_id']);
        exit();
    }
}
