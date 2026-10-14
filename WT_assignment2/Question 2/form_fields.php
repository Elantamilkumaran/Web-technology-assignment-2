<?php /* Shared form fields for add.php and edit.php. Expects $student array and $isEdit flag */ ?>
<label>Full Name</label>
<input type="text" name="name" value="<?= h($student['name']) ?>" required>

<label>Roll Number</label>
<input type="text" name="roll_no" value="<?= h($student['roll_no']) ?>" required>

<label>Email</label>
<input type="email" name="email" value="<?= h($student['email']) ?>" required>

<label>Course</label>
<select name="course" required>
  <option value="">-- Select Course --</option>
  <?php foreach (['B.E. Computer Science', 'B.E. Electronics', 'B.E. Mechanical', 'B.Sc. Mathematics'] as $c): ?>
    <option <?= $student['course'] === $c ? 'selected' : '' ?>><?= h($c) ?></option>
  <?php endforeach; ?>
</select>

<label>Profile Image (JPG/PNG, max 2 MB) <?= $isEdit ? '- leave empty to keep current' : '' ?></label>
<input type="file" name="profile" accept=".jpg,.jpeg,.png">

<label>Document (ID proof / certificate - JPG/PNG/PDF, max 2 MB)</label>
<select name="doc_category">
  <option value="id_proof">ID Proof</option>
  <option value="certificate">Certificate</option>
  <option value="other">Other</option>
</select>
<input type="file" name="document" accept=".jpg,.jpeg,.png,.pdf">
