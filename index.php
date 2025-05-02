<?php
$allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
$maxFileSize = 200 * 1024 * 1024; // 200MB
$uploadDir = 'uploads/';

$uploadStatus = '';
$isSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fileToUpload'])) {
    $file = $_FILES['fileToUpload'];
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $uploadStatus = 'Error uploading file. Error code: ' . $file['error'];
    } else {
        if ($file['size'] > $maxFileSize) {
            $uploadStatus = 'File is too large. Maximum size allowed is ' . ($maxFileSize / (1024 * 1024)) . 'MB.';
        }
        elseif (!in_array($file['type'], $allowedTypes)) {
            $uploadStatus = 'Invalid file type. Allowed types: ' . implode(', ', $allowedTypes);
        }
        elseif (file_exists($uploadDir . $file['name'])) {
            $uploadStatus = 'File with this name already exists.';
        }
        else {
            $destination = $uploadDir . basename($file['name']);
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $uploadStatus = 'File uploaded successfully: ' . htmlspecialchars($file['name']) . 
                               ' (' . round($file['size'] / 1024, 2) . ' KB)';
                $isSuccess = true;
            } else {
                $uploadStatus = 'Failed to move uploaded file.';
            }
        }
    }
}

if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZouTube</title>
    <style>
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Upload a File</h2>
    
    <?php if (!empty($uploadStatus)): ?>
        <p class="<?php echo $isSuccess ? 'success' : 'error'; ?>">
            <?php echo $uploadStatus; ?>
        </p>
    <?php endif; ?>
    
    <form action="index.php" method="post" enctype="multipart/form-data">
        <label for="fileToUpload">Select file to upload:</label>
        <input type="file" name="fileToUpload" id="fileToUpload" required>
        <br><br>
        <input type="submit" value="Upload File" name="submit">
    </form>
    
    <h3>Upload Requirements:</h3>
    <ul>
        <li>Maximum file size: