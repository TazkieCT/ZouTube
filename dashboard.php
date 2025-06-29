<?php
$jsonPath = __DIR__ . '/video_data.json';
$videos = [];

if (file_exists($jsonPath)) {
    $json = file_get_contents($jsonPath);
    $videos = json_decode($json, true) ?? [];
}

function formatDuration($seconds) {
    $minutes = floor($seconds / 60);
    $remaining = $seconds % 60;
    return sprintf("%d:%02d", $minutes, $remaining);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Channel - ZouTube Studio</title>
    <link rel="stylesheet" href="/ZouTube/styles/dashboard.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap">
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="header-left">
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
                <input type="text" class="search-input" placeholder="Search content in your channel">
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
                        <li class="active">
                            <a href="/ZouTube/dashboard.php">
                                <svg viewBox="0 0 24 24" width="24" height="24">
                                    <path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"></path>
                                </svg>
                                <span>Content</span>
                            </a>
                        </li>
                        <li>
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

            <main class="content">
                <div class="content-header">
                    <h1>Channel content</h1>
                    <div class="tabs">
                        <button class="tab active">Videos</button>
                    </div>
                </div>

                <div class="video-table">
                    <div class="table-header">
                        <div class="checkbox-cell">
                            <input type="checkbox" id="selectAll">
                        </div>
                        <div class="video-cell">Video</div>
                        <div class="visibility-cell">Visibility</div>
                        <div class="date-cell">Date</div>
                        <div class="views-cell">Views</div>
                        <div class="likes-cell">Likes</div>
                    </div>

                    <?php if (count($videos) > 0): ?>
                        <?php foreach ($videos as $video): ?>
                            <div class="video-row">
                                <div class="checkbox-cell">
                                    <input type="checkbox">
                                </div>
                                <div class="video-cell">
                                    <div class="video-thumbnail">
                                        <video src="<?= htmlspecialchars($video['filePath']) ?>" muted width="120" height="80" style="object-fit: cover;" preload="metadata"></video>
                                        <span class="video-duration"><?= formatDuration($video['duration']) ?></span>
                                    </div>
                                    <div class="video-info">
                                        <h3><?= htmlspecialchars($video['title']) ?></h3>
                                        <p><?= htmlspecialchars($video['description']) ?></p>
                                    </div>
                                </div>
                                <div class="visibility-cell">
                                    <span class="visibility-badge <?= $video['visibility'] === 'public' ? 'public' : 'private' ?>">
                                        <?= ucfirst($video['visibility']) ?>
                                    </span>
                                </div>
                                <div class="date-cell">—</div>
                                <div class="views-cell">—</div>
                                <div class="likes-cell">
                                    <span>—</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="padding: 20px;">No videos uploaded yet.</p>
                    <?php endif; ?>


                </div>
            </main>
        </div>
    </div>

    <script src="/js/script.js"></script>
</body>
</html>