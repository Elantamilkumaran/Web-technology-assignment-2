<?php
require 'config.php';
require 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);

    // Delete the files from disk first (DB rows are removed by ON DELETE CASCADE)
    $stmt = $pdo->prepare("SELECT id FROM student_files WHERE student_id = ?");
    $stmt->execute([$id]);
    foreach ($stmt->fetchAll() as $f) deleteFileById($pdo, $f['id']);

    $pdo->prepare("DELETE FROM students WHERE id = ?")->execute([$id]);
}
header("Location: index.php?msg=" . urlencode("Student deleted."));
exit;
