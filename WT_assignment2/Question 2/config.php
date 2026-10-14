<?php
// Database connection (change user/password if your MySQL is different)
$host = "localhost";
$db   = "college_db";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed. Did you import database.sql? <br>" . htmlspecialchars($e->getMessage()));
}
