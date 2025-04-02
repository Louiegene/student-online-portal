
$(document).ready(function () {
    $('#carouselExample').carousel({
        interval: 3000,
        pause: 'hover'
    });
});


function prevSlide() {
    $('#carouselExample').carousel('prev');
}

function nextSlide() {
    $('#carouselExample').carousel('next');
}


