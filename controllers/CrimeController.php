<?php
require_once '../config/db.php';
session_start();

class CrimeController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Inside CrimeController class
public function index()
{
    $stmt = $this->pdo->query("SELECT c.*, u.name AS officer_name FROM crimes c
        LEFT JOIN users u ON c.officer_id = u.id
        ORDER BY c.created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    public function store($data, $user)
    {
        $case_number = 'CASE-' . strtoupper(uniqid());
        $title = $data['title'] ?? '';
        $description = $data['description'] ?? '';
        $crime_type = $data['crime_type'] ?? '';
        $location = $data['location'] ?? '';
        $officer_id = $user['id'];
        
        $stmt = $this->pdo->prepare("INSERT INTO crimes 
            (case_number, title, description, crime_type, location, officer_id) 
            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $case_number,
            $title,
            $description,
            $crime_type,
            $location,
            $officer_id
        ]);
         // ✅ Get the last inserted ID (crime ID)
        $crimeId = $this->pdo->lastInsertId();

   
         $_SESSION['message'] = "Crime report submitted successfully (Case Number: $case_number)";

            header("Location: ../views/create_victim.php?crime_id=" . $crimeId);
            exit();

        // $_SESSION['message'] = "Crime report submitted successfully (Case Number: $case_number)";
        // header("Location: ../views/dashboard.php");
        // exit();
    }

public function show($id)
{
    $stmt = $this->pdo->prepare("SELECT c.*, u.name AS officer_name FROM crimes c 
        LEFT JOIN users u ON c.officer_id = u.id
        WHERE c.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get list of officers for dropdown
public function getAllOfficers()
{
    $stmt = $this->pdo->query("SELECT id, name FROM users WHERE role = 'officer'");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function filterCrimes($status, $officer_id, $date_from, $date_to, $limit = 10, $offset = 0)
{
    $sql = "SELECT c.*, u.name AS officer_name 
            FROM crimes c 
            LEFT JOIN users u ON c.officer_id = u.id 
            WHERE 1";
    $params = [];

    if (!empty($status)) {
        $sql .= " AND c.status = ?";
        $params[] = $status;
    }

    if (!empty($officer_id)) {
        $sql .= " AND c.officer_id = ?";
        $params[] = $officer_id;
    }

    if (!empty($date_from)) {
        $sql .= " AND c.date_reported >= ?";
        $params[] = $date_from;
    }

    if (!empty($date_to)) {
        $sql .= " AND c.date_reported <= ?";
        $params[] = $date_to;
    }

    
    $sql .= " ORDER BY c.date_reported DESC LIMIT $limit OFFSET $offset";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// Count total filtered records (for pagination)
public function countFilteredCrimes($status, $officer_id, $date_from, $date_to)
{
    $sql = "SELECT COUNT(*) FROM crimes c WHERE 1";
    $params = [];

    if (!empty($status)) {
        $sql .= " AND c.status = ?";
        $params[] = $status;
    }

    if (!empty($officer_id)) {
        $sql .= " AND c.officer_id = ?";
        $params[] = $officer_id;
    }

    if (!empty($date_from)) {
        $sql .= " AND c.date_reported >= ?";
        $params[] = $date_from;
    }

    if (!empty($date_to)) {
        $sql .= " AND c.date_reported <= ?";
        $params[] = $date_to;
    }

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchColumn();
}
public function updateCrime($data)
{
    $sql = "UPDATE crimes SET 
                case_number = :case_number,
                title = :title,
                description = :description,
                crime_type = :crime_type,
                location = :location,
                status = :status
            WHERE id = :id";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        ':case_number' => $data['case_number'],
        ':title'       => $data['title'],
        ':description' => $data['description'],
        ':crime_type'  => $data['crime_type'],
        ':location'    => $data['location'],
        ':status'      => $data['status'],
        ':id'          => $data['id']
    ]);
}


public function deleteCrime($id)
{
    $stmt = $this->pdo->prepare("DELETE FROM crimes WHERE id = ?");
    $stmt->execute([$id]);
}


}

/*// 🔁 Controller dispatcher:
$controller = new CrimeController($pdo);

$action = $_GET['action'] ?? '';

if ($action === 'store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user'])) {
        $_SESSION['error'] = "Unauthorized!";
        header("Location: ../index.php");
        exit();
    }

    $controller->store($_POST, $_SESSION['user']);
} else {
    http_response_code(404);
    echo "Invalid action.";
}*/

