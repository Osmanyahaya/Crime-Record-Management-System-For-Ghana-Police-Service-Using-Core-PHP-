<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['user'])) {
    header("Location: ../views/dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Ghana Police Crime Record System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Optional custom CSS -->
    <style>
        body {
            background: linear-gradient(to right, #0f2027, #203a43, #2c5364);
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .login-card {
            background: #fff;
            color: #333;
            padding: 30px;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }
        .login-header {
            margin-bottom: 20px;
        }
        .logo {
            width: 60px;
            margin-bottom: 10px;
        }
        .form-label {
            font-weight: 500;
        }
        .btn-primary {
            font-size: 1rem;
            padding: 10px;
        }
        .card-footer {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-card">
        <div class="text-center login-header">
            <img src="../assets/images/logo.png" alt="Logo" class="logo">
            <h4>Ghana Police Login</h4>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form method="post" action="login.php">
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="username" class="form-control" placeholder="Enter email" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <div class="card-footer mt-4 text-center text-muted">
            &copy; <?= date("Y") ?> Ghana Police Service
        </div>
    </div>
</div>

</body>
</html>
