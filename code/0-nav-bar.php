<?php
$_nav= "
    <header>
        <!-- Navegación con Bootstrap -->
        <nav id='navbar' class='navbar navbar-expand-lg navbar-dark bg-dark'>
            <div class='container'>
                <!-- Logo/Brand para móviles - Solo visible en pantallas pequeñas -->
                <a class='navbar-brand mx-auto d-block d-lg-none fw-bold' href='#home' id='mobile-brand'>
                    <img src='../img/5am_Logo_01-01.png' alt='The 5 AM Logo' class='navbar-logo' id='navbar-logo'>
                    <span class='navbar-text-fallback'>The 5 AM</span>
                </a>
                
                <!-- Botón hamburguesa -->
                <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarNav' aria-controls='navbarNav' aria-expanded='false' aria-label='Toggle navigation'>
                    <span class='navbar-toggler-icon'></span>
                </button>
                
                <!-- Enlaces de navegación -->
                <div class='collapse navbar-collapse justify-content-center' id='navbarNav'>
                    <ul class='navbar-nav w-100 d-flex flex-column flex-lg-row align-items-center'>
                        <li class='nav-item'><a class='nav-link' href='#home' id='nav-home'>Home</a></li>
                        <li class='nav-item'><a class='nav-link' href='#about' id='nav-about'>About</a></li>
                        <li class='nav-item'><a class='nav-link' href='#music' id='nav-music'>Music</a></li>
                        <li class='nav-item'><a class='nav-link' href='#press' id='nav-press'>Press</a></li>
                        <li class='nav-item'><a class='nav-link' href='#events' id='nav-events'>Events</a></li>
                        <li class='nav-item'><a class='nav-link' href='#videos' id='nav-videos'>Videos</a></li>
                        <li class='nav-item'><a class='nav-link' href='#photos-1' id='nav-photos'>Photos</a></li>
                        <li class='nav-item'><a class='nav-link'
                                href='https://the-5-am-official-merchandise.myspreadshop.net/' id='nav-webshop'
                                target='_blank' rel='noopener noreferrer'>Webshop</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>";
?>