<?php
function handleFileUpload() {
    $uploadStatus = '';
    $isSuccess = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fileToUpload'])) {
        $file = $_FILES['fileToUpload'];
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $uploadStatus = 'Error uploading file. Error code: ' . $file['error'];
        } else {
            if ($file['size'] > MAX_FILE_SIZE) {
                $uploadStatus = 'File is too large. Maximum size allowed is ' . (MAX_FILE_SIZE / (1024 * 1024)) . 'MB.';
            }
            elseif (!in_array($file['type'], ALLOWED_TYPES)) {
                $uploadStatus = 'Invalid file type. Allowed types: ' . implode(', ', ALLOWED_TYPES);
            }
            elseif (file_exists(UPLOAD_DIR . $file['name'])) {
                $uploadStatus = 'File with this name already exists.';
            }
            else {
                $destination = UPLOAD_DIR . basename($file['name']);
                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    $copyPath = COPY_DIR . basename($file['name']);
                    if (copy($destination, $copyPath)) {
                        $uploadStatus = 'File uploaded and copied successfully: ' . htmlspecialchars($file['name']) . 
                                       ' (' . round($file['size'] / 1024, 2) . ' KB)';
                        $isSuccess = true;
                    } else {
                        $uploadStatus = 'File uploaded but failed to create copy: ' . htmlspecialchars($file['name']);
                    }
                } else {
                    $uploadStatus = 'Failed to move uploaded file.';
                }
            }
        }
    }

    return ['status' => $uploadStatus, 'success' => $isSuccess];
}
?>