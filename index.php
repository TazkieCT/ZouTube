<?php
$videos = json_decode(file_get_contents('video_data.json'), true);


function formatDuration($seconds) {
    if ($seconds == 0) return "0:00";
    
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    $secs = $seconds % 60;
    
    if ($hours > 0) {
        return sprintf("%d:%02d:%02d", $hours, $minutes, $secs);
    } else {
        return sprintf("%d:%02d", $minutes, $secs);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZouTube</title>
    <link rel="stylesheet" href="/ZouTube/styles/home.css">
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
        <main class="content">
            <?php if (empty($videos)): ?>
                <div class="no-videos">
                    <h2>No videos available</h2>
                </div>
            <?php else: ?>
                <div class="video-grid">
                    <?php foreach ($videos as $video): ?>
                        <?php if ($video['visibility'] !== 'public') continue; ?>
                        <div class="video-card" onclick="playVideo('<?= htmlspecialchars($video['filePath']) ?>')">
                            <a href="/ZouTube/watch.php?id=<?php echo $video['id']; ?>">
                                <div class="video-thumbnail">
                                    <?php if (!empty($video['thumbnailPath']) && file_exists($video['thumbnailPath'])): ?>
                                        <img src="<?= htmlspecialchars($video['thumbnailPath']) ?>" alt="Video Thumbnail" class="thumbnail-image">
                                    <?php else: ?>
                                        <video class="thumbnail-video" preload="metadata">
                                            <source src="<?= htmlspecialchars($video['filePath']) ?>" type="video/mp4">
                                        </video>
                                    <?php endif; ?>

                                    <div class="play-button">
                                        <svg viewBox="0 0 24 24" width="48" height="48">
                                            <path d="M8 5v14l11-7z" fill="white"></path>
                                        </svg>
                                    </div>
                                    <span class="video-duration"><?= formatDuration($video['duration']) ?></span>
                                </div>
                                <div class="video-info">
                                    <div class="video-details">
                                        <h3 class="video-title"><?= htmlspecialchars($video['title']) ?></h3>
                                        <div class="video-meta">
                                            <span class="channel-name">
                                                <?php echo htmlspecialchars($video['creator']); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <!-- Video Player Modal -->
    <div class="video-modal" id="videoModal" style="display: none;">
        <div class="video-modal-content">
            <button class="close-modal" onclick="closeVideoModal()">&times;</button>
            <video id="modalVideo" controls width="100%">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>

    <script>
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
        });

        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const menuToggle = document.getElementById('menuToggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(e.target) && 
                !menuToggle.contains(e.target) && 
                sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
            }
        });

        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth > 768) {
                sidebar.classList.remove('open');
            }
        });

        function playVideo(videoPath) {
            const modal = document.getElementById('videoModal');
            const video = document.getElementById('modalVideo');
            video.src = videoPath;
            modal.style.display = 'flex';
            video.play();
        }

        function closeVideoModal() {
            const modal = document.getElementById('videoModal');
            const video = document.getElementById('modalVideo');
            video.pause();
            video.src = '';
            modal.style.display = 'none';
        }

        document.getElementById('videoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeVideoModal();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const thumbnailVideos = document.querySelectorAll('.thumbnail-video');
            thumbnailVideos.forEach(video => {
                video.addEventListener('loadedmetadata', function() {
                    this.currentTime = this.duration * 0.1;
                });
                
                video.addEventListener('seeked', function() {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    canvas.width = this.videoWidth;
                    canvas.height = this.videoHeight;
                    ctx.drawImage(this, 0, 0, canvas.width, canvas.height);
                    
                    const img = document.createElement('img');
                    img.src = canvas.toDataURL();
                    img.alt = 'Video thumbnail';
                    img.style.width = '100%';
                    img.style.height = '100%';
                    img.style.objectFit = 'cover';
                    
                    this.parentNode.replaceChild(img, this);
                });
            });
        });
    </script>
</body>
</html>