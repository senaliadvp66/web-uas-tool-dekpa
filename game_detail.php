<?php
// game_detail.php
session_start();
include('config.php'); // Database connection file

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['id_user'])) {
    header("location: login.php");
    exit;
}

// Get game ID from query string
$gameId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch game details from database
$query = "SELECT game.*, kategori.nama_kategori FROM game INNER JOIN kategori ON game.id_kategori = kategori.id_kategori WHERE id_game = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $gameId);
$stmt->execute();
$result = $stmt->get_result();
$game = $result->fetch_assoc();

if (!$game) {
    echo "<p>Game not found.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Detail - <?= htmlspecialchars($game['judul']) ?></title>
    <link rel="stylesheet" href="style/style.css">
    <style>
        .detail-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #1e1e1e;
            color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .detail-container img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .detail-container h2 {
            font-size: 32px;
            margin-bottom: 15px;
            color: #e50914;
        }

        .detail-container p {
            margin-bottom: 10px;
            line-height: 1.6;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #e50914;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .back-link:hover {
            background-color: #b00710;
        }
    </style>
</head>
<body>
    <div class="detail-container">
        <img src="uploads/<?= htmlspecialchars($game['gambar']) ?>" alt="<?= htmlspecialchars($game['judul']) ?>">
        <h2><?= htmlspecialchars($game['judul']) ?></h2>
        <p><strong>Category:</strong> <?= htmlspecialchars($game['nama_kategori']) ?></p>
        <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($game['deskripsi'])) ?></p>
        <p><strong>Specifications:</strong><br><?= nl2br(htmlspecialchars($game['spesifikasi'])) ?></p>
        <a href="index.php" class="back-link">Back to Home</a>
    </div>
</body>
</html>
