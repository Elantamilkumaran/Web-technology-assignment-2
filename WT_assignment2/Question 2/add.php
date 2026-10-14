<?php
require 'config.php';
require 'functions.php';

$student = ['name' => '', 'roll_no' => '', 'email' => '', 'course' => ''];
$errors  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student = [
        'name'    => trim($_POST['name'] ?? ''),
        'roll_no' => trim($_POST['roll_no'] ?? ''),
        'email'   => trim($_POST['email'] ?? ''),
        'course'  => trim($_POST['course'] ?? ''),
    ];
    $errors = validateStudent($student);

    // Check files BEFORE saving anything
    $profile = checkUpload('profile', 'profile');
    $doc     = checkUpload('document', $_POST['doc_category'] ?? 'other');
    if ($profile && $profile['error']) $errors[] = $profile['error'];
    if ($doc && $doc['error'])         $errors[] = $doc['error'];

    if (!$errors) {
        try {
            $stmt = $pdo->prepare("INSERT INTO students (name, roll_no, email, course) VALUES (?, ?, ?, ?)");
            $stmt->execute(array_values($student));
            $id = $pdo->lastInsertId();

            if ($profile) saveUpload($pdo, $id, 'profile', 'profile', $profile);
            if ($doc)     saveUpload($pdo, $id, 'document', $_POST['doc_category'], $doc);

            header("Location: index.php?msg=" . urlencode("Student added successfully."));
            exit;
        } catch (PDOException $e) {
            $errors[] = ($e->getCode() == 23000) ? "Roll number already exists." : "Database error: " . $e->getMessage();
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
    }
}

$isEdit = false;
include 'header.php';
?>
<h3>Add Student</h3>
<?php foreach ($errors as $e): ?><div class="alert error"><?= h($e) ?></div><?php endforeach; ?>

<form method="post" enctype="multipart/form-data" class="card">
  <?php include 'form_fields.php'; ?>
  <button type="submit" class="submit">Save Student</button>
</form>
<?php include 'footer.php'; ?>
