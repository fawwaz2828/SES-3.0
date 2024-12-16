<?php
session_start();
session_unset(); // Menghapus semua sesi
session_destroy(); // Menghancurkan sesi

// Redirect ke halaman login atau landing page
header("Location: index.php");
exit();
?>
