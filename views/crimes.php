<?php
require_once '../config/db.php';
require_once '../controllers/CrimeController.php';

// Authentication check
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}

$controller = new CrimeController($pdo);


$status = $_GET['status'] ?? '';
$officer_id = $_GET['officer_id'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';

// Fetch data
$officers = $controller->getAllOfficers();
$crimes = $controller->filterCrimes($status, $officer_id, $date_from, $date_to);
// Pagination settings
$limit = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Fetch filtered crimes and total count
$total = $controller->countFilteredCrimes($status, $officer_id, $date_from, $date_to);
$crimes = $controller->filterCrimes($status, $officer_id, $date_from, $date_to, $limit, $offset);
$total_pages = ceil($total / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reported Crimes - Crime Management System</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .message {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 4px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .filters-form {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border: 1px solid #dee2e6;
        }
        
        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: end;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            min-width: 150px;
        }
        
        .form-group label {
            font-weight: 600;
            margin-bottom: 5px;
            color: #495057;
        }
        
        .form-group select,
        .form-group input {
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.2s;
        }
        
        .btn:hover {
            background-color: #0056b3;
        }
        
        .crimes-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .crimes-table th {
            background-color: #343a40;
            color: white;
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
        }
        
        .crimes-table td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .crimes-table tr:hover {
            background-color: #f8f9fa;
        }
        
        .status {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .status.open {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status.under-investigation {
            background-color: #cce7ff;
            color: #004085;
        }
        
        .status.closed {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        
        .action-link {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }
        
        .action-link:hover {
            text-decoration: underline;
        }
        
        .page-header {
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #dee2e6;
        }
        
        .page-header h2 {
            color: #343a40;
            margin: 0;
            font-size: 28px;
        }
        
        .no-crimes {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-style: italic;
        }
    </style>
    <?php include 'header.php'; ?>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h2>Reported Crimes</h2>
        </div>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="message">
                <?php echo htmlspecialchars($_SESSION['message']); ?>
                <?php unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
        <div class="alert alert-success">Crime record updated successfully!</div>
       <?php endif; ?>
       <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
<div class="alert alert-success">Crime record deleted successfully!</div>
<?php endif; ?>

        <!-- Filters Form -->
        <form method="GET" class="filters-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="status">Status:</label>
                    <select name="status" id="status">
                        <option value="">-- All Statuses --</option>
                        <option value="open" <?= $status === 'open' ? 'selected' : '' ?>>Open</option>
                        <option value="under investigation" <?= $status === 'under investigation' ? 'selected' : '' ?>>Under Investigation</option>
                        <option value="closed" <?= $status === 'closed' ? 'selected' : '' ?>>Closed</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="officer_id">Assigned Officer:</label>
                    <select name="officer_id" id="officer_id">
                        <option value="">-- All Officers --</option>
                        <?php foreach ($officers as $officer): ?>
                            <option value="<?= htmlspecialchars($officer['id']) ?>" 
                                    <?= $officer_id == $officer['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($officer['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="date_from">Date From:</label>
                    <input type="date" 
                           name="date_from" 
                           id="date_from" 
                           value="<?= htmlspecialchars($date_from) ?>">
                </div>

                <div class="form-group">
                    <label for="date_to">Date To:</label>
                    <input type="date" 
                           name="date_to" 
                           id="date_to" 
                           value="<?= htmlspecialchars($date_to) ?>">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn">Apply Filters</button>
                </div>
            </div>
        </form>

        <!-- Crimes Table -->
        <?php if (empty($crimes)): ?>
            <div class="no-crimes">
                <p>No crimes found matching the selected criteria.</p>
            </div>
        <?php else: ?>
            <table class="crimes-table">
                <thead>
                    <tr>
                        <th>Case #</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Assigned Officer</th>
                        <th>Date Reported</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($crimes as $crime): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($crime['case_number']) ?></strong>
                            </td>
                            <td><?= htmlspecialchars($crime['title']) ?></td>
                            <td>
                                <span class="status <?= strtolower(str_replace(' ', '-', $crime['status'])) ?>">
                                    <?= htmlspecialchars(ucfirst($crime['status'])) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($crime['officer_name'] ?? 'Unassigned') ?></td>
                            <td><?= date('M j, Y', strtotime($crime['date_reported'])) ?></td>
                            <td>
                                 <a href="edit_crime.php?id=<?= $crime['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="crime_details.php?id=<?= htmlspecialchars($crime['id']) ?>" 
                                   class="btn btn-sm btn-primary">
                                    View
                                </a>
                               

                                <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                                 <a href="../handlers/delete_crime.php?id=<?= $crime['id'] ?>" 
                                    class="btn btn-sm btn-danger" 
                                    onclick="return confirm('Are you sure you want to delete this crime record?');">
                                    Delete
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if ($total_pages > 1): ?>
    <div class="pagination-container">
        <nav aria-label="Page navigation" class="pagination-nav">
            <ul class="pagination">
                <!-- Previous Page Button -->
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>" 
                           class="page-link page-prev" 
                           aria-label="Previous page">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="page-item disabled">
                        <span class="page-link page-prev disabled" aria-label="Previous page">
                            <span aria-hidden="true">&laquo;</span>
                        </span>
                    </li>
                <?php endif; ?>

                <!-- Page Numbers -->
                <?php
                // Calculate pagination range for better UX with many pages
                $start_page = max(1, $page - 2);
                $end_page = min($total_pages, $page + 2);
                
                // Adjust range if we're near the beginning or end
                if ($page <= 3) {
                    $end_page = min($total_pages, 5);
                }
                if ($page > $total_pages - 3) {
                    $start_page = max(1, $total_pages - 4);
                }
                ?>

                <!-- First page and ellipsis if needed -->
                <?php if ($start_page > 1): ?>
                    <li class="page-item">
                        <a href="?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>" 
                           class="page-link">1</a>
                    </li>
                    <?php if ($start_page > 2): ?>
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Page number range -->
                <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <?php if ($i == $page): ?>
                            <span class="page-link current" aria-current="page">
                                <?= $i ?>
                                <span class="sr-only">(current)</span>
                            </span>
                        <?php else: ?>
                            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" 
                               class="page-link">
                               <?= $i ?>
                            </a>
                        <?php endif; ?>
                    </li>
                <?php endfor; ?>

                <!-- Last page and ellipsis if needed -->
                <?php if ($end_page < $total_pages): ?>
                    <?php if ($end_page < $total_pages - 1): ?>
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    <?php endif; ?>
                    <li class="page-item">
                        <a href="?<?= http_build_query(array_merge($_GET, ['page' => $total_pages])) ?>" 
                           class="page-link"><?= $total_pages ?></a>
                    </li>
                <?php endif; ?>

                <!-- Next Page Button -->
                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>" 
                           class="page-link page-next" 
                           aria-label="Next page">
                            <span class="sr-only">Next</span>
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="page-item disabled">
                        <span class="page-link page-next disabled" aria-label="Next page">
                            <span aria-hidden="true">&raquo;</span>
                        </span>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>

        <!-- Page Info -->
        <div class="pagination-info">
            <span class="page-info-text">
                Page <?= $page ?> of <?= $total_pages ?>
                <?php if (isset($total_records)): ?>
                    (<?= number_format($total_records) ?> total records)
                <?php endif; ?>
            </span>
        </div>
    </div>

    <style>
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            padding: 20px 0;
            border-top: 1px solid #dee2e6;
        }

        .pagination-nav {
            flex: 1;
            display: flex;
            justify-content: center;
        }

        .pagination {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 4px;
        }

        .page-item {
            display: inline-block;
        }

        .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 8px 12px;
            text-decoration: none;
            color: #007bff;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            position: relative;
        }

        .page-link:hover {
            background-color: #e9ecef;
            border-color: #adb5bd;
            text-decoration: none;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .page-item.active .page-link,
        .page-link.current {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0,123,255,0.3);
        }

        .page-item.active .page-link:hover,
        .page-link.current:hover {
            background-color: #0056b3;
            border-color: #0056b3;
            transform: none;
        }

        .page-item.disabled .page-link,
        .page-link.disabled {
            color: #6c757d;
            background-color: #fff;
            border-color: #dee2e6;
            cursor: not-allowed;
            pointer-events: none;
            opacity: 0.6;
        }

        .page-prev,
        .page-next {
            font-size: 16px;
            font-weight: 600;
        }

        .pagination-info {
            color: #6c757d;
            font-size: 14px;
            text-align: right;
            min-width: 200px;
        }

        .page-info-text {
            font-weight: 500;
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .pagination-container {
                flex-direction: column;
                gap: 15px;
            }

            .pagination-info {
                text-align: center;
                min-width: auto;
            }

            .page-link {
                min-width: 36px;
                height: 36px;
                padding: 6px 10px;
                font-size: 13px;
            }

            .pagination {
                gap: 2px;
            }

            /* Hide some page numbers on mobile */
            .pagination .page-item:not(.active):not(:first-child):not(:last-child):not(:nth-child(2)):not(:nth-last-child(2)) {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .page-link {
                min-width: 32px;
                height: 32px;
                padding: 4px 8px;
                font-size: 12px;
            }
        }
    </style>
<?php endif; ?>
        <?php endif; ?>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>