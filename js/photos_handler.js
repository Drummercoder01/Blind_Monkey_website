// photos_handler.js - VERSIÓN MEJORADA CON MOSTRAR/OCULTAR DINÁMICO
document.addEventListener("DOMContentLoaded", function () {
    const galleryContainer = document.getElementById("gallery-container");
    const photosLoading = document.getElementById("photos-loading");
    const noPhotosState = document.getElementById("no-photos-state");
    const photosButtonContainer = document.getElementById("photos-button-container");
    const toggleButton = document.getElementById("togglePhotos");
    
    let allImages = [];
    let showingAll = false;
    const initialVisibleCount = 8;
    let additionalImagesElements = []; // Array para guardar elementos de fotos adicionales

    function loadPhotos() {
        fetch('../scripts/get_photos.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success' && data.photos.length > 0) {
                    allImages = data.photos;
                    renderInitialPhotos();
                    photosLoading.classList.add('d-none');
                    
                    if (allImages.length > initialVisibleCount) {
                        photosButtonContainer.classList.remove('d-none');
                        updateButtonState();
                    }
                } else {
                    showNoPhotosState();
                }
            })
            .catch(error => {
                console.error('Error loading photos:', error);
                showNoPhotosState();
            });
    }

    function showNoPhotosState() {
        photosLoading.classList.add('d-none');
        noPhotosState.classList.remove('d-none');
        photosButtonContainer.classList.add('d-none');
    }

    function renderInitialPhotos() {
        galleryContainer.innerHTML = '';
        additionalImagesElements = []; // Resetear array
        
        // Crear y mostrar fotos iniciales
        const initialImages = allImages.slice(0, initialVisibleCount);
        
        initialImages.forEach((photo, index) => {
            createImageElement(photo, index, true);
        });
        
        // Pre-crear (pero no mostrar) fotos adicionales
        if (allImages.length > initialVisibleCount) {
            const additionalImages = allImages.slice(initialVisibleCount);
            
            additionalImages.forEach((photo, index) => {
                const globalIndex = initialVisibleCount + index;
                const element = createImageElement(photo, globalIndex, false);
                additionalImagesElements.push(element);
            });
        }
        
        updateButtonState();
    }

    function createImageElement(photo, index, isVisible) {
        const imgDiv = document.createElement("div");
        imgDiv.classList.add("gallery-item");
        if (!isVisible) {
            imgDiv.style.display = 'none'; // Ocultar inicialmente si no es visible
        }
        imgDiv.setAttribute('data-index', index);
        
        const img = document.createElement("img");
        img.src = photo.img_path;
        img.alt = "Photo " + (index + 1);
        img.loading = "lazy";
        img.onclick = () => openLightbox(index);
        
        imgDiv.appendChild(img);
        galleryContainer.appendChild(imgDiv);
        
        // Animación de aparición solo si es visible
        if (isVisible) {
            setTimeout(() => {
                imgDiv.classList.add("visible");
            }, index * 100);
        }
        
        return imgDiv;
    }

    function showAdditionalPhotos() {
        // Mostrar fotos adicionales con animación escalonada
        additionalImagesElements.forEach((imgDiv, index) => {
            setTimeout(() => {
                imgDiv.style.display = 'block';
                // Forzar reflow para que la animación funcione
                void imgDiv.offsetWidth;
                imgDiv.classList.add("visible");
            }, index * 100);
        });
        
        showingAll = true;
        updateButtonState();
    }

    function hideAdditionalPhotos() {
        // Ocultar fotos adicionales con animación
        additionalImagesElements.forEach((imgDiv, index) => {
            setTimeout(() => {
                imgDiv.classList.remove("visible");
                // Esperar a que termine la animación antes de ocultar
                setTimeout(() => {
                    imgDiv.style.display = 'none';
                }, 300);
            }, index * 50); // Animación más rápida para ocultar
        });
        
        showingAll = false;
        updateButtonState();
        
        // Scroll suave después de ocultar
        setTimeout(() => {
            document.getElementById('photos-1').scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });
        }, 500);
    }

    function updateButtonState() {
        if (!toggleButton || !photosButtonContainer) return;
        
        if (allImages.length <= initialVisibleCount) {
            photosButtonContainer.classList.add('d-none');
            return;
        }
        
        photosButtonContainer.classList.remove('d-none');
        
        const buttonText = toggleButton.querySelector('.button-text');
        const buttonIcon = toggleButton.querySelector('i');
        
        if (showingAll) {
            buttonText.textContent = 'Show less photos';
            buttonIcon.className = 'bi bi-eye-slash me-2';
        } else {
            buttonText.textContent = 'Show more photos';
            buttonIcon.className = 'bi bi-images me-2';
        }
    }

    function togglePhotosView() {
        if (showingAll) {
            hideAdditionalPhotos();
        } else {
            showAdditionalPhotos();
        }
    }

    // Event listener para el botón
    if (toggleButton) {
        toggleButton.addEventListener("click", togglePhotosView);
    }

    // Funcionalidad del lightbox
    const lightbox = document.getElementById("lightbox");
    const lightboxImg = document.getElementById("lightbox-img");
    let currentIndex = 0;

    function openLightbox(index) {
        currentIndex = index;
        lightboxImg.src = allImages[index].img_path;
        lightbox.classList.add("show");
        document.body.style.overflow = "hidden";
    }

    function closeLightbox() {
        lightbox.classList.remove("show");
        document.body.style.overflow = "";
    }

    function showNext() {
        currentIndex = (currentIndex + 1) % allImages.length;
        lightboxImg.src = allImages[currentIndex].img_path;
    }

    function showPrev() {
        currentIndex = (currentIndex - 1 + allImages.length) % allImages.length;
        lightboxImg.src = allImages[currentIndex].img_path;
    }

    // Event listeners para lightbox
    lightbox.addEventListener("click", (e) => {
        if (e.target === lightbox) closeLightbox();
    });

    const closeBtn = document.querySelector(".close");
    const prevBtn = document.querySelector(".prev");
    const nextBtn = document.querySelector(".next");
    
    if (closeBtn) closeBtn.onclick = closeLightbox;
    if (prevBtn) prevBtn.onclick = showPrev;
    if (nextBtn) nextBtn.onclick = showNext;

    // Navegación con teclado
    document.addEventListener("keydown", (e) => {
        if (lightbox.classList.contains("show")) {
            if (e.key === "Escape") closeLightbox();
            if (e.key === "ArrowRight") showNext();
            if (e.key === "ArrowLeft") showPrev();
        }
    });

    // Función global para refrescar
    window.refreshPhotos = function() {
        showingAll = false;
        renderInitialPhotos();
    };

    // Iniciar carga de fotos
    loadPhotos();
});