<?php
$_footer = "
<footer id='footer' class='footer-section'>
    <div class='footer-container'>

        <!-- Logo & Band Info -->
        <div class='footer-brand'>
            <img src='../img/blind_monkey_logo.jpg' alt='Blind Monkey Logo' class='footer-logo'
                 onerror='this.style.display=\"none\"; this.nextElementSibling.style.display=\"block\"'>
            <div class='footer-logo-text' style='display: none;'>Blind Monkey</div>
            <p class='footer-tagline'>Rock &middot; Grunge &middot; Antwerpen</p>
            <p class='footer-copyright'>&copy; " . date('Y') . " Blind Monkey. All rights reserved.</p>
        </div>

        <!-- Newsletter -->
        <div class='footer-newsletter'>
            <div class='newsletter-content'>
                <h3 class='newsletter-title'>Stay in the Loop</h3>
                <p class='newsletter-description'>Shows, new releases &amp; exclusive content — straight to your inbox.</p>

                <form id='newsletter-form' class='newsletter-form' action='#' method='post'>
                    <div class='newsletter-input-group'>
                        <input type='email' id='newsletter-email' name='email'
                               placeholder='Your email address' required class='newsletter-input'>
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

        <!-- Social Links -->
        <div class='footer-social'>
            <h4 class='footer-social-title'>Follow the Monkey</h4>
            <div class='footer-social-icons'>
                <a href='https://www.instagram.com/blind_monkey_antwerpen/' class='footer-social-link' aria-label='Instagram' target='_blank'>
                    <i class='fab fa-instagram'></i>
                </a>
                <a href='https://open.spotify.com/artist/6o0z83KmtPJtdpUFwJKhEj' class='footer-social-link' aria-label='Spotify' target='_blank'>
                    <i class='fab fa-spotify'></i>
                </a>
                <a href='https://www.tiktok.com/@blind.monkey7' class='footer-social-link' aria-label='TikTok' target='_blank'>
                    <i class='fab fa-tiktok'></i>
                </a>
                <a href='https://www.youtube.com/@blindmonkey' class='footer-social-link' aria-label='YouTube' target='_blank'>
                    <i class='fab fa-youtube'></i>
                </a>
                <a href='https://vi.be/platform/bara' class='footer-social-link' aria-label='VI.BE' target='_blank'>
                    <i class='fas fa-music'></i>
                </a>
            </div>
        </div>

        <!-- Quick Links -->
        <div class='footer-links'>
            <h4 class='footer-links-title'>Quick Links</h4>
            <ul class='footer-links-list'>
                <li><a href='#about'  class='footer-link'>About</a></li>
                <li><a href='#music'  class='footer-link'>Music</a></li>
                <li><a href='#events' class='footer-link'>Shows</a></li>
                <li><a href='#videos' class='footer-link'>Videos</a></li>
                <li><a href='#press'  class='footer-link'>Press</a></li>
                <li><a href='mailto:blindmonkey.be@gmail.com' class='footer-link'>Booking</a></li>
            </ul>
        </div>

    </div>

    <!-- Bottom Bar -->
    <div class='footer-bottom'>
        <div class='footer-bottom-content'>
            <p class='footer-bottom-text'>
                Made with <i class='fas fa-heart footer-heart'></i> for rock music lovers
            </p>
            <div class='footer-bottom-links'>
                <a href='mailto:blindmonkey.be@gmail.com' class='footer-bottom-link'>blindmonkey.be@gmail.com</a>
                <span class='footer-divider'>&bull;</span>
                <span class='footer-bottom-link'>Antwerpen, België</span>
            </div>
        </div>
    </div>
</footer>";
?>
