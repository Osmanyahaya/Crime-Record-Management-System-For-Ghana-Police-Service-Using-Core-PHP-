<?php
class ReportController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function store($crimeId, $reportText, $userId)
    {
        $stmt = $this->pdo->prepare("INSERT INTO reports (crime_id, report_text, submitted_by) VALUES (?, ?, ?)");
        return $stmt->execute([$crimeId, $reportText, $userId]);
    }

    public function getByCrimeId($crimeId)
    {
        $stmt = $this->pdo->prepare("
            SELECT r.*, u.name AS officer_name 
            FROM reports r 
            JOIN users u ON r.submitted_by = u.id 
            WHERE r.crime_id = ?
            ORDER BY r.created_at DESC
        ");
        $stmt->execute([$crimeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function handleStoreRequest($postData, $user)
{
    $crimeId = $postData['crime_id'] ?? null;
    $reportText = trim($postData['report_text'] ?? '');
    $userId = $user['id'];

    if (!$crimeId || empty($reportText)) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: ../views/crime_detail.php?id=" . $crimeId);
        exit();
    }

    $saved = $this->store($crimeId, $reportText, $userId);

    if ($saved) {
        $_SESSION['success'] = "Report submitted successfully.";
    } else {
        $_SESSION['error'] = "Failed to submit report.";
    }

    header("Location: ../views/crime_details.php?id=" . $crimeId);
    exit();
}

public function getReportsByCrime($crimeId)
{
    $stmt = $this->pdo->prepare("
        SELECT r.*, u.name AS officer_name, u.role
        FROM reports r
        JOIN users u ON r.submitted_by = u.id
        WHERE r.crime_id = ?
        ORDER BY r.created_at DESC
    ");
    $stmt->execute([$crimeId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function getReportById($id)
{
    $stmt = $this->pdo->prepare("SELECT * FROM reports WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function updateReport($id, $text)
{
    $stmt = $this->pdo->prepare("UPDATE reports SET report_text = ? WHERE id = ?");
    $stmt->execute([$text, $id]);
}

public function deleteReport($id)
{
    $stmt = $this->pdo->prepare("DELETE FROM reports WHERE id = ?");
    $stmt->execute([$id]);
}

public function getCIDReports()
{
    $stmt = $this->pdo->prepare("
        SELECT c.*, u.name AS officer_name, cr.title, cr.id AS crime_identity 
        FROM reports c
        JOIN users u ON c.submitted_by = u.id
        JOIN crimes cr ON c.crime_id = cr.id
        ORDER BY c.created_at DESC
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'submit_cid_report') {
    require_once '../config/db.php';
    session_start();

    $crime_id = $_POST['crime_id'];
    $findings = $_POST['report_text'];
    $officer_id = $_SESSION['user']['id'];
    $stmt = $pdo->prepare("INSERT INTO reports (crime_id, submitted_by, report_text) VALUES (?, ?, ?)");
    $stmt->execute([$crime_id, $officer_id, $findings,]);

    $_SESSION['success'] = "CID Report submitted successfully.";
    header("Location: ../views/crime_details.php?id=" . $crime_id);
    exit();
}
