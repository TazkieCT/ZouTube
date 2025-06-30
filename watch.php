<?php
$videoData = json_decode(file_get_contents('video_data.json'), true);

$videoId = $_GET['id'] ?? $videoData[0]['id'];

$video = null;
foreach ($videoData as $item) {
    if ($item['id'] == $videoId) {
        $video = $item;
        break;
    }
}

if (!$video) {
    die("Video not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php foreach ($videoData as $item): ?>
                    <?php if ($item['visibility'] !== 'public') continue; ?>
                    <?php if ($item['id'] === $video['id']): ?>
                        <?php echo htmlspecialchars($item['title']); ?> - ZouTube
                    <?php endif; ?>
        <?php endforeach; ?>
    </title>
    <link rel="stylesheet" href="/ZouTube/styles/player.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap">
</head>
<body>
    <header class="header">
        <div class="header-left">
            <a href="/ZouTube/index.php" class="logo">
                <svg width="30" height="20" viewBox="0 0 30 20" xmlns="http://www.w3.org/2000/svg" class="logo-icon">
                    <rect width="30" height="20" fill="#FF0000" rx="5" />
                    <path d="M12 6L20 10L12 14V6Z" fill="white" />
                </svg>
                <span class="logo-text">ZouTube</span>
            </a>
        </div>
        <form class="search-container" action="/ZouTube/search.php" method="GET">
            <input type="text" class="search-input" name="query" placeholder="Search" required>
            <button type="submit" class="search-button">
                <svg viewBox="0 0 24 24" width="24" height="24">
                    <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 
                            16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 
                            5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99 
                            L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 
                            5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 
                            11.99 14 9.5 14z">
                    </path>
                </svg>
            </button>
        </form>
        <div class="header-right">
            <a href="/ZouTube/login.php" class="login-button">Login</a>
        </div>

    </header>

    <div class="main-content">
        <div class="video-container">
            <div class="video-player">
                <video id="mainVideo" poster="<?php echo htmlspecialchars($video['thumbnailPath'] ?? ''); ?>" preload="metadata" autoplay playsinline>
                    <source src="<?php echo htmlspecialchars($video['filePath']); ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <div class="video-controls" id="videoControls">
                    <div class="progress-container">
                        <div class="progress-bar">
                            <div class="progress-fill" id="progressFill"></div>
                        </div>
                    </div>
                    
                    <div class="controls-bottom">
                        <div class="controls-left">
                            <button class="control-button" id="playPauseBtn">
                                <svg class="play-icon" viewBox="0 0 24 24" width="24" height="24">
                                    <path d="M8 5v14l11-7z"></path>
                                </svg>
                                <svg class="pause-icon" viewBox="0 0 24 24" width="24" height="24" style="display: none;">
                                    <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"></path>
                                </svg>
                            </button>
                            <button class="control-button" id="nextBtn">
                                <svg viewBox="0 0 24 24" width="24" height="24">
                                    <path d="M6 18l8.5-6L6 6v12zM16 6v12h2V6h-2z"></path>
                                </svg>
                            </button>
                            <div class="time-display">
                                <span id="currentTime">0:00</span>
                                <span> / </span>
                                <span id="duration">0:00</span>
                            </div>
                        </div>
                        
                        <div class="controls-right">
                            <button class="control-button" id="volumeBtn">
                                <svg viewBox="0 0 24 24" width="24" height="24">
                                    <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"></path>
                                </svg>
                            </button>
                            <div class="volume-slider-container">
                                <input type="range" min="0" max="100" value="100" class="volume-slider" id="volumeSlider">
                            </div>
                            <button class="control-button" id="fullscreenBtn">
                                <svg class="fullscreen-icon" viewBox="0 0 24 24" width="24" height="24">
                                    <path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"></path>
                                </svg>
                                <svg class="exit-fullscreen-icon" viewBox="0 0 24 24" width="24" height="24" style="display: none;">
                                    <path d="M5 16h3v3h2v-5H5v2zm3-8H5v2h5V5H8v3zm6 11h2v-3h3v-2h-5v5zm2-11V5h-2v5h5V8h-3z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="video-info">
                <h1 class="video-title"><?php echo htmlspecialchars($video['title']); ?></h1>
                <div class="video-description">
                    <p>
                        <?php
                        $escapedDescription = htmlspecialchars($video['description']);
                        $withClickableHashtags = preg_replace_callback('/#(\w+)/', function($matches) {
                            $tag = htmlspecialchars($matches[1]);
                            return '<a href="/ZouTube/search.php?query=%23' . urlencode($tag) . '" class="hashtag">#' . $tag . '</a>';
                        }, nl2br($escapedDescription));
                        echo $withClickableHashtags;
                        ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="sidebar">
            <h3 class="header-recommendation">Recommended Videos</h3>
            <div class="recommended-videos">
                <?php foreach ($videoData as $item): ?>
                    <?php if ($item['visibility'] !== 'public') continue; ?>
                    <?php if ($item['id'] !== $video['id']): ?>
                        <div class="video-card">
                            <a href="?id=<?php echo $item['id']; ?>" class="card-link">
                                <div class="thumbnail">
                                    <?php if (!empty($item['thumbnailPath']) && file_exists($item['thumbnailPath'])): ?>
                                        <img src="<?= htmlspecialchars($item['thumbnailPath']) ?>" alt="Video Thumbnail">
                                    <?php else: ?>
                                        <video class="thumbnail-fallback" preload="metadata">
                                            <source src="<?= htmlspecialchars($item['filePath']) ?>" type="video/mp4">
                                        </video>
                                    <?php endif; ?>

                                    <span class="duration"><?php echo gmdate("i:s", $item['duration']); ?></span>
                                </div>
                                <div class="video-card-info">
                                    <h3 class="video-card-title"><?php echo htmlspecialchars($item['title']); ?></h3>
                                    <p class="video-card-channel"><?php echo htmlspecialchars($item['creator']); ?></p>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script src="/ZouTube/js/player.js"></script>
</body>
</html>