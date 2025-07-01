<?php
session_start();
$jsonPath = __DIR__ . '/video_data.json';
$videos = [];

if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    session_destroy();
    header('Location: /ZouTube/login.php');
    exit;
}

if (!isset($_SESSION['username'])) {
    header('Location: /ZouTube/login.php');
    exit;
}


if (file_exists($jsonPath)) {
    $json = file_get_contents($jsonPath);
    $videos = json_decode($json, true) ?? [];
    $videos = array_filter($videos, function ($video) {
        return isset($video['creator']) && $video['creator'] === $_SESSION['username'];
    });
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Dashboard Channel - ZouTube Studio</title>
    <link rel="stylesheet" href="/ZouTube/styles/dashboard.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap"/>
    <style>
        /* GK TAU KENAPA KALAU PAKAI EKSTERNAL GK KE BERUBAH STYLENYA */
        .visibility-toggle {
            padding: 6px 12px;
            font-size: 13px;
            background-color: #f1f3f4;
            color: #202124;
            border: 1px solid #dadce0;
            border-radius: 20px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .visibility-toggle:hover {
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>
<div class="container">
    <header class="header">
        <div class="header-left">
            <button class="menu-toggle" id="menuToggle">
                <span></span><span></span><span></span>
            </button>
            <a href="/ZouTube/dashboard.php" class="logo">
                <svg width="30" height="20" viewBox="0 0 30 20" xmlns="http://www.w3.org/2000/svg" class="logo-icon">
                    <rect width="30" height="20" fill="#FF0000" rx="5"/>
                    <path d="M12 6L20 10L12 14V6Z" fill="white"/>
                </svg>
                <span class="logo-text">ZouTube</span>
            </a>
        </div>
        <div class="header-right">
            <a href="?logout=true" class="logout-button">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16,17 21,12 16,7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Logout
            </a>
        </div>    
    </header>

    <div class="main-content">
        <aside class="sidebar" id="sidebar">
            <nav class="sidebar-nav">
                <ul>
                    <li class="active">
                        <a href="/ZouTube/dashboard.php">
                            <svg width="24" height="24" viewBox="0 0 24 24">
                                <path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"></path>
                            </svg>
                            <span>Content</span>
                        </a>
                    </li>
                    <li>
                        <a href="/ZouTube/upload.php">
                            <svg width="24" height="24" viewBox="0 0 24 24">
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
            </div>

            <div class="video-table">
                <div class="table-header">
                    <div class="video-cell">Video</div>
                    <div class="visibility-cell">Visibility</div>
                    <div class="visibility-cell">Action</div>
                </div>

                <?php if (count($videos) > 0): ?>
                    <?php foreach ($videos as $video): ?>
                        <div class="video-row">
                            <div class="video-cell">
                                <div class="video-thumbnail">
                                    <img src="/ZouTube/<?= htmlspecialchars($video['thumbnailPath']) ?>" alt="Thumbnail" class="thumbnail-image">
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
                            <div class="action-cell">
                                <form method="POST" action="/ZouTube/update_visibility.php" class="toggle-form">
                                    <input type="hidden" name="video_id" value="<?= $video['id'] ?>">
                                    <button type="submit" class="visibility-toggle">
                                        <?= $video['visibility'] === 'private' ? 'Make Public' : 'Make Private' ?>
                                    </button>
                                </form>
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
</body>
</html>
