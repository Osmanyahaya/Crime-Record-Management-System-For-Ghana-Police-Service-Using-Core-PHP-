<?php
class SuspectController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function store($data)
    {
        $name = trim($data['name']);
        $crime_id = intval($data['crime_id']);

        if (!$name || !$crime_id) {
            $_SESSION['error'] = "Suspect name and crime ID are required.";
            header("Location: ../views/create_suspect.php?crime_id=$crime_id");
            exit();
        }
        $photoName = null;

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photoName = uniqid('suspect_') . '.' . $ext;
        move_uploaded_file($_FILES['photo']['tmp_name'], "../uploads/suspects/$photoName");
            }

        $stmt = $this->pdo->prepare("INSERT INTO suspects (crime_id, name, age, gender, contact, address, status,photo) VALUES (?, ?, ?, ?, ?, ?, ?,?)");
        $stmt->execute([
            $crime_id,
            $name,
            intval($data['age']),
            trim($data['gender']),
            trim($data['contact']),
            trim($data['address']),
            trim($data['status']),
            $photoName
        ]);

        $_SESSION['success'] = "Suspect added.";
        header("Location: ../views/crime_details.php?id=$crime_id");
        exit();
    }

    public function getByCrime($crime_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM suspects WHERE crime_id = ?");
        $stmt->execute([$crime_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM suspects WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE suspects SET name = ?, age = ?, gender = ?, contact = ?, address = ?, status = ?,photo = ? WHERE id = ?");
        $stmt->execute([
            trim($data['name']),
            intval($data['age']),
            trim($data['gender']),
            trim($data['contact']),
            trim($data['address']),
            trim($data['status']),
            $data['photo'],
            $id
        ]);

        $_SESSION['success'] = "Suspect updated.";
        header("Location: ../views/crime_details.php?id=" .  $this->getCrimeIdForSuspect($id));
        exit();
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("SELECT crime_id FROM suspects WHERE id = ?");
        $stmt->execute([$id]);
        $suspect = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$suspect) {
            $_SESSION['error'] = "Suspect not found.";
            header("Location: ../views/dashboard.php");
            exit();
        }

        $stmt = $this->pdo->prepare("DELETE FROM suspects WHERE id = ?");
        $stmt->execute([$id]);

        $_SESSION['success'] = "Suspect deleted.";
        header("Location: ../views/crime_details.php?id=" . $suspect['crime_id']);
        exit();
    }

    public function getCrimeIdForSuspect($id)
{
    $stmt = $this->pdo->prepare("SELECT crime_id FROM suspects WHERE id = ?");
    $stmt->execute([$id]);
    $crimeId = $stmt->fetchColumn();

    if (!$crimeId) {
        // Fallback or throw error
        return null;
    }
    
    return $crimeId;
   
}
}
