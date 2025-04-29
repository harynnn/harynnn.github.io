<?php
require 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $newPassword = $_POST['new_password'];

    // Validate password strength
    if (strlen($newPassword) < 8 || 
        !preg_match('/[A-Z]/', $newPassword) || 
        !preg_match('/[a-z]/', $newPassword) || 
        !preg_match('/[0-9]/', $newPassword) || 
        !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $newPassword)) {
        die('Password does not meet the requirements.');
    }

    // Hash the password and update
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
    try {
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$hashedPassword, $email]);
        echo "Password reset successfully.";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
