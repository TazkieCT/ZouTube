document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('mainVideo');
    const playPauseBtn = document.getElementById('playPauseBtn');
    const playIcon = playPauseBtn.querySelector('.play-icon');
    const pauseIcon = playPauseBtn.querySelector('.pause-icon');
    const progressFill = document.getElementById('progressFill');
    const progressBar = document.querySelector('.progress-bar');
    const currentTimeDisplay = document.getElementById('currentTime');
    const durationDisplay = document.getElementById('duration');
    const volumeBtn = document.getElementById('volumeBtn');
    const volumeSlider = document.getElementById('volumeSlider');
    const fullscreenBtn = document.getElementById('fullscreenBtn');
    const fullscreenIcon = fullscreenBtn.querySelector('.fullscreen-icon');
    const exitFullscreenIcon = fullscreenBtn.querySelector('.exit-fullscreen-icon');
    const videoControls = document.getElementById('videoControls');
    
    function togglePlayPause() {
        if (video.paused) {
            video.play();
            playIcon.style.display = 'none';
            pauseIcon.style.display = 'block';
        } else {
            video.pause();
            playIcon.style.display = 'block';
            pauseIcon.style.display = 'none';
        }
    }
    
    playPauseBtn.addEventListener('click', togglePlayPause);
    video.addEventListener('click', togglePlayPause);
    
    video.addEventListener('timeupdate', function() {
        const progress = (video.currentTime / video.duration) * 100;
        progressFill.style.width = `${progress}%`;
        
        const currentMinutes = Math.floor(video.currentTime / 60);
        const currentSeconds = Math.floor(video.currentTime % 60);
        currentTimeDisplay.textContent = `${currentMinutes}:${currentSeconds < 10 ? '0' : ''}${currentSeconds}`;
    });
    
    video.addEventListener('loadedmetadata', function () {
        if (isNaN(video.duration) || video.duration === Infinity || video.duration === 0) {
            video.load();
        }

        const durationMinutes = Math.floor(video.duration / 60);
        const durationSeconds = Math.floor(video.duration % 60);
        durationDisplay.textContent = `${durationMinutes}:${durationSeconds < 10 ? '0' : ''}${durationSeconds}`;
    });

    setTimeout(() => {
        if (!isNaN(video.duration) && video.duration > 0) {
            const durationMinutes = Math.floor(video.duration / 60);
            const durationSeconds = Math.floor(video.duration % 60);
            durationDisplay.textContent = `${durationMinutes}:${durationSeconds < 10 ? '0' : ''}${durationSeconds}`;
        }
    }, 2000);

    
    progressBar.addEventListener('click', function(e) {
        const progressBarRect = progressBar.getBoundingClientRect();
        const clickPosition = e.clientX - progressBarRect.left;
        const progressBarWidth = progressBarRect.width;
        const seekTime = (clickPosition / progressBarWidth) * video.duration;
        
        video.currentTime = seekTime;
    });
    
    volumeSlider.addEventListener('input', function() {
        video.volume = volumeSlider.value / 100;
        if (video.volume === 0) {
            volumeBtn.innerHTML = '<svg viewBox="0 0 24 24" width="24" height="24"><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"></path></svg>';
        } else if (video.volume < 0.5) {
            volumeBtn.innerHTML = '<svg viewBox="0 0 24 24" width="24" height="24"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"></path></svg>';
        } else {
            volumeBtn.innerHTML = '<svg viewBox="0 0 24 24" width="24" height="24"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"></path></svg>';
        }
    });
    
    volumeBtn.addEventListener('click', function() {
        if (video.volume > 0) {
            volumeBtn.dataset.previousVolume = video.volume;
            video.volume = 0;
            volumeSlider.value = 0;
            volumeBtn.innerHTML = '<svg viewBox="0 0 24 24" width="24" height="24"><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"></path></svg>';
        } else {
            const previousVolume = parseFloat(volumeBtn.dataset.previousVolume) || 1;
            video.volume = previousVolume;
            volumeSlider.value = previousVolume * 100;
            
            if (previousVolume < 0.5) {
                volumeBtn.innerHTML = '<svg viewBox="0 0 24 24" width="24" height="24"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"></path></svg>';
            } else {
                volumeBtn.innerHTML = '<svg viewBox="0 0 24 24" width="24" height="24"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"></path></svg>';
            }
        }
    });
    
    // This is Fullscreen feature
    fullscreenBtn.addEventListener('click', function() {
        if (document.fullscreenElement) {
            document.exitFullscreen();
            fullscreenIcon.style.display = 'block';
            exitFullscreenIcon.style.display = 'none';
        } else {
            const videoPlayer = document.querySelector('.video-player');
            videoPlayer.requestFullscreen();
            fullscreenIcon.style.display = 'none';
            exitFullscreenIcon.style.display = 'block';
        }
    });
    
    // Update fullscreen button when entering/exiting fullscreen
    document.addEventListener('fullscreenchange', function() {
        if (document.fullscreenElement) {
            fullscreenIcon.style.display = 'none';
            exitFullscreenIcon.style.display = 'block';
        } else {
            fullscreenIcon.style.display = 'block';
            exitFullscreenIcon.style.display = 'none';
        }
    });
    
    // Hide controls when mouse is inactive (From line 116-141 gacorrrr)
    let controlsTimeout;
    
    function hideControls() {
        videoControls.style.opacity = '0';
    }
    
    function showControls() {
        videoControls.style.opacity = '1';
        
        clearTimeout(controlsTimeout);
        
        controlsTimeout = setTimeout(hideControls, 3000);
    }
    
    const videoPlayer = document.querySelector('.video-player');
    videoPlayer.addEventListener('mousemove', showControls);
    videoPlayer.addEventListener('mouseleave', hideControls);
    
    videoControls.addEventListener('mouseenter', function() {
        clearTimeout(controlsTimeout);
    });
    
    videoControls.addEventListener('mouseleave', function() {
        controlsTimeout = setTimeout(hideControls, 3000);
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.code === 'Space') {
            e.preventDefault();
            togglePlayPause();
        } else if (e.code === 'ArrowRight') {
            video.currentTime += 10;
        } else if (e.code === 'ArrowLeft') {
            video.currentTime -= 10;
        } else if (e.code === 'ArrowUp') {
            e.preventDefault();
            const newVolume = Math.min(1, video.volume + 0.1);
            video.volume = newVolume;
            volumeSlider.value = newVolume * 100;
            volumeSlider.dispatchEvent(new Event('input'));
        } else if (e.code === 'ArrowDown') {
            e.preventDefault();
            const newVolume = Math.max(0, video.volume - 0.1);
            video.volume = newVolume;
            volumeSlider.value = newVolume * 100;
            volumeSlider.dispatchEvent(new Event('input'));
        } else if (e.code === 'KeyM') {
            volumeBtn.click();
        } else if (e.code === 'KeyF') {
            fullscreenBtn.click();
        }
    });
    
    video.volume = 0.75;
    volumeSlider.value = 75;

    // video.play().catch((error) => {
    //     console.warn("Autoplay blocked:", error);
    // });
});