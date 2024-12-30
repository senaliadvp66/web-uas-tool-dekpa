<?php
// edit_user.php
session_start();
include('config.php'); // Database connection file

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

$userId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch user details
$query = "SELECT * FROM users WHERE id_user = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "<p>User not found.</p>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    $role = $_POST['role'];

    if (!empty($_POST['password'])) {
        $hashed_password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $updateQuery = "UPDATE users SET username = ?, nama = ?, role = ?, password = ? WHERE id_user = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param('ssssi', $username, $nama, $role, $hashed_password, $userId);
    } else {
        $updateQuery = "UPDATE users SET username = ?, nama = ?, role = ? WHERE id_user = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param('sssi', $username, $nama, $role, $userId);
    }

    if ($stmt->execute()) {
        $success = "User updated successfully.";
        header("Location: user.php");
    } else {
        $error = "Failed to update user. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="style/game.css">
    <style>
        select.form-input {
            color: black;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit User</h2>
        <?php if (isset($success)): ?>
            <p class="success-message"><?= $success ?></p>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <p class="error-message"><?= $error ?></p>
        <?php endif; ?>
        <form action="edit_user.php?id=<?= $userId ?>" method="POST">
            <input class="form-input" type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" placeholder="Username" required>
            <input class="form-input" type="text" name="nama" value="<?= htmlspecialchars($user['nama']) ?>" placeholder="Full Name" required>
            <select class="form-input" name="role" required>
                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
            <input class="form-input" type="password" name="password" placeholder="New Password (Leave blank to keep current password)">
            <button class="form-button" type="submit">Update User</button>
        </form>
    </div>
</body>
</html>
