<?php
define('ALLOWED_TYPES', ['image/jpeg', 'image/png', 'application/pdf']);
define('MAX_FILE_SIZE', 2 * 1024 * 1024);
define('UPLOAD_DIR', 'uploads/');
define('COPY_DIR', 'backups/');

if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
}
if (!file_exists(COPY_DIR)) {
    mkdir(COPY_DIR, 0777, true);
}
?>