<?php include 'header.php'; ?>
<h2>Login</h2>
<form method="POST" action="../controllers/AuthController.php">
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>
    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>
    <button type="submit" name="login">Login</button>
</form>
<?php include 'footer.php'; ?>
