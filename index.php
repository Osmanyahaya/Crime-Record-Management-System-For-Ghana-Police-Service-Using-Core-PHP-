<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome | Ghana Police Crime Records System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap 4 CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(to right, #0f2027, #203a43, #2c5364);
            color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .welcome-container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 30px;
        }
        .welcome-box {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }
        .welcome-box h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        .welcome-box p {
            font-size: 1.1rem;
            margin-bottom: 30px;
        }
        .btn-login {
            font-size: 1.1rem;
            padding: 10px 30px;
        }
        .logo {
            width: 100px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="welcome-container">
    <div class="welcome-box">
        <img src="assets/images/logo.png" alt="Ghana Police Logo" class="logo">
        <h1>Welcome to the Ghana Police Crime Records System</h1>
        <p>Empowering officers and administrators with a secure platform for crime reporting and investigation tracking.</p>
        <a href="public/login.php" class="btn btn-primary btn-login">Login to Continue</a>
    </div>
</div>

</body>
</html>
