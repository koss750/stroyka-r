<div id="image_modal" class="image_modal">
    <div class="image_modal_container">
        <img class="image_modal_content" id="image_modal_img">
        <span class="image_modal_close">&times;</span>
        <a class="image_modal_prev" onclick="changeModalImage(-1)">&lsaquo;</a>
        <a class="image_modal_next" onclick="changeModalImage(1)">&rsaquo;</a>
        <button class="image_modal_zoom" onclick="toggleZoom()">+</button>
    </div>
</div>

<script>
    function toggleZoom() {
    const img = document.getElementById('image_modal_img');
    const container = document.querySelector('.image_modal_container');
    const zoomBtn = document.querySelector('.image_modal_zoom');

    if (img.classList.contains('zoomed')) {
        img.classList.remove('zoomed');
        container.classList.remove('zoomed');
        zoomBtn.textContent = '+';
    } else {
        img.classList.add('zoomed');
        container.classList.add('zoomed');
        zoomBtn.textContent = '-';
    }
}
</script>
<style>
    .image_modal_zoom {
    position: absolute;
    bottom: 10px;
    right: 10px;
    background: rgba(0, 0, 0, 0.5);
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
}

.image_modal_container.zoomed {
    overflow: auto;
}

.image_modal_content.zoomed {
    max-width: none;
    max-height: none;
    cursor: move;
}
</style>