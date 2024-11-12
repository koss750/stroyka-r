@props(['jpgImageUrls', 'thumbImageUrls'])

<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        @foreach($jpgImageUrls as $index => $url)
            <div class="carousel-item @if($loop->first) active @endif">
                <img src="{{ $url }}" class="d-block w-100" onclick="openImageModal(this.src)">
            </div>
        @endforeach
        <a class="carousel-control-prev custom-arrow" href="#" role="button" data-slide="prev">
            <span class="custom-arrow-icon" aria-hidden="true">&lsaquo;</span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next custom-arrow" href="#" role="button" data-slide="next">
            <span class="custom-arrow-icon" aria-hidden="true">&rsaquo;</span>
            <span class="sr-only">Next</span>
        </a>
    </div>
    
    <ol class="carousel-indicators">
        @foreach($thumbImageUrls as $index => $url)
            <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $index }}" 
                @if($loop->first) class="active" @endif
                style="background-image: url('{{ $url }}');">
            </li>
        @endforeach
    </ol>
</div>
<script>
function openImageModal(imgSrc) {
    var modal = document.getElementById("image_modal");
    var modalImg = document.getElementById("image_modal_img");
    modal.style.display = "block";
    modalImg.src = imgSrc;
}

document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById("image_modal");
    var span = document.getElementsByClassName("image_modal_close")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // Close the modal when clicking outside the image
    modal.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Prevent clicks on the image from closing the modal
    document.getElementById("image_modal_img").onclick = function(event) {
        event.stopPropagation();
    }
});
</script>