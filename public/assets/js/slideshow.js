let images = ["../../public/assets/images/about-us1.jpg", "../../public/assets/images/about-us2.jpg"];
    let index = 0;
    let aboutImage = document.getElementById("aboutImage");

    function changeImage() {
        aboutImage.style.opacity = "0"; // Fade out effect
        setTimeout(() => {
            index = (index + 1) % images.length;
            aboutImage.src = images[index];
            aboutImage.style.opacity = "1"; // Fade in effect
        }, 1000);
    }

    setInterval(changeImage, 5000);