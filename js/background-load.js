// En tu archivo scroll.js o crea un nuevo archivo para el fondo
document.addEventListener("DOMContentLoaded", function() {
    // Precargar la imagen de fondo para una transición suave
    const bgImage = new Image();
    bgImage.src = '../img/big-lights-city-banner.webp';
    
    bgImage.onload = function() {
        // Una vez cargada, agregar una clase para transición suave
        document.querySelector('.background').style.opacity = '1';
    };
    
    bgImage.onerror = function() {
        console.error('Error loading background image');
        // Fallback a un color sólido si la imagen no carga
        document.querySelector('.background').style.background = '#000';
    };
});