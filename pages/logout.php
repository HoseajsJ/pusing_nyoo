<?php
session_start();

// Hapus semua data session
$_SESSION = [];
session_unset();
session_destroy();

// Redirect ke halaman utama
header("Location: ../index.php");
exit;