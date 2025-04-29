<?php
session_start();
require 'dbconnection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE uid = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit;
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newName = $_POST['name']; // Update to 'name'
    $newEmail = $_POST['email'];

    // Update user details
    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE uid = ?");
    $stmt->execute([$newName, $newEmail, $user_id]);

    $message = "Profile updated successfully!";
    // Refresh user data
    $stmt = $pdo->prepare("SELECT * FROM users WHERE uid = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="style1.css">
    <style>
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        h2 { text-align: center; }
        form { display: flex; flex-direction: column; }
        label { margin-bottom: 5px; font-weight: bold; }
        input { margin-bottom: 15px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
        .submit-button {
            background-color: rgb(61, 95, 150);
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .submit-button:hover { background-color: rgb(61, 100, 176); }
        .message { margin-top: 10px; color: green; }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="/images/borrowsmart.png" alt="BorrowSmart Logo">
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="profile.php">My Profile</a></li>
            <li><a href="borrow_item.php">Borrow Item</a></li>
            <li><a href="return_item.php">Return Item</a></li>
            <li><a href="return_history.php">Return History</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header class="main-header">
            <img src="/images/uthmlogo.png" alt="UTHM Logo">
            <h1>My Profile</h1>
        </header>

        <div class="container">
            <h1>My Profile</h1>

            <?php if (isset($message)): ?>
                <p class="message"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <form method="POST">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                <br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                <br>
                <button type="submit" class="submit-button">Update Profile</button>
            </form>
        </div>
    </main>
</body>
</html>
