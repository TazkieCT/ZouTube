<?php
$jsonPath = __DIR__ . '/video_data.json';
$videos = [];

if (file_exists($jsonPath)) {
    $videos = json_decode(file_get_contents($jsonPath), true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['video_id'])) {
    $videoId = $_POST['video_id'];

    foreach ($videos as &$video) {
        if ($video['id'] === $videoId) {
            $video['visibility'] = ($video['visibility'] === 'public') ? 'private' : 'public';
            break;
        }
    }
    file_put_contents($jsonPath, json_encode($videos, JSON_PRETTY_PRINT));
}

header("Location: /ZouTube/dashboard.php");
exit;
