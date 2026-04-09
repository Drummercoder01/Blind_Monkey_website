// Logo load handler for bm-navbar brand
document.addEventListener('DOMContentLoaded', function () {
    const logo  = document.getElementById('bm-brand-logo');
    const brand = document.getElementById('bm-brand');

    if (!logo || !brand) return;

    logo.addEventListener('error', function () {
        console.warn('⚠️ Brand logo failed to load');
        brand.classList.add('logo-failed');
    });

    logo.addEventListener('load', function () {
        brand.classList.remove('logo-failed');
    });

    // Handle cached images
    if (logo.complete && logo.naturalWidth === 0) {
        brand.classList.add('logo-failed');
    }

    console.log('🎨 Navbar brand logo handler ready');
});
