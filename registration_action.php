<?php
require 'dbconnection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $role = 'user'; // Default role for new users

    // Password strength regex
    $password_regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#?]).{8,}$/';

    // Validate password strength
    if (!preg_match($password_regex, $password)) {
        $_SESSION['error'] = "Password does not meet strength requirements.<br>
                              Must include at least:
                              <ul>
                                  <li>8 characters</li>
                                  <li>1 uppercase letter</li>
                                  <li>1 lowercase letter</li>
                                  <li>1 number</li>
                                  <li>1 special character (! @ # ?)</li>
                              </ul>";
        header("Location: register.php"); // Redirect back to registration page
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert user into database
    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, role, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $role, $hashedPassword]);
        $_SESSION['success'] = "Registration successful. You can now log in.";
        header("Location: login.php"); // Redirect to login page
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Unique constraint violation (e.g., duplicate email)
            $_SESSION['error'] = "Error: Email already registered.";
        } else {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
        header("Location: register.php"); // Redirect back to registration page
        exit;
    }
}
?>
