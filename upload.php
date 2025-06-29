<?php
$errors = [];
$videoData = null;

function generateId() {
    return random_int(1000, 9999) . time();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['videoTitle'] ?? '');
    if ($title === '') {
        $errors[] = 'Title can\' be empty.';
    }

    $thumbnailPath = '';

    //Ini buat validasi thumbnailnya
    if (!empty($_FILES['thumbnailFile']['tmp_name'])) {
        $thumbFile = $_FILES['thumbnailFile'];

        if ($thumbFile['error'] === UPLOAD_ERR_OK && strpos($thumbFile['type'], 'image/') === 0) {
            $thumbDir = __DIR__ . '/uploads/thumbnails/';
            if (!is_dir($thumbDir)) mkdir($thumbDir, 0755, true);

            $thumbName = time() . '_' . basename($thumbFile['name']);
            $thumbTargetPath = $thumbDir . $thumbName;

            if (move_uploaded_file($thumbFile['tmp_name'], $thumbTargetPath)) {
                $thumbnailPath = 'uploads/thumbnails/' . $thumbName;
            } else {
                $errors[] = 'Failed to save Thumbnail.';
            }
        } else {
            $errors[] = 'Thumbnail must be image (png/jpg/jpeg).';
        }
    }

    //Ini buat validasi videonya
    if (!empty($_FILES['videoFile']['tmp_name'])) {
        $file = $_FILES['videoFile'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Kesalahan saat mengupload file.';
        } elseif (strpos($file['type'], 'video/') !== 0) {
            $errors[] = 'File must be video.';
        } elseif ($file['size'] > 100 * 1024 * 1024) {
            $errors[] = 'Video file must be 100MB or less.';
        } else {
            $dir = __DIR__ . '/uploads/videos/';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $name = time() . '_' . basename($file['name']);
            $path = $dir . $name;
            if (move_uploaded_file($file['tmp_name'], $path)) {
                $videoData = [
                    'id' => generateId(),
                    'creator' => "Tazkie", // Ini ganti sama cookie nanti
                    'title' => htmlspecialchars($title),
                    'description' => htmlspecialchars($_POST['videoDescription'] ?? ''),
                    'visibility' => $_POST['visibility'] === 'public' ? 'public' : 'private',
                    'fileName' => htmlspecialchars($file['name']),
                    'filePath' => 'uploads/videos/' . $name,
                    'thumbnailPath' => $thumbnailPath,
                    'fileSize' => round($file['size'] / 1024 / 1024, 2) . ' MB',
                    'duration' => 0
                ];


                $jsonFile = __DIR__ . '/video_data.json';

                $existingData = [];
                if (file_exists($jsonFile)) {
                    $jsonContent = file_get_contents($jsonFile);
                    $existingData = json_decode($jsonContent, true) ?? [];
                }

                $existingData[] = $videoData;

                file_put_contents($jsonFile, json_encode($existingData, JSON_PRETTY_PRINT));

            } else {
                $errors[] = 'Gagal menyimpan file.';
            }
        }
    } else {
        $errors[] = 'Pilih file video.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Video - ZouTube</title>
    <link rel="stylesheet" href="/ZouTube/styles/upload.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap">
</head>
<body>
    <header class="header">
        <div class="header-left">
            <!-- INI LOGO BUAT BUTTON MENU -->
            <button class="menu-toggle" id="menuToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <a href="/ZouTube/dashboard.php" class="logo">
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
                        <a href="/ZouTube/dashboard.php">
                            <svg viewBox="0 0 24 24" width="24" height="24">
                                <path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"></path>
                            </svg>
                            <span>Content</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/ZouTube/upload.php">
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
            <?php if ($videoData): ?>
                <main class="upload-form-content">
                    <div class="content-header">
                        <h1>Upload Successful!</h1>
                        <p class="subtitle">Your video has been uploaded successfully</p>
                    </div>
                    <div class="upload-success">
                        <h2>Video Details</h2>
                        <p><strong>Title:</strong> <?= $videoData['title'] ?></p>
                        <p><strong>Description:</strong> <?= $videoData['description'] ?></p>
                        <p><strong>Visibility:</strong> <?= ucfirst($videoData['visibility']) ?></p>
                        <p><strong>File:</strong> <?= $videoData['fileName'] ?> (<?= $videoData['fileSize'] ?>)</p>
                        <video src="<?= $videoData['filePath'] ?>" controls width="100%" style="max-width: 480px; margin-top: 20px;"></video>
                        <br><br>
                        <a href="upload.php" class="action-button primary">Upload Another Video</a>
                    </div>
                </main>
            <?php else: ?>
                <main class="upload-form-content">
                    <div class="content-header">
                        <h1>Upload Video</h1>
                        <p class="subtitle">Post a video to your channel</p>
                    </div>

                    <?php if ($errors): ?>
                        <div class="errors" style="background: #ffebee; border: 1px solid #f44336; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
                            <ul style="margin: 0; padding-left: 20px; color: #d32f2f;">
                                <?php foreach ($errors as $e): ?>
                                    <li><?= $e ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="upload-content">
                            <div class="upload-section">
                                <div class="form-group">
                                    <label for="videoTitle">Title (required)</label>
                                    <input type="text" id="videoTitle" name="videoTitle" placeholder="Add a title that describes your video" maxlength="100" value="<?= htmlspecialchars($_POST['videoTitle'] ?? '') ?>">
                                    <div class="character-count"><span id="titleCount"><?= strlen($_POST['videoTitle'] ?? '') ?></span>/100</div>
                                </div>

                                <div class="form-group">
                                    <label for="videoDescription">Description</label>
                                    <textarea id="videoDescription" name="videoDescription" placeholder="Tell viewers about your video" rows="6" maxlength="5000"><?= htmlspecialchars($_POST['videoDescription'] ?? '') ?></textarea>
                                    <div class="character-count"><span id="descCount"><?= strlen($_POST['videoDescription'] ?? '') ?></span>/5000</div>
                                </div>

                                <div class="form-group">
                                    <label>Thumbnail</label>
                                    <p class="thumbnail-help">Select or upload a picture that shows what's in your video. A good thumbnail stands out and draws viewers' attention.</p>
                                    
                                    <input type="file" name="thumbnailFile" id="thumbnailFile" accept="image/*">
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
                                            <input type="radio" id="visibilityPublic" name="visibility" value="public" <?= ($_POST['visibility'] ?? 'public') === 'public' ? 'checked' : '' ?>>
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
                                            <input type="radio" id="visibilityPrivate" name="visibility" value="private" <?= ($_POST['visibility'] ?? '') === 'private' ? 'checked' : '' ?>>
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
                                <button class="action-button primary" id="publishButton" type="submit" disabled>PUBLISH</button>
                            </div>
                        </div>
                        <input type="file" id="fileInput" name="videoFile" style="display: none;">
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
                            <label for="fileInput" class="upload-button">SELECT FILES</label>
                        </div>
                        <div class="video-preview-container" id="videoPreviewContainer" style="display: none;">
                            <video id="videoPreview" controls></video>
                            <div class="video-info">
                                <div class="video-info-item">
                                    <span class="info-label">File name:</span>
                                    <span id="fileName" class="info-value"></span>
                                </div>
                                <div class="video-info-item">
                                    <span class="info-label">File size:</span>
                                    <span id="fileSize" class="info-value"></span>
                                </div>
                                <div class="video-info-item">
                                    <span class="info-label">Duration:</span>
                                    <span id="videoDuration" class="info-value"></span>
                                </div>
                            </div>
                            <button class="change-video-button" id="changeVideoButton" type="button">CHANGE VIDEO</button>
                        </div>
                    </div>
                </aside>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.getElementById('videoTitle').addEventListener('input', function() {
            document.getElementById('titleCount').textContent = this.value.length;
        });
        
        document.getElementById('videoDescription').addEventListener('input', function() {
            document.getElementById('descCount').textContent = this.value.length;
        });

        document.getElementById('fileInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                showVideoPreview(file);
                document.getElementById('publishButton').disabled = false;
            }
        });

        document.getElementById('changeVideoButton').addEventListener('click', function() {
            document.getElementById('fileInput').value = '';
            document.getElementById('videoPreviewContainer').style.display = 'none';
            document.getElementById('uploadPlaceholder').style.display = 'block';
            document.getElementById('publishButton').disabled = true;
        });

        function showVideoPreview(file) {
            const videoPreview = document.getElementById('videoPreview');
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            const videoDuration = document.getElementById('videoDuration');
            
            const videoURL = URL.createObjectURL(file);
            videoPreview.src = videoURL;
            
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            
            videoPreview.addEventListener('loadedmetadata', function() {
                videoDuration.textContent = formatDuration(videoPreview.duration);
            });
            
            document.getElementById('uploadPlaceholder').style.display = 'none';
            document.getElementById('videoPreviewContainer').style.display = 'block';
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function formatDuration(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = Math.floor(seconds % 60);
            
            if (hours > 0) {
                return `${hours}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
            } else {
                return `${minutes}:${secs.toString().padStart(2, '0')}`;
            }
        }

        const uploadArea = document.getElementById('uploadArea');
        const uploadPlaceholder = document.getElementById('uploadPlaceholder');

        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.classList.add('drag-over');
        });

        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('drag-over');
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('drag-over');
            
            const files = e.dataTransfer.files;
            if (files.length > 0 && files[0].type.startsWith('video/')) {
                document.getElementById('fileInput').files = files;
                showVideoPreview(files[0]);
                document.getElementById('publishButton').disabled = false;
            }
        });

        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>