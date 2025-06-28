<?php
// index.php or home.php
// Sample video data - in a real application, this would come from a database
$videos = [
    [
        'id' => 1,
        'title' => 'How to Build a Modern Web Application',
        'thumbnail' => '/placeholder.svg?height=180&width=320',
        'duration' => '15:42',
        'channel' => 'TechTutorials',
        'views' => '1.2M',
        'uploaded' => '2 days ago',
        'verified' => true
    ],
    [
        'id' => 2,
        'title' => 'Amazing Nature Documentary - Wildlife in 4K',
        'thumbnail' => '/placeholder.svg?height=180&width=320',
        'duration' => '45:18',
        'channel' => 'NatureWorld',
        'views' => '856K',
        'uploaded' => '1 week ago',
        'verified' => true
    ],
    [
        'id' => 3,
        'title' => 'Cooking the Perfect Pasta - Italian Recipe',
        'thumbnail' => '/placeholder.svg?height=180&width=320',
        'duration' => '8:25',
        'channel' => 'CookingMaster',
        'views' => '432K',
        'uploaded' => '3 days ago',
        'verified' => false
    ],
    [
        'id' => 4,
        'title' => 'Latest Tech News and Reviews 2024',
        'thumbnail' => '/placeholder.svg?height=180&width=320',
        'duration' => '22:15',
        'channel' => 'TechReview',
        'views' => '2.1M',
        'uploaded' => '5 hours ago',
        'verified' => true
    ],
    [
        'id' => 5,
        'title' => 'Relaxing Music for Study and Work',
        'thumbnail' => '/placeholder.svg?height=180&width=320',
        'duration' => '2:15:30',
        'channel' => 'ChillBeats',
        'views' => '5.8M',
        'uploaded' => '1 month ago',
        'verified' => false
    ],
    [
        'id' => 6,
        'title' => 'Funny Cat Compilation 2024',
        'thumbnail' => '/placeholder.svg?height=180&width=320',
        'duration' => '12:08',
        'channel' => 'PetFunny',
        'views' => '3.4M',
        'uploaded' => '4 days ago',
        'verified' => false
    ],
    [
        'id' => 7,
        'title' => 'Learn JavaScript in 30 Minutes',
        'thumbnail' => '/placeholder.svg?height=180&width=320',
        'duration' => '28:45',
        'channel' => 'CodeAcademy',
        'views' => '987K',
        'uploaded' => '1 week ago',
        'verified' => true
    ],
    [
        'id' => 8,
        'title' => 'Travel Vlog: Exploring Tokyo Japan',
        'thumbnail' => '/placeholder.svg?height=180&width=320',
        'duration' => '18:32',
        'channel' => 'TravelWithMe',
        'views' => '1.5M',
        'uploaded' => '2 weeks ago',
        'verified' => true
    ],
    [
        'id' => 9,
        'title' => 'DIY Home Improvement Tips',
        'thumbnail' => '/placeholder.svg?height=180&width=320',
        'duration' => '14:20',
        'channel' => 'HomeFixIt',
        'views' => '623K',
        'uploaded' => '6 days ago',
        'verified' => false
    ],
    [
        'id' => 10,
        'title' => 'Gaming Review: Latest AAA Game',
        'thumbnail' => '/placeholder.svg?height=180&width=320',
        'duration' => '35:12',
        'channel' => 'GameReviews',
        'views' => '2.8M',
        'uploaded' => '3 days ago',
        'verified' => true
    ],
    [
        'id' => 11,
        'title' => 'Fitness Workout for Beginners',
        'thumbnail' => '/placeholder.svg?height=180&width=320',
        'duration' => '25:18',
        'channel' => 'FitLife',
        'views' => '1.1M',
        'uploaded' => '1 week ago',
        'verified' => false
    ],
    [
        'id' => 12,
        'title' => 'Science Explained: Quantum Physics',
        'thumbnail' => '/placeholder.svg?height=180&width=320',
        'duration' => '42:55',
        'channel' => 'ScienceHub',
        'views' => '756K',
        'uploaded' => '5 days ago',
        'verified' => true
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZouTube</title>
    <link rel="stylesheet" href="/Zoutube/styles/home.css">
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
                                <path d="M18 9l-1.41-1.42L10 14.17l-2.59-2.58L6 13l4 4 8-8zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"></path>
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
                                <div class="channel-avatar">T</div>
                                <span>TechTutorials</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="channel-avatar">N</div>
                                <span>NatureWorld</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="channel-avatar">C</div>
                                <span>CookingMaster</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </aside>

        <main class="content">
            <div class="video-grid">
                <?php foreach ($videos as $video): ?>
                    <div class="video-card">
                        <div class="video-thumbnail">
                            <img src="<?= $video['thumbnail'] ?>" alt="<?= htmlspecialchars($video['title']) ?>">
                            <span class="video-duration"><?= $video['duration'] ?></span>
                        </div>
                        <div class="video-info">
                            <div class="video-details">
                                <h3 class="video-title"><?= htmlspecialchars($video['title']) ?></h3>
                                <div class="video-meta">
                                    <span class="channel-name">
                                        <?= htmlspecialchars($video['channel']) ?>
                                        <?php if ($video['verified']): ?>
                                            <svg class="verified-icon" viewBox="0 0 24 24" width="14" height="14">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" fill="#606060"></path>
                                            </svg>
                                        <?php endif; ?>
                                    </span>
                                    <div class="video-stats">
                                        <span><?= $video['views'] ?> views</span>
                                        <span>•</span>
                                        <span><?= $video['uploaded'] ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
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
    </script>
</body>
</html>