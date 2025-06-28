<?php
// index.php or home.php
// Your actual video data
$videos = [
    [
        "id" => 1,
        "title" => "Dummy Title",
        "description" => "Dummy description untuk apa kau tahu disini...",
        "visibility" => "public",
        "fileName" => "Cody Fry - 'The End' (Album Trailer).mp4",
        "filePath" => "uploads/1751097744_Cody Fry - 'The End' (Album Trailer).mp4",
        "fileSize" => "5.92 MB",
        "duration" => 500
    ],
    [
        "id" => "34101751100298",
        "title" => "Upload sekali lagi",
        "description" => "The best album created ever, made by cody fry",
        "visibility" => "public",
        "fileName" => "Cody Fry - 'The End' (Album Trailer).mp4",
        "filePath" => "uploads/1751100298_Cody Fry - 'The End' (Album Trailer).mp4",
        "fileSize" => "5.92 MB",
        "duration" => 0
    ]
];

// Function to format duration from seconds
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

// Function to generate random views and upload time for demo
function getRandomViews() {
    $views = rand(100, 5000);
    if ($views > 1000) {
        return number_format($views / 1000, 1) . 'K';
    }
    return $views;
}

function getRandomUploadTime() {
    $times = ['2 hours ago', '1 day ago', '3 days ago', '1 week ago', '2 weeks ago', '1 month ago'];
    return $times[array_rand($times)];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZouTube</title>
    <link rel="stylesheet" href="styles/home.css">
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
            <a href="/" class="logo">
                <svg width="30" height="20" viewBox="0 0 30 20" xmlns="http://www.w3.org/2000/svg" class="logo-icon">
                    <rect width="30" height="20" fill="#FF0000" rx="5" />
                    <path d="M12 6L20 10L12 14V6Z" fill="white" />
                </svg>
                <span class="logo-text">ZouTube</span>
            </a>
        </div>
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search">
            <button class="search-button">
                <svg viewBox="0 0 24 24" width="24" height="24">
                    <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"></path>
                </svg>
            </button>
        </div>
        <div class="header-right">
            <button class="create-button" title="Create">
                <svg viewBox="0 0 24 24" width="24" height="24">
                    <path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4zM14 13h-3v3H9v-3H6v-2h3V8h2v3h3v2z"></path>
                </svg>
            </button>
            <button class="notifications-button" title="Notifications">
                <svg viewBox="0 0 24 24" width="24" height="24">
                    <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"></path>
                </svg>
                <span class="notification-badge">3</span>
            </button>
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
                        <a href="/">
                            <svg viewBox="0 0 24 24" width="24" height="24">
                                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"></path>
                            </svg>
                            <span>Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <svg viewBox="0 0 24 24" width="24" height="24">
                                <path d="M14.97 16.95L10 13.87V7h2v5.76l4.03 2.49-1.06 1.7zM12 3c-4.97 0-9 4.03-9 9s4.02 9 9 9 9-4.03 9-9-4.03-9-9-9zm0-2c6.08 0 11 4.93 11 11s-4.92 11-11 11S1 18.07 1 12 5.92 1 12 1z"></path>
                            </svg>
                            <span>History</span>
                        </a>
                    </li>
                    <li>
                        <a href="/upload.php">
                            <svg viewBox="0 0 24 24" width="24" height="24">
                                <path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4zM14 13h-3v3H9v-3H6v-2h3V8h2v3h3v2z"></path>
                            </svg>
                            <span>Upload Video</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <svg viewBox="0 0 24 24" width="24" height="24">
                                <path d="M18 9l-1.41-1.42L10 14.17l-2.59-2.58L6 13l4 4 8-8zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8 8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"></path>
                            </svg>
                            <span>Liked Videos</span>
                        </a>
                    </li>
                </ul>
                
                <div class="sidebar-section">
                    <h3>Subscriptions</h3>
                    <ul>
                        <li>
                            <a href="#">
                                <div class="channel-avatar">C</div>
                                <span>Cody Fry</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="channel-avatar">M</div>
                                <span>Music Channel</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="channel-avatar">Z</div>
                                <span>ZouTube Official</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </aside>

        <main class="content">
            <?php if (empty($videos)): ?>
                <div class="no-videos">
                    <div class="no-videos-icon">
                        <svg viewBox="0 0 24 24" width="48" height="48">
                            <path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z" fill="#606060"></path>
                        </svg>
                    </div>
                    <h2>No videos available</h2>
                    <p>Upload your first video to get started!</p>
                    <a href="/upload.php" class="upload-link">Upload Video</a>
                </div>
            <?php else: ?>
                <div class="video-grid">
                    <?php foreach ($videos as $video): ?>
                        <div class="video-card" onclick="playVideo('<?= htmlspecialchars($video['filePath']) ?>')">
                            <div class="video-thumbnail">
                                <video class="thumbnail-video" preload="metadata">
                                    <source src="<?= htmlspecialchars($video['filePath']) ?>" type="video/mp4">
                                </video>
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
                                            ZouTube Channel
                                            <svg class="verified-icon" viewBox="0 0 24 24" width="14" height="14">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" fill="#606060"></path>
                                            </svg>
                                        </span>
                                        <div class="video-stats">
                                            <span><?= getRandomViews() ?> views</span>
                                            <span>•</span>
                                            <span><?= getRandomUploadTime() ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
        // Menu toggle functionality
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
        });

        // Close sidebar when clicking outside on mobile
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

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth > 768) {
                sidebar.classList.remove('open');
            }
        });

        // Video player functionality
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

        // Close modal when clicking outside
        document.getElementById('videoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeVideoModal();
            }
        });

        // Generate thumbnail from video
        document.addEventListener('DOMContentLoaded', function() {
            const thumbnailVideos = document.querySelectorAll('.thumbnail-video');
            thumbnailVideos.forEach(video => {
                video.addEventListener('loadedmetadata', function() {
                    // Set video to a specific time for thumbnail (e.g., 10% of duration)
                    this.currentTime = this.duration * 0.1;
                });
                
                video.addEventListener('seeked', function() {
                    // Create canvas to capture frame
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    canvas.width = this.videoWidth;
                    canvas.height = this.videoHeight;
                    ctx.drawImage(this, 0, 0, canvas.width, canvas.height);
                    
                    // Convert to image and replace video
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