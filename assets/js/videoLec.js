function changeVideo(url) {
    let videoId = '';
    if (url.includes('youtube.com/watch?v=')) {
        videoId = url.split('v=')[1];
    } else if (url.includes('youtu.be/')) {
        videoId = url.split('youtu.be/')[1];
    }
    let embedUrl = 'https://www.youtube.com/embed/' + videoId;
    document.getElementById('videoPlayer').src = embedUrl;
}

// charge la premiere video
window.onload = function() {
    let firstVideoItem = document.querySelector('.video-item');
    if (firstVideoItem) {
        firstVideoItem.click();
    }
}
