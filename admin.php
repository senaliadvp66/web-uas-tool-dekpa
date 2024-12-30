<?php
// admin.php
session_start();
include('config.php'); // Database connection file

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Delete game
if (isset($_GET['delete'])) {
    $id_game = $_GET['delete'];
    $query = "DELETE FROM game WHERE id_game = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id_game);
    if ($stmt->execute()) {
        $success = "Game deleted successfully.";
    } else {
        $error = "Failed to delete game.";
    }
}

// Fetch games
$query = "SELECT game.id_game, game.judul, kategori.nama_kategori FROM game INNER JOIN kategori ON game.id_kategori = kategori.id_kategori";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Games</title>
    <link rel="stylesheet" href="style/game.css">
</head>
<body>
    <div class="form-container">
        <h2>Manage Games</h2>
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
                    <th style="border-bottom: 1px solid #fff; padding: 10px;">Title</th>
                    <th style="border-bottom: 1px solid #fff; padding: 10px;">Category</th>
                    <th style="border-bottom: 1px solid #fff; padding: 10px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #ccc;"><?= $row['id_game'] ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ccc;"><?= $row['judul'] ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ccc;"><?= $row['nama_kategori'] ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ccc;">
                            <a href="edit_game.php?id=<?= $row['id_game'] ?>" style="color: #4caf50;">Edit</a> |
                            <a href="?delete=<?= $row['id_game'] ?>" style="color: #ff5252;" onclick="return confirm('Are you sure you want to delete this game?')">Delete</a> |
                            <a href="game_detail.php?id=<?= $row['id_game'] ?>" style="color: #007bff;">Details</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="add_game.php" class="form-button">Add New Game</a>
    </div>
</body>
</html>
