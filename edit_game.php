<?php
// edit_game.php
session_start();
include('config.php'); // Database connection file

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

$gameId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch game details
$query = "SELECT * FROM game WHERE id_game = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $gameId);
$stmt->execute();
$result = $stmt->get_result();
$game = $result->fetch_assoc();

if (!$game) {
    echo "<p>Game not found.</p>";
    exit;
}

// Fetch categories
$kategoriQuery = "SELECT * FROM kategori";
$kategoriResult = $conn->query($kategoriQuery);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $id_kategori = $_POST['id_kategori'];
    $deskripsi = $_POST['deskripsi'];
    $spesifikasi = (isset($_POST['minimum_spec']) ? $_POST['minimum_spec'] : '') . (isset($_POST['recommended_spec']) ? $_POST['recommended_spec'] : '');

    $gambar = $game['gambar']; // Retain the current image if no new image is uploaded
    if (!empty($_FILES['gambar']['name'])) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $uploadFile = $uploadDir . basename($_FILES['gambar']['name']);
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $uploadFile)) {
            $gambar = basename($_FILES['gambar']['name']);
        } else {
            $error = "Failed to upload new image.";
        }
    }

    $updateQuery = "UPDATE game SET judul = ?, id_kategori = ?, deskripsi = ?, spesifikasi = ?, gambar = ? WHERE id_game = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('sisssi', $judul, $id_kategori, $deskripsi, $spesifikasi, $gambar, $gameId);

    if ($stmt->execute()) {
        $success = "Game updated successfully.";
        header("Location: admin.php");
    } else {
        $error = "Failed to update game. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Game</title>
    <link rel="stylesheet" href="style/game.css">
    <style>
        select.form-input {
            color: black;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Game</h2>
        <?php if (isset($success)): ?>
            <p class="success-message"><?= $success ?></p>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <p class="error-message"><?= $error ?></p>
        <?php endif; ?>
        <form action="edit_game.php?id=<?= $gameId ?>" method="POST" enctype="multipart/form-data">
            <input class="form-input" type="text" name="judul" value="<?= htmlspecialchars($game['judul']) ?>" placeholder="Game Title" required>
            <select class="form-input" name="id_kategori" required>
                <option value="">Select Category</option>
                <?php while ($row = $kategoriResult->fetch_assoc()): ?>
                    <option value="<?= $row['id_kategori'] ?>" <?= $row['id_kategori'] == $game['id_kategori'] ? 'selected' : '' ?>><?= $row['nama_kategori'] ?></option>
                <?php endwhile; ?>
            </select>
            <textarea class="form-input" name="deskripsi" placeholder="Description" required><?= htmlspecialchars($game['deskripsi']) ?></textarea>
            <textarea class="form-input" name="minimum_spec" rows="10" style="height: auto;"><?= htmlspecialchars($game['spesifikasi']) ?></textarea>
            <input class="form-input" type="file" name="gambar" accept="image/*">
            <p>Current Image: <img src="uploads/<?= htmlspecialchars($game['gambar']) ?>" alt="<?= htmlspecialchars($game['judul']) ?>" style="max-width: 100px;"></p>
            <button class="form-button" type="submit">Update Game</button>
        </form>
    </div>
</body>
</html>
