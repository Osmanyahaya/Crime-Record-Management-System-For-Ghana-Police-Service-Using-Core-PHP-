<?php
require_once '../config/db.php';

class UserController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function fetchUsers($search = '', $role = '', $limit = 10, $offset = 0)
{
    $sql = "SELECT * FROM users WHERE 1=1";
    $params = [];
    $types = [];

    if (!empty($search)) {
        $sql .= " AND (name LIKE ? OR email LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $types[] = PDO::PARAM_STR;
        $types[] = PDO::PARAM_STR;
    }

    if (!empty($role)) {
        $sql .= " AND role = ?";
        $params[] = $role;
        $types[] = PDO::PARAM_STR;
    }

    $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $params[] = (int)$limit;
    $params[] = (int)$offset;
    $types[] = PDO::PARAM_INT;
    $types[] = PDO::PARAM_INT;

    $stmt = $this->pdo->prepare($sql);

    // Bind parameters with types
    foreach ($params as $index => $value) {
        $stmt->bindValue($index + 1, $value, $types[$index]);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    public function countUsers($search = '', $role = '')
    {
        $sql = "SELECT COUNT(*) FROM users WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (name LIKE ? OR email LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if (!empty($role)) {
            $sql .= " AND role = ?";
            $params[] = $role;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
}
