    document.addEventListener("DOMContentLoaded", function () {
        const hiddenVideos = document.querySelector(".hidden-videos");
        const toggleButton = document.getElementById("toggleVideos");

        toggleButton.addEventListener("click", function () {
            if (hiddenVideos.style.display === "none") {
                hiddenVideos.style.display = "flex";
                toggleButton.textContent = "Watch less videos";
            } else {
                hiddenVideos.style.display = "none";
                toggleButton.textContent = "Watch more videos";
            }
        });
    });