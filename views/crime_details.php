<?php
require_once '../config/db.php';
require_once '../controllers/CrimeController.php';
require_once '../controllers/ReportController.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}

$controller = new CrimeController($pdo);
$reportController = new ReportController($pdo);


// Ensure an ID is passed
if (!isset($_GET['id'])) {
    echo "No crime ID provided.";
    exit();
}

$crime = $controller->show($_GET['id']);
$reports = $reportController->getByCrimeId($_GET['id']);
require_once '../controllers/VictimController.php';
$victimController = new VictimController($pdo);
$victims = $victimController->getByCrime($_GET['id']);
require_once '../controllers/SuspectController.php';
$suspectController = new SuspectController($pdo);
$suspects = $suspectController->getByCrime($_GET['id']);

if (!$crime) {
    echo "Crime not found.";
    exit();
}

// Get status color class
function getStatusColor($status) {
    switch(strtolower($status)) {
        case 'open': return 'status-open';
        case 'investigating': return 'status-investigating';
        case 'closed': return 'status-closed';
        case 'resolved': return 'status-resolved';
        default: return 'status-default';
    }
}

// Get crime type icon
function getCrimeIcon($type) {
    switch(strtolower($type)) {
        case 'theft': return '🔓';
        case 'assault': return '⚠️';
        case 'burglary': return '🏠';
        case 'fraud': return '💳';
        case 'vandalism': return '🔨';
        default: return '📋';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crime Details - Case #<?= htmlspecialchars($crime['case_number']) ?></title>
    <?php include 'header.php'; ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
           
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .header {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.1;
        }

        .header h1 {
            margin: 0;
            font-size: 2.5em;
            font-weight: 300;
            position: relative;
            z-index: 1;
        }

        .case-number {
            font-size: 1.2em;
            opacity: 0.9;
            margin-top: 10px;
            position: relative;
            z-index: 1;
        }

        .content {
            padding: 40px;
        }

        .crime-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .detail-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid #e8ecf4;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .detail-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .detail-item {
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .detail-item:last-child {
            margin-bottom: 0;
        }

        .detail-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2em;
            flex-shrink: 0;
        }

        .detail-content {
            flex: 1;
        }

        .detail-label {
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.9em;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .detail-value {
            color: #555;
            font-size: 1.1em;
            line-height: 1.5;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9em;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-open { background: #e74c3c; color: white; }
        .status-investigating { background: #f39c12; color: white; }
        .status-closed { background: #27ae60; color: white; }
        .status-resolved { background: #3498db; color: white; }
        .status-default { background: #95a5a6; color: white; }

        .crime-type-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9em;
        }

        .section {
            margin-top: 50px;
            padding-top: 30px;
            border-top: 2px solid #e8ecf4;
        }

        .section-title {
            color: #2c3e50;
            font-size: 1.8em;
            font-weight: 600;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .report-form {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid #e8ecf4;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 1.1em;
        }

        .form-textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #e8ecf4;
            border-radius: 10px;
            font-family: inherit;
            font-size: 1em;
            line-height: 1.6;
            resize: vertical;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1em;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(231, 76, 60, 0.3);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #95a5a6, #7f8c8d);
            color: white;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(149, 165, 166, 0.3);
        }

        .reports-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .report-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e8ecf4;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .report-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }

        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .report-author {
            font-weight: 600;
            color: #2c3e50;
            font-size: 1.1em;
        }

        .report-role {
            background: #ecf0f1;
            color: #7f8c8d;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.8em;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .report-text {
            color: #555;
            line-height: 1.6;
            margin-bottom: 15px;
            font-size: 1em;
        }

        .report-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #7f8c8d;
            font-size: 0.9em;
        }

        .report-actions {
            display: flex;
            gap: 10px;
        }

        .btn-small {
            padding: 6px 15px;
            font-size: 0.85em;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1em;
            margin-top: 40px;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: #764ba2;
            transform: translateX(-5px);
        }

        .no-reports {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
            font-size: 1.1em;
            background: #f8f9fa;
            border-radius: 15px;
            border: 2px dashed #bdc3c7;
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                border-radius: 15px;
            }
            
            .content {
                padding: 20px;
            }
            
            .crime-details {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .header h1 {
                font-size: 2em;
            }
            
            .report-header {
                flex-direction: column;
                gap: 10px;
            }
            
            .report-actions {
                justify-content: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-shield-alt"></i> Crime Details</h1>
            <div class="case-number">Case #<?= htmlspecialchars($crime['case_number']) ?></div>
        </div>
        
        <div class="content">
            <div class="crime-details">
                <div class="detail-card">
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Title</div>
                            <div class="detail-value"><?= htmlspecialchars($crime['title']) ?></div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-align-left"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Description</div>
                            <div class="detail-value"><?= nl2br(htmlspecialchars($crime['description'])) ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="detail-card">
                    <div class="detail-item">
                        <div class="detail-icon">
                            <?= getCrimeIcon($crime['crime_type']) ?>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Crime Type</div>
                            <div class="detail-value">
                                <span class="crime-type-badge">
                                    <?= getCrimeIcon($crime['crime_type']) ?>
                                    <?= htmlspecialchars($crime['crime_type']) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Location</div>
                            <div class="detail-value"><?= htmlspecialchars($crime['location']) ?></div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                <span class="status-badge <?= getStatusColor($crime['status']) ?>">
                                    <?= htmlspecialchars($crime['status']) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="detail-card">
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Reported By</div>
                            <div class="detail-value"><?= htmlspecialchars($crime['officer_name']) ?></div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Date Reported</div>
                            <div class="detail-value"><?= date('F j, Y \a\t g:i A', strtotime($crime['date_reported'])) ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <h3>Victims</h3>
  <table class="table">
    <tr>
        <th>Name</th><th>Age</th><th>Gender</th><th>Contact</th><th>Statement</th><th>Action</th>
    </tr>
    <?php foreach ($victims as $victim): ?>
        <tr>
            <td><?= htmlspecialchars($victim['name']) ?></td>
            <td><?= htmlspecialchars($victim['age']) ?></td>
            <td><?= htmlspecialchars($victim['gender']) ?></td>
            <td><?= htmlspecialchars($victim['contact']) ?></td>
            <td><?= nl2br(htmlspecialchars($victim['statement'])) ?></td>
             <td>
                <a href="../views/edit_victim.php?id=<?= $victim['id'] ?>">Edit</a> | 
                <a href="../handlers/delete_victim.php?id=<?= $victim['id'] ?>" onclick="return confirm('Delete victim?')">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<a class="btn btn-primary" href="create_victim.php?crime_id=<?= $crime['id'] ?>">Add Victim</a>
<hr>
<h3>Suspects</h3>
<table class="table">
<tr>
    <th>Name</th><th>Gender</th><th>Age</th><th>Contact</th><th>Photo</th><th>Status</th><th>Action</th>
</tr>
<?php foreach ($suspects as $s): ?>
<tr>
    <td><?= htmlspecialchars($s['name']) ?></td>
    <td><?= htmlspecialchars($s['gender']) ?></td>
    <td><?= htmlspecialchars($s['age']) ?></td>
    <td><?= htmlspecialchars($s['contact']) ?></td>
    <td>
<?php if (!empty($s['photo'])): ?>
    <img src="../uploads/suspects/<?= $s['photo'] ?>" alt="Photo" width="50">
<?php else: ?>
    No photo
<?php endif; ?>
</td>
    <td><?= htmlspecialchars($s['status']) ?></td>
    <td>
        <a href="../views/edit_suspect.php?id=<?= $s['id'] ?>">Edit</a> |
        <a href="../handlers/delete_suspect.php?id=<?= $s['id'] ?>" onclick="return confirm('Delete suspect?')">Delete</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
<a class="btn btn-primary" href="create_suspect.php?crime_id=<?= $crime['id'] ?>">Add Suspect</a>

            <div class="section">
                <h2 class="section-title">
                    <i class="fas fa-plus-circle"></i>
                    Submit a Report
                </h2>
                
                <div class="report-form">
                    <form action="../handlers/add_report.php" method="POST">
                        <input type="hidden" name="action" value="submit_cid_report">
                        <input type="hidden" name="crime_id" value="<?= $crime['id'] ?>">
                        <div class="form-group">
                            <label class="form-label" for="report_text">
                                <i class="fas fa-edit"></i> Report Details
                            </label>
                            <textarea 
                                id="report_text" 
                                name="report_text" 
                                rows="5" 
                                class="form-textarea" 
                                placeholder="Enter your report details here..."
                                required
                            ></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i>
                            Submit Report
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="section">
                <h2 class="section-title">
                    <i class="fas fa-clipboard-list"></i>
                    Reports for this Crime
                </h2>
                
                <?php if (count($reports) > 0): ?>
                    <div class="reports-list">
                        <?php foreach ($reports as $report): ?>
                            <div class="report-card">
                                <div class="report-header">
                                    <div>
                                        <div class="report-author">
                                            <i class="fas fa-user"></i>
                                            <?= htmlspecialchars($report['officer_name']) ?>
                                        </div>
                                       
                                    </div>
                                    <?php if ($_SESSION['user']['id'] == $report['submitted_by'] || $_SESSION['user']['role'] == 'admin'): ?>
                                        <div class="report-actions">
                                            <form action="../views/edit_report.php" method="GET" style="display:inline;">
                                                <input type="hidden" name="report_id" value="<?= $report['id'] ?>">
                                                <button type="submit" class="btn btn-secondary btn-small">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                            </form>
                                            <form action="../handlers/delete_report.php" method="POST" style="display:inline;">
                                                <input type="hidden" name="report_id" value="<?= $report['id'] ?>">
                                                <button type="submit" class="btn btn-danger btn-small" onclick="return confirm('Are you sure you want to delete this report?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="report-text">
                                    <?= nl2br(htmlspecialchars($report['report_text'])) ?>
                                </div>
                                
                                <div class="report-meta">
                                    <span>
                                        <i class="fas fa-clock"></i>
                                        <?= date('F j, Y \a\t g:i A', strtotime($report['created_at'])) ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-reports">
                        <i class="fas fa-inbox" style="font-size: 3em; margin-bottom: 20px; opacity: 0.5;"></i>
                        <p>No reports have been submitted for this crime yet.</p>
                        <p>Be the first to add a report using the form above.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <a href="crimes.php" class="back-link">
                <i class="fas fa-arrow-left"></i>
                Back to all crimes
            </a>
        </div>
    </div>
    
    <script>
        // Add smooth scrolling animation for form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('.btn-primary');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
            submitBtn.disabled = true;
            
            // Re-enable button after 3 seconds (in case of errors)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 3000);
        });
        
        // Add fade-in animation to cards
        const cards = document.querySelectorAll('.detail-card, .report-card');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });
        
        cards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    </script>
</body>
</html>