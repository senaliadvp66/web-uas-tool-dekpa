<?php
// add_game.php
session_start();
include('config.php'); // Database connection file

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $id_kategori = $_POST['id_kategori'];
    $deskripsi = $_POST['deskripsi'];
    $spesifikasi = $_POST['spesifikasi'];
    $gambar = $_FILES['gambar']['name'];

    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($gambar);

    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $uploadFile)) {
        $query = "INSERT INTO game (judul, id_kategori, deskripsi, spesifikasi, gambar) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sisss', $judul, $id_kategori, $deskripsi, $spesifikasi, $gambar);

        if ($stmt->execute()) {
            $success = "Game added successfully.";
        } else {
            $error = "Failed to add game. Please try again.";
        }
    } else {
        $error = "Failed to upload image.";
    }
}

$kategoriQuery = "SELECT * FROM kategori";
$kategoriResult = $conn->query($kategoriQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Game</title>
    <link rel="stylesheet" href="style/game.css">
</head>
<body>
    <div class="form-container">
        <h2>Add Game</h2>
        <?php if (isset($success)): ?>
            <p class="success-message"><?= $success ?></p>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <p class="error-message"><?= $error ?></p>
        <?php endif; ?>
        <form action="add_game.php" method="POST" enctype="multipart/form-data">
            <input class="form-input" type="text" name="judul" placeholder="Game Title" required>
            <select class="form-input" name="id_kategori" required>
                <option value="">Select Category</option>
                <?php while ($row = $kategoriResult->fetch_assoc()): ?>
                    <option value="<?= $row['id_kategori'] ?>"><?= $row['nama_kategori'] ?></option>
                <?php endwhile; ?>
            </select>
            <textarea class="form-input" name="deskripsi" placeholder="Description" required></textarea>
            <textarea class="form-input" name="spesifikasi" placeholder="Specifications" required></textarea>
            <input class="form-input" type="file" name="gambar" accept="image/*" required>
            <button class="form-button" type="submit">Add Game</button>
        </form>
    </div>
</body>
</html>