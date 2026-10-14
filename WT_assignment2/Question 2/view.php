<?php
require 'config.php';
require 'functions.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$s = $stmt->fetch();
if (!$s) die("Student not found. <a href='index.php'>Back</a>");

$files = $pdo->prepare("SELECT * FROM student_files WHERE student_id = ? ORDER BY uploaded_at DESC");
$files->execute([$id]);
$files = $files->fetchAll();
$profile = profileImage($pdo, $id);

include 'header.php';
?>
<h3>Student Details</h3>
<div class="card">
  <div class="profile">
    <?php if ($profile): ?>
      <img class="photo" src="<?= h($profile) ?>" alt="Profile">
    <?php else: ?>
      <div class="photo empty">No photo</div>
    <?php endif; ?>
    <div>
      <p><b>Name:</b> <?= h($s['name']) ?></p>
      <p><b>Roll No:</b> <?= h($s['roll_no']) ?></p>
      <p><b>Email:</b> <?= h($s['email']) ?></p>
      <p><b>Course:</b> <?= h($s['course']) ?></p>
      <p><b>Registered:</b> <?= h($s['created_at']) ?></p>
    </div>
  </div>
</div>

<h3>Uploaded Files</h3>
<table>
  <tr><th>Preview</th><th>Category</th><th>File Name</th><th>Type</th><th>Size</th><th>Uploaded On</th><th>Action</th></tr>
  <?php foreach ($files as $f): ?>
    <tr>
      <td>
        <?php if (strpos($f['file_type'], 'image/') === 0): ?>
          <a href="<?= h($f['file_path']) ?>" target="_blank"><img class="thumb" src="<?= h($f['file_path']) ?>" alt=""></a>
        <?php else: ?>
          <a class="btn view" href="<?= h($f['file_path']) ?>" target="_blank">Open PDF</a>
        <?php endif; ?>
      </td>
      <td><?= h(ucfirst(str_replace('_', ' ', $f['category']))) ?></td>
      <td><?= h($f['original_name']) ?></td>
      <td><?= h($f['file_type']) ?></td>
      <td><?= round($f['file_size'] / 1024, 1) ?> KB</td>
      <td><?= h($f['uploaded_at']) ?></td>
      <td>
        <form class="inline" method="post" action="delete_file.php" onsubmit="return confirm('Delete this file?');">
          <input type="hidden" name="id" value="<?= $f['id'] ?>">
          <input type="hidden" name="student_id" value="<?= $id ?>">
          <button class="btn del" type="submit">Delete</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  <?php if (!$files): ?><tr><td colspan="7" class="center">No files uploaded.</td></tr><?php endif; ?>
</table>
<p><a class="btn edit" href="edit.php?id=<?= $id ?>">Edit / Upload more</a> <a class="btn view" href="index.php">Back</a></p>
<?php include 'footer.php'; ?>
