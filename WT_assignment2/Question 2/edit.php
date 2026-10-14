<?php
require 'config.php';
require 'functions.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch();
if (!$student) die("Student not found. <a href='index.php'>Back</a>");

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student = [
        'id'      => $id,
        'name'    => trim($_POST['name'] ?? ''),
        'roll_no' => trim($_POST['roll_no'] ?? ''),
        'email'   => trim($_POST['email'] ?? ''),
        'course'  => trim($_POST['course'] ?? ''),
    ];
    $errors = validateStudent($student);

    $profile = checkUpload('profile', 'profile');
    $doc     = checkUpload('document', $_POST['doc_category'] ?? 'other');
    if ($profile && $profile['error']) $errors[] = $profile['error'];
    if ($doc && $doc['error'])         $errors[] = $doc['error'];

    if (!$errors) {
        try {
            $stmt = $pdo->prepare("UPDATE students SET name=?, roll_no=?, email=?, course=? WHERE id=?");
            $stmt->execute([$student['name'], $student['roll_no'], $student['email'], $student['course'], $id]);

            if ($profile) saveUpload($pdo, $id, 'profile', 'profile', $profile);
            if ($doc)     saveUpload($pdo, $id, 'document', $_POST['doc_category'], $doc);

            header("Location: index.php?msg=" . urlencode("Student updated successfully."));
            exit;
        } catch (PDOException $e) {
            $errors[] = ($e->getCode() == 23000) ? "Roll number already exists." : "Database error: " . $e->getMessage();
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
    }
}

$isEdit = true;
include 'header.php';
?>
<h3>Edit Student</h3>
<?php foreach ($errors as $e): ?><div class="alert error"><?= h($e) ?></div><?php endforeach; ?>

<form method="post" enctype="multipart/form-data" class="card">
  <?php include 'form_fields.php'; ?>
  <button type="submit" class="submit">Update Student</button>
</form>
<?php include 'footer.php'; ?>
