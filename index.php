<?php
$allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
$maxFileSize = 2 * 1024 * 1024; // 2MB
$uploadDir = 'uploads/';
$copyDir = 'backups/'; 

if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}
if (!file_exists($copyDir)) {
    mkdir($copyDir, 0777, true);
}

$uploadStatus = '';

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
                // Menyalin file ke folder backup
                $copyPath = $copyDir . basename($file['name']);
                if (copy($destination, $copyPath)) {
                    $uploadStatus = 'File uploaded and copied successfully: ' . htmlspecialchars($file['name']) . 
                                   ' (' . round($file['size'] / 1024, 2) . ' KB)<br>' .
                                   'Original: ' . $destination . '<br>' .
                                   'Copy: ' . $copyPath;
                } else {
                    $uploadStatus = 'File uploaded but failed to create copy: ' . htmlspecialchars($file['name']);
                }
            } else {
                $uploadStatus = 'Failed to move uploaded file.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>File Upload with Validation</title>
</head>
<body>
    <h2>Upload a File</h2>
    
    <?php if (!empty($uploadStatus)): ?>
        <p style="color: <?php echo strpos($uploadStatus, 'successfully') !== false ? 'green' : 'red'; ?>">
            <?php echo $uploadStatus; ?>
        </p>
    <?php endif; ?>
    
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
        <label for="fileToUpload">Select file to upload:</label>
        <input type="file" name="fileToUpload" id="fileToUpload" required>
        <br><br>
        <input type="submit" value="Upload File" name="submit">
    </form>
    
    <h3>Upload Requirements:</h3>
    <ul>
        <li>Maximum file size: <?php echo ($maxFileSize / (1024 * 1024)); ?> MB</li>
        <li>Allowed file types: <?php echo implode(', ', $allowedTypes); ?></li>
    </ul>
</body>
</html>

