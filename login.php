<?php
session_start();
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login - BorrowSmart</title>
</head>
<body>
<header class="navbar">
    <img src="/images/borrowsmart.png" alt="BorrowSmart Logo">
    <img src="/images/uthmlogo.png" alt="UTHM Logo">
</header>

<div class="container">
    <h2>Login</h2>

    <?php if (!empty($error)): ?>
        <div class="error-message" style="color: red; margin-bottom: 10px;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="login_action.php" method="POST">
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>

        <!-- New: Choose Role -->
        <label for="role">Select Your Role:</label>
        <select name="role" id="role" required>
            <option value="">-- Please select --</option>
            <option value="admin">Admin</option>
            <option value="staff">Staff</option>
            <option value="student">Student</option>
        </select>

        <button type="submit">Login</button>
    </form>

    <p>Forgot your password? <a href="forgotpassword.php">Reset here</a></p>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</div>
</body>
</html>
