<?php
session_start();
require 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $selectedRole = $_POST['role']; // Get selected role from form

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            if ($user['role'] !== $selectedRole) {
                $_SESSION['error'] = "Incorrect role selected. Please select the correct role.";
                header("Location: login.php");
                exit();
            }

            $_SESSION['user_id'] = $user['uid'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['name'];

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: admin_dashboard.php");
                exit();
            } elseif ($user['role'] === 'staff') {
                header("Location: staff_dashboard.php");
                exit();
            } elseif ($user['role'] === 'student' || $user['role'] === 'user') {
                header("Location: dashboard.php");
                exit();
            } else {
                $_SESSION['error'] = "Unknown user role. Please contact administrator.";
                header("Location: login.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Invalid email or password.";
            header("Location: login.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: login.php");
        exit();
    }
}
?>
