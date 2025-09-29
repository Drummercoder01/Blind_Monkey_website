<?php
$_inhoud .= "
<section id='home' class='min-vh-100 d-flex align-items-center'>
    <div class='container'>
        <div class='home-content text-center' style='padding: 1rem 0;'>
            <!-- Ticket con cuadro negro transparente alrededor -->
            <div class='ticket-banner'>
                <div class='ticket-container'>
                    <!-- Logo animado en esquina superior -->
                    <div class='ticket-logo-overlay'>
                        <img src='../img/5am_Logo_01-01.png' alt='The 5 AM Logo' class='ticket-logo'>
                    </div>
                    
                    <div class='ticket-sale text-center rounded'>
                        <!-- Contenedor principal con efecto de tarjeta de entrada -->
                        <div class='ticket-card'>
                            <!-- Cabecera de la tarjeta con efecto de perforación -->
                            <div class='ticket-perforation'></div>
                            
                            <!-- Contenedor de la imagen con mejoras visuales -->
                            <div class='ticket-image-container'>
                                <a href='https://www.nowonlinetickets.nl/Shop/EventDetails/B19FA38854B2D17A9C77AAA320A6E2AB'
                                    target='_blank' class='ticket-link'>
                                    <div class='image-overlay'>
                                        <img src='../img/big-lights-city-banner.webp' alt='Event Tickets'
                                            class='ticket-image'>
                                        <div class='overlay-content'>
                                            <span class='cta-text'>¡BUY TICKETS!</span>
                                            <span class='cta-arrow'>➜</span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Información del evento -->
                            <div class='event-info'>
                                <h3 class='event-title'>ALBUM RELEASE PARTY</h3>
                                <div class='event-details'>
                                    <p class='event-date'><i class='fas fa-calendar-alt'></i> 07-FEB-2026</p>
                                    <p class='event-location'><i class='fas fa-map-marker-alt'></i> Skybar MAS - Antwerpen</p>
                                </div>
                            </div>

                            <!-- Contador regresivo o indicador de urgencia -->
                            <div class='urgency-indicator'>
                                <div class='limited-tickets'>
                                    <i class='fas fa-ticket-alt'></i>
                                    <span>Limited tickets</span>
                                </div>
                                <div class='countdown-timer' id='countdown'>
                                    <span class='countdown-text'>Don't miss out!</span>
                                </div>
                            </div>

                            <!-- Botón de acción principal -->
                            <div class='ticket-action'>
                                <a href='https://www.nowonlinetickets.nl/Shop/EventDetails/B19FA38854B2D17A9C77AAA320A6E2AB'
                                    target='_blank' class='btn-ticket-purchase'>
                                    <span>Buy Tickets Now</span>
                                    <i class='fas fa-arrow-right'></i>
                                </a>
                                <p class='secure-booking'><i class='fas fa-lock'></i> Secure and guaranteed reservation</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Widgets Sociales ACTUALIZADOS -->
            <div class='social-icons'>
                <!-- Facebook -->
                <a href='http://www.facebook.com/pages/page/690619354292715' class='social-icon' target='_blank' aria-label='Facebook'>
                    <i class='fab fa-facebook-f'></i>
                </a>

                <!-- Spotify -->
                <a href='https://open.spotify.com/artist/2KrYW1HYywqXzW12p4lsc1' class='social-icon' target='_blank' aria-label='Spotify'>
                    <i class='fab fa-spotify'></i>
                </a>

                <!-- Instagram -->
                <a href='https://instagram.com/the5am_official' class='social-icon' target='_blank' aria-label='Instagram'>
                    <i class='fab fa-instagram'></i>
                </a>
                
                <!-- YouTube -->
                <a href='https://www.youtube.com/channel/UCydSo9en3IlGKmgnU8Xuc3Q' class='social-icon' target='_blank' aria-label='YouTube'>
                    <i class='fab fa-youtube'></i>
                </a>
                
                <!-- TikTok -->
                <a href='https://www.tiktok.com/@the5am_official' class='social-icon' target='_blank' aria-label='TikTok'>
                    <i class='fab fa-tiktok'></i>
                </a>
                
                <!-- Reverbnation -->
                <a href='http://www.reverbnation.com/the5am' class='social-icon' target='_blank' aria-label='Reverbnation'>
                    <i class='fas fa-star'></i>
                </a>
            </div>
        </div>
    </div>

    <script>
            // Animación adicional cuando la página carga
        document.addEventListener('DOMContentLoaded', function() {
            const logo = document.querySelector('.ticket-logo-overlay');
            
            // Agregar clase de animación después de un pequeño delay
            setTimeout(() => {
                logo.style.animation = 'logoEntrance 1.5s ease-out forwards, logoFloat 4s ease-in-out infinite 1.5s';
            }, 500);
            
            // Efecto especial al hacer clic en el logo
            logo.addEventListener('click', function() {
                this.style.animation = 'logoEntrance 0.8s ease-out forwards';
                setTimeout(() => {
                    this.style.animation = 'logoEntrance 1.5s ease-out forwards, logoFloat 4s ease-in-out infinite 1.5s';
                }, 800);
            });
        });
    </script>
</section>
";
?>