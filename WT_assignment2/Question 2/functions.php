<?php
define('MAX_SIZE', 2 * 1024 * 1024);                       // 2 MB
define('IMAGE_TYPES', ['image/jpeg' => 'jpg', 'image/png' => 'png']);
define('DOC_TYPES', ['image/jpeg' => 'jpg', 'image/png' => 'png', 'application/pdf' => 'pdf']);

// Safe output (prevents XSS)
function h($text) {
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

// Validate student text fields. Returns array of error messages.
function validateStudent($d) {
    $errors = [];
    if (!preg_match('/^[A-Za-z ]{3,}$/', $d['name']))        $errors[] = "Name must be letters only (min 3 characters).";
    if (!preg_match('/^[A-Za-z0-9]{5,20}$/', $d['roll_no'])) $errors[] = "Roll number must be 5-20 letters/digits.";
    if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL))     $errors[] = "Enter a valid email address.";
    if ($d['course'] === '')                                 $errors[] = "Course is required.";
    return $errors;
}

// Check an uploaded file (size + real type). Returns null if no file was chosen,
// otherwise ['error' => msg or '', 'ext' => ..., 'mime' => ...]
function checkUpload($field, $category) {
    if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $f = $_FILES[$field];
    $label = ($category === 'profile') ? "Profile image" : "Document";

    if ($f['error'] === UPLOAD_ERR_INI_SIZE || $f['error'] === UPLOAD_ERR_FORM_SIZE || $f['size'] > MAX_SIZE) {
        return ['error' => "$label is too large (max 2 MB)."];
    }
    if ($f['error'] !== UPLOAD_ERR_OK) {
        return ['error' => "$label upload failed (error code {$f['error']})."];
    }

    // Check the real file type (not just the extension)
    $mime    = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
    $allowed = ($category === 'profile') ? IMAGE_TYPES : DOC_TYPES;
    if (!isset($allowed[$mime])) {
        $names = ($category === 'profile') ? "JPG or PNG" : "JPG, PNG or PDF";
        return ['error' => "$label must be $names only."];
    }
    return ['error' => '', 'ext' => $allowed[$mime], 'mime' => $mime];
}

// Save a checked file: unique name (no overwriting) + metadata row in MySQL
function saveUpload($pdo, $studentId, $field, $category, $info) {
    $f = $_FILES[$field];
    $newName = $category . '_' . $studentId . '_' . bin2hex(random_bytes(8)) . '.' . $info['ext'];
    $path = 'uploads/' . $newName;

    if (!move_uploaded_file($f['tmp_name'], __DIR__ . '/' . $path)) {
        throw new Exception("Could not save the uploaded file.");
    }

    // A student has only one profile image: remove the old one
    if ($category === 'profile') {
        $old = $pdo->prepare("SELECT id FROM student_files WHERE student_id = ? AND category = 'profile'");
        $old->execute([$studentId]);
        foreach ($old->fetchAll() as $row) deleteFileById($pdo, $row['id']);
    }

    $stmt = $pdo->prepare("INSERT INTO student_files (student_id, category, original_name, file_path, file_type, file_size)
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$studentId, $category, basename($f['name']), $path, $info['mime'], $f['size']]);
}

// Delete one file (disk + database row)
function deleteFileById($pdo, $fileId) {
    $stmt = $pdo->prepare("SELECT file_path FROM student_files WHERE id = ?");
    $stmt->execute([$fileId]);
    $row = $stmt->fetch();
    if ($row) {
        $full = __DIR__ . '/' . $row['file_path'];
        if (file_exists($full)) unlink($full);
        $pdo->prepare("DELETE FROM student_files WHERE id = ?")->execute([$fileId]);
    }
}

// Profile image path of a student (or null)
function profileImage($pdo, $studentId) {
    $stmt = $pdo->prepare("SELECT file_path FROM student_files WHERE student_id = ? AND category = 'profile' LIMIT 1");
    $stmt->execute([$studentId]);
    return $stmt->fetchColumn() ?: null;
}
