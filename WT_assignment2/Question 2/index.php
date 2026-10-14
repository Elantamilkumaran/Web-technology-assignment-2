<?php
require 'config.php';
require 'functions.php';
include 'header.php';

$students = $pdo->query("SELECT * FROM students ORDER BY id DESC")->fetchAll();
?>
<?php if (isset($_GET['msg'])): ?>
  <div class="alert success"><?= h($_GET['msg']) ?></div>
<?php endif; ?>

<h3>Student Records</h3>
<table>
  <tr><th>Photo</th><th>Name</th><th>Roll No</th><th>Course</th><th>Actions</th></tr>
  <?php foreach ($students as $s): $img = profileImage($pdo, $s['id']); ?>
    <tr>
      <td><?php if ($img): ?><img class="thumb" src="<?= h($img) ?>" alt=""><?php else: ?><div class="thumb"></div><?php endif; ?></td>
      <td><?= h($s['name']) ?></td>
      <td><?= h($s['roll_no']) ?></td>
      <td><?= h($s['course']) ?></td>
      <td>
        <a class="btn view" href="view.php?id=<?= $s['id'] ?>">View</a>
        <a class="btn edit" href="edit.php?id=<?= $s['id'] ?>">Edit</a>
        <form class="inline" method="post" action="delete.php" onsubmit="return confirm('Delete this student and all files?');">
          <input type="hidden" name="id" value="<?= $s['id'] ?>">
          <button class="btn del" type="submit">Delete</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  <?php if (!$students): ?>
    <tr><td colspan="5" class="center">No students yet. Click "+ Add Student".</td></tr>
  <?php endif; ?>
</table>
<?php include 'footer.php'; ?>
