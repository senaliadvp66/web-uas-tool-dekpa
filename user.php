<?php
// manage_users.php
session_start();
include('config.php'); // Database connection file

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Delete user
if (isset($_GET['delete'])) {
    $id_user = $_GET['delete'];
    $query = "DELETE FROM users WHERE id_user = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id_user);
    if ($stmt->execute()) {
        $success = "User deleted successfully.";
    } else {
        $error = "Failed to delete user.";
    }
}

// Fetch users
$query = "SELECT id_user, username, nama, role FROM users";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Users</title>
    <link rel="stylesheet" href="style/game.css">
    <style>
        select.form-input {
            color: black;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Manage Users</h2>
        <?php if (isset($success)): ?>
            <p class="success-message"><?= $success ?></p>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <p class="error-message"><?= $error ?></p>
        <?php endif; ?>

        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
            <thead>
                <tr>
                    <th style="border-bottom: 1px solid #fff; padding: 10px;">ID</th>
                    <th style="border-bottom: 1px solid #fff; padding: 10px;">Username</th>
                    <th style="border-bottom: 1px solid #fff; padding: 10px;">Name</th>
                    <th style="border-bottom: 1px solid #fff; padding: 10px;">Role</th>
                    <th style="border-bottom: 1px solid #fff; padding: 10px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #ccc;"><?= $row['id_user'] ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ccc;"><?= $row['username'] ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ccc;"><?= $row['nama'] ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ccc; text-transform: capitalize;"><?= $row['role'] ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ccc;">
                            <a href="edit_user.php?id=<?= $row['id_user'] ?>" style="color: #4caf50;">Edit</a> |
                            <a href="?delete=<?= $row['id_user'] ?>" style="color: #ff5252;" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
