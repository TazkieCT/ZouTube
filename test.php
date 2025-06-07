<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Video - ZouTube</title>
    <link rel="stylesheet" href="styles/upload.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap">
</head>
<body>
    <header class="header">
        <div class="header-left">
            <button class="menu-toggle" id="menuToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <a href="#" class="logo">
                <svg width="30" height="20" viewBox="0 0 30 20" xmlns="http://www.w3.org/2000/svg" class="logo-icon">
                    <rect width="30" height="20" fill="#FF0000" rx="5" />
                    <path d="M12 6L20 10L12 14V6Z" fill="white" />
                </svg>
                <span class="logo-text">ZouTube</span>
            </a>
        </div>
        <div class="search-container">
            <button class="search-button">
                <svg viewBox="0 0 24 24" width="24" height="24">
                    <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"></path>
                </svg>
            </button>
            <input type="text" class="search-input" placeholder="Search">
        </div>
        <div class="header-right">
            <div class="profile">
                <div class="profile-avatar">Z</div>
            </div>
        </div>
    </header>

    <div class="main-content">
        <aside class="sidebar" id="sidebar">
            <nav class="sidebar-nav">
                <ul>
                    <li>
                        <a href="/dashboard.php">
                            <svg viewBox="0 0 24 24" width="24" height="24">
                                <path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"></path>
                            </svg>
                            <span>Content</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#">
                            <svg viewBox="0 0 24 24" width="24" height="24">
                                <path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4zM14 13h-3v3H9v-3H6v-2h3V8h2v3h3v2z"></path>
                            </svg>
                            <span>Upload Video</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <div class="upload-layout">
            <main class="upload-form-content">
                <div class="content-header">
                    <h1>Upload Video</h1>
                    <p class="subtitle">Post a video to your channel</p>
                </div>

                <?php
                error_reporting(E_ALL);
                ini_set('display_errors', 1);

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Handle form submission
                    $title = $_POST['videoTitle'] ?? '';
                    $description = $_POST['videoDescription'] ?? '';
                    $visibility = $_POST['visibility'] ?? 'public';
                    
                    // Handle file upload
                    if (isset($_FILES['videoFile']) && $_FILES['videoFile']['error'] === UPLOAD_ERR_OK) {
                        $uploadDir = 'uploads/';
                        if (!file_exists($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }
                        
                        $fileName = basename($_FILES['videoFile']['name']);
                        $filePath = $uploadDir . uniqid() . '_' . $fileName;
                        
                        if (move_uploaded_file($_FILES['videoFile']['tmp_name'], $filePath)) {
                            // File upload success
                            $fileSize = filesize($filePath);
                            $formattedSize = formatFileSize($fileSize);
                            
                            // Get video duration (requires ffmpeg)
                            $duration = '0:00';
                            if (function_exists('shell_exec')) {
                                $output = shell_exec("ffmpeg -i " . escapeshellarg($filePath) . " 2>&1");
                                if (preg_match('/Duration: (\d+):(\d+):(\d+)/', $output, $matches)) {
                                    $hours = $matches[1];
                                    $minutes = $matches[2];
                                    $seconds = $matches[3];
                                    if ($hours > 0) {
                                        $duration = "$hours:$minutes:$seconds";
                                    } else {
                                        $duration = "$minutes:$seconds";
                                    }
                                }
                            }
                            
                            // Generate thumbnails (requires ffmpeg)
                            $thumbnails = [];
                            if (function_exists('shell_exec')) {
                                $thumbnailDir = $uploadDir . 'thumbnails/';
                                if (!file_exists($thumbnailDir)) {
                                    mkdir($thumbnailDir, 0777, true);
                                }
                                
                                $thumbnailPrefix = uniqid();
                                $output = shell_exec("ffmpeg -i " . escapeshellarg($filePath) . " -vf \"select=eq(n\,0)\" -vframes 1 " . escapeshellarg($thumbnailDir . $thumbnailPrefix . "_1.jpg") . " 2>&1");
                                $output = shell_exec("ffmpeg -i " . escapeshellarg($filePath) . " -ss 00:00:05 -vframes 1 " . escapeshellarg($thumbnailDir . $thumbnailPrefix . "_2.jpg") . " 2>&1");
                                $output = shell_exec("ffmpeg -i " . escapeshellarg($filePath) . " -ss 00:00:10 -vframes 1 " . escapeshellarg($thumbnailDir . $thumbnailPrefix . "_3.jpg") . " 2>&1");
                                
                                for ($i = 1; $i <= 3; $i++) {
                                    $thumbFile = $thumbnailDir . $thumbnailPrefix . "_$i.jpg";
                                    if (file_exists($thumbFile)) {
                                        $thumbnails[] = $thumbFile;
                                    }
                                }
                            }
                            
                            // Save video info to database or file
                            // Here we'll just display a success message
                            echo '<div class="upload-success">';
                            echo '<h3>Video uploaded successfully!</h3>';
                            echo '<div class="video-info">';
                            echo '<p><strong>Title:</strong> ' . htmlspecialchars($title) . '</p>';
                            echo '<p><strong>Description:</strong> ' . nl2br(htmlspecialchars($description)) . '</p>';
                            echo '<p><strong>Visibility:</strong> ' . htmlspecialchars($visibility) . '</p>';
                            echo '<p><strong>File Name:</strong> ' . htmlspecialchars($fileName) . '</p>';
                            echo '<p><strong>File Size:</strong> ' . $formattedSize . '</p>';
                            echo '<p><strong>Duration:</strong> ' . $duration . '</p>';
                            
                            if (!empty($thumbnails)) {
                                echo '<div class="uploaded-thumbnails">';
                                echo '<p><strong>Generated Thumbnails:</strong></p>';
                                foreach ($thumbnails as $thumbnail) {
                                    echo '<img src="' . htmlspecialchars($thumbnail) . '" width="200">';
                                }
                                echo '</div>';
                            }
                            
                            echo '</div>';
                            echo '<a href="upload.php" class="upload-another">Upload Another Video</a>';
                            echo '</div>';
                            
                            exit;
                        } else {
                            $error = "Failed to upload file.";
                        }
                    } else {
                        $error = "No file uploaded or upload error.";
                    }
                    
                    if (isset($error)) {
                        echo '<div class="upload-error">' . htmlspecialchars($error) . '</div>';
                    }
                }
                
                function formatFileSize($bytes) {
                    if ($bytes === 0) return '0 Bytes';
                    
                    $k = 1024;
                    $sizes = ['Bytes', 'KB', 'MB', 'GB'];
                    $i = floor(log($bytes) / log($k));
                    
                    return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
                }
                ?>

                <form method="POST" enctype="multipart/form-data" class="upload-content" id="uploadForm">
                    <div class="upload-section">
                        <div class="form-group">
                            <label for="videoTitle">Title (required)</label>
                            <input type="text" id="videoTitle" name="videoTitle" placeholder="Add a title that describes your video" maxlength="100" required>
                            <div class="character-count"><span id="titleCount">0</span>/100</div>
                        </div>

                        <div class="form-group">
                            <label for="videoDescription">Description</label>
                            <textarea id="videoDescription" name="videoDescription" placeholder="Tell viewers about your video" rows="6" maxlength="5000"></textarea>
                            <div class="character-count"><span id="descCount">0</span>/5000</div>
                        </div>

                        <div class="form-group">
                            <label>Thumbnail</label>
                            <p class="thumbnail-help">A thumbnail will be automatically generated from your video.</p>
                            
                            <div class="thumbnails-container" id="thumbnailsContainer">
                                <div class="thumbnail-placeholder">
                                    <svg viewBox="0 0 24 24" width="24" height="24">
                                        <path d="M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"></path>
                                        <path d="M14.14 11.86l-3 3.87L9 13.14 6 17h12l-3.86-5.14z"></path>
                                    </svg>
                                    <p>Upload your video to generate thumbnails</p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Visibility</label>
                            <div class="radio-group">
                                <div class="radio-option">
                                    <input type="radio" id="visibilityPublic" name="visibility" value="public" checked>
                                    <label for="visibilityPublic">
                                        <div class="radio-title">
                                            <svg viewBox="0 0 24 24" width="24" height="24">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"></path>
                                            </svg>
                                            Public
                                        </div>
                                        <div class="radio-description">Everyone can watch your video</div>
                                    </label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" id="visibilityPrivate" name="visibility" value="private">
                                    <label for="visibilityPrivate">
                                        <div class="radio-title">
                                            <svg viewBox="0 0 24 24" width="24" height="24">
                                                <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"></path>
                                            </svg>
                                            Private
                                        </div>
                                        <div class="radio-description">Only you can watch your video</div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="upload-actions">
                        <button type="submit" class="action-button primary" id="publishButton">PUBLISH</button>
                    </div>
                </form>
            </main>

            <aside class="upload-sidebar">
                <div class="upload-sidebar-header">
                    <h2>Upload video</h2>
                </div>
                <div class="upload-area" id="uploadArea">
                    <div class="upload-placeholder" id="uploadPlaceholder">
                        <svg viewBox="0 0 24 24" width="48" height="48">
                            <path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM14 13v4h-4v-4H7l5-5 5 5h-3z"></path>
                        </svg>
                        <p>Drag and drop video files to upload</p>
                        <p class="upload-small">Your videos will be private until you publish them</p>
                        <label for="videoFile" class="upload-button">SELECT FILES</label>
                        <input type="file" id="videoFile" name="videoFile" accept="video/*" required style="display: none;">
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <script>
    // Minimal JavaScript for UI interactions
    document.addEventListener('DOMContentLoaded', function() {
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const videoTitle = document.getElementById('videoTitle');
        const videoDescription = document.getElementById('videoDescription');
        const titleCount = document.getElementById('titleCount');
        const descCount = document.getElementById('descCount');
        
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
        });
        
        videoTitle.addEventListener('input', function() {
            titleCount.textContent = this.value.length;
        });
        
        videoDescription.addEventListener('input', function() {
            descCount.textContent = this.value.length;
        });
        
        // File input change handler
        const fileInput = document.getElementById('videoFile');
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                const file = this.files[0];
                if (!file.type.startsWith('video/')) {
                    alert('Please select a valid video file.');
                    this.value = '';
                }
            }
        });
        
        // Drag and drop functionality
        const uploadArea = document.getElementById('uploadArea');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            uploadArea.classList.add('drag-over');
        }
        
        function unhighlight() {
            uploadArea.classList.remove('drag-over');
        }
        
        uploadArea.addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0 && files[0].type.startsWith('video/')) {
                fileInput.files = files;
                console.log('File ready for upload:', files[0].name);
            } else {
                alert('Please drop a valid video file.');
            }
        });
    });
    </script>
</body>
</html>