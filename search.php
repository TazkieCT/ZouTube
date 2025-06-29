<?php
$searchQuery = $_GET['query'] ?? '';

$videos = json_decode(file_get_contents('video_data.json'), true);

function formatDuration($seconds) {
    if ($seconds == 0) return "0:00";
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    $secs = $seconds % 60;
    return $hours > 0 ? sprintf("%d:%02d:%02d", $hours, $minutes, $secs)
                      : sprintf("%d:%02d", $minutes, $secs);
}

$searchResults = [];

if ($searchQuery) {
    $isHashtag = false;
    $searchByDescription = false;

    if (preg_match('/^#(\w+)(\s.*)?$/', $searchQuery, $matches)) {
        $isHashtag = true;
        $hashtag = $matches[1];
        $hasExtra = isset($matches[2]) && trim($matches[2]) !== '';

        if (!$hasExtra) {
            $searchByDescription = true;
        }

        $term = $hashtag;
    } else {
        $term = $searchQuery;
    }

    foreach ($videos as $video) {
        $titleMatch = stripos($video['title'], $term) !== false;
        $descMatch = $searchByDescription && stripos($video['description'], $term) !== false;

        if ($titleMatch || $descMatch) {
            $searchResults[] = $video;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($searchQuery) ?> - ZouTube</title>
    <link rel="stylesheet" href="/ZouTube/styles/search.css">
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
            <a href="/ZouTube/home.php" class="logo">
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
            <div class="header-right">
                <a href="/ZouTube/login.php" class="login-button">Login</a>
            </div>
        </div>
    </header>

    <div class="main-content">
        <main class="search-results-container">
            <div class="search-info">
                <h1 class="search-query">You search for "<?= htmlspecialchars($searchQuery ?: 'All Videos') ?>"</h1>
                <div class="results-count">
                    About <?= number_format(count($searchResults)) ?> results
                </div>
            </div>
            
            <div class="search-results-list">
                <?php foreach ($searchResults as $video): ?>
                    <?php if ($video['visibility'] !== 'public') continue; ?>
                    <div class="search-result-item" onclick="location.href='/ZouTube/player.php?id=<?= $video['id'] ?>'">
                        <div class="result-thumbnail">
                            <img src="<?= htmlspecialchars($video['thumbnailPath']) ?>" alt="<?= htmlspecialchars($video['title']) ?>">
                            <span class="video-duration"><?= formatDuration($video['duration']) ?></span>
                        </div>
                        <div class="result-info">
                            <a href="/player.php?id=<?= $video['id'] ?>" class="result-title">
                                <?= htmlspecialchars($video['title']) ?>
                            </a>
                            <div class="result-channel">
                                <?= htmlspecialchars($video['creator']) ?>
                            </div>
                            <div class="result-description">
                                <?= nl2br(htmlspecialchars($video['description'])) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>

    <script>
        document.getElementById('menuToggle').addEventListener('click', function() {
            console.log('Menu toggle clicked');
        });

        // Handle search form submission
        document.querySelector('.search-container form').addEventListener('submit', function(e) {
            const searchInput = this.querySelector('input[name="q"]');
            if (!searchInput.value.trim()) {
                e.preventDefault();
                searchInput.focus();
            }
        });

        document.querySelectorAll('.search-result-item').forEach(item => {
            item.addEventListener('click', function(e) {
                if (e.target.tagName !== 'A') {
                    const link = this.querySelector('.result-title');
                    if (link) {
                        window.location.href = link.href;
                    }
                }
            });
        });
    </script>
</body>
</html>
