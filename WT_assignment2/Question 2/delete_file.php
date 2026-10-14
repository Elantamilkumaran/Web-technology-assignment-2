<?php
require 'config.php';
require 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    deleteFileById($pdo, (int)($_POST['id'] ?? 0));
}
header("Location: view.php?id=" . (int)($_POST['student_id'] ?? 0));
exit;
