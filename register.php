<?php

include "config.php";
// register.php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match. Please try again.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $role = 'user';

        // Check if username already exists
        $checkQuery = "SELECT * FROM users WHERE username = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param('s', $username);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            $error = "Username already exists. Please choose another.";
        } else {
            $query = "INSERT INTO users (username, nama, password, role) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ssss', $username, $nama, $hashed_password, $role);

            if ($stmt->execute()) {
                header('Location: login.php');
                exit;
            } else {
                $error = "Failed to register. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style/user.css">
</head>
<body>
    <div class="form-container">
        <h2>Register</h2>
        <?php if (isset($error)): ?>
            <p class="error-message"><?= $error ?></p>
        <?php endif; ?>
        <form action="register.php" method="POST">
            <input class="form-input" type="text" name="username" placeholder="Username" required>
            <input class="form-input" type="text" name="nama" placeholder="Full Name" required>
            <input class="form-input" type="password" name="password" placeholder="Password" required>
            <input class="form-input" type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button class="form-button" type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>
