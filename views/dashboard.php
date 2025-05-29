<?php
#212529
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../public/login.php');
    exit();
}
include 'header.php';
$user = $_SESSION['user'];
$role = $user['role']; // officer, cid, or admin

// Role-based styling and permissions
$roleConfig = [
    'officer' => ['color' => 'blue', 'badge' => 'Officer', 'icon' => 'shield'],
    'cid' => ['color' => 'purple', 'badge' => 'CID Agent', 'icon' => 'search'],
    'admin' => ['color' => 'red', 'badge' => 'Administrator', 'icon' => 'crown']
];
$currentRole = $roleConfig[$role] ?? $roleConfig['officer'];
?>

<style>
    :root {
        --primary-blue: #1e40af;
        --primary-purple: #7c3aed;
        --primary-red: #212529;
        --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --card-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        --hover-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    body {
        
        min-height: 100vh;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    .dashboard-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .welcome-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        box-shadow: var(--card-shadow);
        border: 1px solid rgba(255, 255, 255, 0.2);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .welcome-header {
        background: linear-gradient(135deg, var(--primary-<?= $currentRole['color'] ?>) 0%, color-mix(in srgb, var(--primary-<?= $currentRole['color'] ?>) 80%, white) 100%);
        padding: 2rem;
        color: white;
        position: relative;
    }

    .welcome-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        opacity: 0.3;
    }

    .user-info {
        position: relative;
        z-index: 1;
    }

    .user-name {
        font-size: 2rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .action-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .action-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-<?= $currentRole['color'] ?>);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .action-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--hover-shadow);
        text-decoration: none;
        color: inherit;
    }

    .action-card:hover::before {
        transform: scaleX(1);
    }

    .card-icon {
        width: 48px;
        height: 48px;
        background: var(--primary-<?= $currentRole['color'] ?>);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        color: white;
        font-size: 1.5rem;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        color: #1f2937;
    }

    .card-description {
        color: #6b7280;
        margin: 0;
        line-height: 1.5;
    }

    .restricted-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: linear-gradient(45deg, #fbbf24, #f59e0b);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin: 2rem 0;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.9);
        padding: 1.5rem;
        border-radius: 12px;
        text-align: center;
        backdrop-filter: blur(10px);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        color: var(--primary-<?= $currentRole['color'] ?>);
        display: block;
    }

    .stat-label {
        color: #6b7280;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 1rem;
        }
        
        .welcome-header {
            padding: 1.5rem;
        }
        
        .user-name {
            font-size: 1.5rem;
        }
        
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Icons using CSS shapes */
    .icon-shield::before { content: "🛡️"; }
    .icon-search::before { content: "🔍"; }
    .icon-crown::before { content: "👑"; }
    .icon-report::before { content: "📝"; }
    .icon-view::before { content: "👁️"; }
    .icon-investigate::before { content: "🔬"; }
    .icon-document::before { content: "📋"; }
    .icon-users::before { content: "👥"; }
</style>

<div class="dashboard-container">
    <!-- Welcome Section -->
    <div class="welcome-card">
        <div class="welcome-header">
            <div class="user-info">
                <h1 class="user-name">Welcome back, <?= htmlspecialchars($user['name']) ?></h1>
                <div class="role-badge">
                    <span class="icon-<?= $currentRole['icon'] ?>"></span>
                    <?= $currentRole['badge'] ?>
                </div>
            </div>
        </div>
        
        
    </div>

    <!-- Action Grid -->
    <div class="dashboard-grid">
        <!-- Universal Actions -->
        <a href="report_crime.php" class="action-card">
            <div class="card-icon icon-report"></div>
            <h3 class="card-title">Report New Crime</h3>
            <p class="card-description">File a new crime report with detailed incident information and evidence.</p>
        </a>

        <a href="crimes.php" class="action-card">
            <div class="card-icon icon-view"></div>
            <h3 class="card-title">View Reported Crimes</h3>
            <p class="card-description">Browse and search through all reported crimes and their current status.</p>
        </a>

        <!-- CID & Admin Actions -->
        <?php if (in_array($role, ['cid', 'admin'])): ?>
            

            <a href="cid_report_form.php" class="action-card">
                <div class="card-icon icon-document"></div>
                <h3 class="card-title">Submit CID Report</h3>
                <p class="card-description">Generate comprehensive investigative reports for case closure.</p>
                <div class="restricted-badge">CID+</div>
            </a>
            <a href="cid_reports.php" class="action-card">
            <div class="card-icon icon-view"></div>
            <h3 class="card-title">View CID Reports</h3>
            <p class="card-description">Browse and search through all CID Reports.</p>
            </a>
        <?php endif; ?>

        <!-- Admin Only Actions -->
        <?php if ($role === 'admin'): ?>
            <a href="manage_users.php" class="action-card">
                <div class="card-icon icon-users"></div>
                <h3 class="card-title">Manage Users</h3>
                <p class="card-description">Add, edit, and manage user accounts and access permissions.</p>
                <div class="restricted-badge">Admin</div>
            </a>
        <?php endif; ?>
    </div>
</div>

<script>
// Add subtle animations and interactions
document.addEventListener('DOMContentLoaded', function() {
    // Animate cards on scroll
    const cards = document.querySelectorAll('.action-card');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    });

    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });

    // Add click animation
    cards.forEach(card => {
        card.addEventListener('click', function(e) {
            const ripple = document.createElement('div');
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.background = 'rgba(255, 255, 255, 0.6)';
            ripple.style.transform = 'scale(0)';
            ripple.style.animation = 'ripple 0.6s linear';
            ripple.style.left = (e.clientX - card.offsetLeft) + 'px';
            ripple.style.top = (e.clientY - card.offsetTop) + 'px';
            ripple.style.width = ripple.style.height = '20px';
            ripple.style.marginLeft = ripple.style.marginTop = '-10px';
            
            card.appendChild(ripple);
            setTimeout(() => ripple.remove(), 600);
        });
    });
});

// CSS animation for ripple effect
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>

<?php include 'footer.php'; ?>