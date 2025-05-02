document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    const fileInput = document.getElementById('fileInput');
    const uploadArea = document.getElementById('uploadArea');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const videoPreviewContainer = document.getElementById('videoPreviewContainer');
    const videoPreview = document.getElementById('videoPreview');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const videoDuration = document.getElementById('videoDuration');
    const changeVideoButton = document.getElementById('changeVideoButton');
    const thumbnailsContainer = document.getElementById('thumbnailsContainer');
    const videoTitle = document.getElementById('videoTitle');
    const videoDescription = document.getElementById('videoDescription');
    const titleCount = document.getElementById('titleCount');
    const descCount = document.getElementById('descCount');
    const publishButton = document.getElementById('publishButton');
    
    menuToggle.addEventListener('click', function() {
        sidebar.classList.toggle('open');
    });
    
    document.addEventListener('click', function(event) {
        const isClickInsideSidebar = sidebar.contains(event.target);
        const isClickOnMenuToggle = menuToggle.contains(event.target);
        
        if (!isClickInsideSidebar && !isClickOnMenuToggle && window.innerWidth <= 768) {
            sidebar.classList.remove('open');
        }
    });
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight() {
        uploadArea.classList.add('drag-over');
    }
    
    function unhighlight() {
        uploadArea.classList.remove('drag-over');
    }
    
    uploadArea.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0 && files[0].type.startsWith('video/')) {
            handleFiles(files);
        }
    }
    
    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            handleFiles(this.files);
        }
    });
    
    changeVideoButton.addEventListener('click', function() {
        fileInput.click();
    });
    
    function handleFiles(files) {
        const file = files[0];
        
        if (!file.type.startsWith('video/')) {
            alert('Please select a valid video file.');
            return;
        }
        
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        
        const videoURL = URL.createObjectURL(file);
        videoPreview.src = videoURL;
        
        uploadPlaceholder.style.display = 'none';
        videoPreviewContainer.style.display = 'block';
        
        videoPreview.addEventListener('loadedmetadata', function() {
            videoDuration.textContent = formatDuration(videoPreview.duration);
            
            generateThumbnails(videoPreview);
        });
        
        checkPublishButton();
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
        
        let result = '';
        
        if (hours > 0) {
            result += hours + ':';
            result += (minutes < 10 ? '0' : '') + minutes + ':';
        } else {
            result += minutes + ':';
        }
        
        result += (secs < 10 ? '0' : '') + secs;
        
        return result;
    }
    
function generateThumbnails(videoElement) {
    thumbnailsContainer.innerHTML = '';
    
    const duration = videoElement.duration;
    const thumbnailCount = 3;
    
    const canvas = document.createElement('canvas');
    const context = canvas.getContext('2d');
    
    const videoWidth = videoElement.videoWidth;
    const videoHeight = videoElement.videoHeight;
    canvas.width = 320;
    canvas.height = (videoHeight / videoWidth) * 320;
    
    const thumbnailItems = [];
    for (let i = 0; i < thumbnailCount; i++) {
        const thumbnailItem = document.createElement('div');
        thumbnailItem.className = 'thumbnail-item';
        const thumbnailImage = document.createElement('img');
        thumbnailItem.appendChild(thumbnailImage);
        thumbnailsContainer.appendChild(thumbnailItem);
        thumbnailItems.push({
            element: thumbnailItem,
            timePoint: duration * ((i + 1) / (thumbnailCount + 1))
        });
        
        if (i === 0) {
            thumbnailItem.classList.add('selected');
        }
    }
    
    processThumbnail(0);
    
    function processThumbnail(index) {
        if (index >= thumbnailItems.length) return;
        
        const item = thumbnailItems[index];
        videoElement.currentTime = item.timePoint;
        
        videoElement.addEventListener('seeked', function seekListener() {
            videoElement.removeEventListener('seeked', seekListener);
            
            context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);
            
            const dataURL = canvas.toDataURL('image/jpeg');
            
            item.element.querySelector('img').src = dataURL;
            
            item.element.addEventListener('click', function() {
                document.querySelectorAll('.thumbnail-item').forEach(el => {
                    el.classList.remove('selected');
                });
                this.classList.add('selected');
            });
            
            processThumbnail(index + 1);
        });
    }
}
    
    videoTitle.addEventListener('input', function() {
        titleCount.textContent = this.value.length;
        checkPublishButton();
    });
    
    videoDescription.addEventListener('input', function() {
        descCount.textContent = this.value.length;
    });
    
    function checkPublishButton() {
        if (videoTitle.value.trim() !== '' && videoPreview.src !== '') {
            publishButton.disabled = false;
        } else {
            publishButton.disabled = true;
        }
    }
    
    publishButton.addEventListener('click', function() {
        const visibility = document.querySelector('input[name="visibility"]:checked').value;
        
        const selectedThumbnail = document.querySelector('.thumbnail-item.selected img');
        
        const videoData = {
            title: videoTitle.value,
            description: videoDescription.value,
            visibility: visibility,
            thumbnail: selectedThumbnail ? selectedThumbnail.src : null,
            fileName: fileName.textContent,
            fileSize: fileSize.textContent,
            duration: videoDuration.textContent
        };
        
        console.log('Publishing video:', videoData);
        
        alert('Video uploaded successfully!');
        
        resetForm();
    });
    
    function resetForm() {
        videoPreview.src = '';
        uploadPlaceholder.style.display = 'flex';
        videoPreviewContainer.style.display = 'none';
        videoTitle.value = '';
        videoDescription.value = '';
        titleCount.textContent = '0';
        descCount.textContent = '0';
        thumbnailsContainer.innerHTML = `
            <div class="thumbnail-placeholder">
                <svg viewBox="0 0 24 24" width="24" height="24">
                    <path d="M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"></path>
                    <path d="M14.14 11.86l-3 3.87L9 13.14 6 17h12l-3.86-5.14z"></path>
                </svg>
                <p>Upload your video to generate thumbnails</p>
            </div>
        `;
        publishButton.disabled = true;
        document.getElementById('visibilityPublic').checked = true;
    }
});