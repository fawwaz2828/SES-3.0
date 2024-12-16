<?php
session_start(); // Memulai sesi

// Debugging: Menampilkan session ID untuk memastikan sesi aktif
echo "Session ID: " . session_id() . "<br>";

// Periksa apakah sesi ada
if (!isset($_SESSION['user_id'])) {
    // Jika sesi tidak ada, arahkan ke halaman login
    echo "Tidak ada sesi pengguna. Mengalihkan ke halaman login...";
    header("Location: login1.php");
    exit();
}

// Ambil data pengguna dari sesi
$userName = $_SESSION['user_name'];
$userEmail = $_SESSION['user_email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <nav>
        <div class="logo">git config --global user.name
            <img src="imageLogin/Frame 2.svg" alt="Logo">
        </div>
        <ul>
            <li><a href="explore.php">explore</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($userName); ?>!</h1>
        <p>Email: <?php echo htmlspecialchars($userEmail); ?></p>
    </div>
</body>
</html>
