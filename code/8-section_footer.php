<?php
$_footer = "
<footer id='footer' class='footer-section'>
    <div class='footer-container'>
        <!-- Logo y información de la banda -->
        <div class='footer-brand'>
            <img src='../img/5am_Logo_01-01.png' alt='The 5 AM Logo' class='footer-logo' onerror='this.style.display=\"none\"; this.nextElementSibling.style.display=\"block\"'>
            <div class='footer-logo-text' style='display: none;'>The 5 AM</div>
            <p class='footer-copyright'>© 2017 - " . date('Y') . " The 5 AM</p>
            <p class='footer-tagline'>Rock your world since 2017</p>
        </div>

        <!-- Newsletter Subscription -->
        <div class='footer-newsletter'>
            <div class='newsletter-content'>
                <h3 class='newsletter-title'>Stay in the Loop</h3>
                <p class='newsletter-description'>Get the latest news, tour dates, and exclusive content directly to your inbox.</p>
                
                <form id='newsletter-form' class='newsletter-form' action='#' method='post'>
                    <div class='newsletter-input-group'>
                        <input type='email' id='newsletter-email' name='email' placeholder='Enter your email' required class='newsletter-input'>
                        <button type='submit' class='newsletter-btn'>
                            <i class='fas fa-paper-plane'></i>
                            <span class='newsletter-btn-text'>Subscribe</span>
                        </button>
                    </div>
                    <div id='newsletter-message' class='newsletter-message'></div>
                </form>
                
                <p class='newsletter-privacy'>
                    <i class='fas fa-lock'></i>
                    We respect your privacy. Unsubscribe at any time.
                </p>
            </div>
        </div>

        <!-- Social Media Links -->
        <div class='footer-social'>
            <h4 class='footer-social-title'>Follow Us</h4>
            <div class='footer-social-icons'>
                <a href='http://www.facebook.com/pages/page/690619354292715' class='footer-social-link' aria-label='Facebook'>
                    <i class='fab fa-facebook-f'></i>
                </a>
                <a href='https://instagram.com/the5am_official' class='footer-social-link' aria-label='Instagram'>
                    <i class='fab fa-instagram'></i>
                </a>
                <a href='https://x.com/The5AM_Official' class='footer-social-link' aria-label='Twitter'>
                    <i class='fab fa-twitter'></i>
                </a>
                <a href='https://www.youtube.com/channel/UCydSo9en3IlGKmgnU8Xuc3Q' class='footer-social-link' aria-label='YouTube'>
                    <i class='fab fa-youtube'></i>
                </a>
                <a href='https://open.spotify.com/artist/2KrYW1HYywqXzW12p4lsc1' class='footer-social-link' aria-label='Spotify'>
                    <i class='fab fa-spotify'></i>
                </a>
            </div>
        </div>

        <!-- Quick Links -->
        <div class='footer-links'>
            <h4 class='footer-links-title'>Quick Links</h4>
            <ul class='footer-links-list'>
                <li><a href='#about' class='footer-link'>About</a></li>
                <li><a href='#music' class='footer-link'>Music</a></li>
                <li><a href='#events' class='footer-link'>Tour Dates</a></li>
                <li><a href='#press' class='footer-link'>Press</a></li>
                <li><a href='https://the-5-am-official-merchandise.myspreadshop.net/' class='footer-link' target='_blank'>Merch Store</a></li>
                <li><a href='mailto:info@the5am.be' class='footer-link'>Contact</a></li>
            </ul>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class='footer-bottom'>
        <div class='footer-bottom-content'>
            <p class='footer-bottom-text'>
                Made with <i class='fas fa-heart footer-heart'></i> for rock music lovers worldwide
            </p>
            <div class='footer-bottom-links'>
                <a href='mailto:info@the5am.be' class='footer-bottom-link'>info@the5am.be</a>
                <span class='footer-divider'>•</span>
                <a href='#' class='footer-bottom-link'>Privacy Policy</a>
            </div>
        </div>
    </div>
</footer>";
?>