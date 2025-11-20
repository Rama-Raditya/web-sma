<?php
session_start();

$host = 'localhost';
$dbname = 'sekolah';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isRedaksi() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'redaksi';
}

function redirect($url) {
    header("Location: $url");
    exit();
}
?>