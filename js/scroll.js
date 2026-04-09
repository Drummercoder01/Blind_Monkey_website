document.addEventListener("DOMContentLoaded", function () {

    const navbar   = document.querySelector(".bm-navbar");
    const hamburger = document.getElementById("bm-hamburger");
    const mobileMenu = document.getElementById("bm-mobile-menu");
    const navLinks  = document.querySelectorAll(".bm-link");
    const sections  = document.querySelectorAll("section[id]");

    // ── 1. SCROLLED CLASS ─────────────────────────────────────────────
    function onScroll() {
        if (!navbar) return;
        if (window.scrollY > 50) {
            navbar.classList.add("scrolled");
        } else {
            navbar.classList.remove("scrolled");
        }
    }
    window.addEventListener("scroll", onScroll, { passive: true });
    onScroll(); // run once on load

    // ── 2. ACTIVE LINK via IntersectionObserver ───────────────────────
    const NAVBAR_HEIGHT = 72;

    function setActive(id) {
        navLinks.forEach(link => {
            const href = link.getAttribute("href");
            if (href === "#" + id) {
                link.classList.add("active");
            } else {
                link.classList.remove("active");
            }
        });
    }

    if ("IntersectionObserver" in window && sections.length) {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        setActive(entry.target.getAttribute("id"));
                    }
                });
            },
            {
                rootMargin: `-${NAVBAR_HEIGHT}px 0px -55% 0px`,
                threshold: 0
            }
        );
        sections.forEach(section => observer.observe(section));
    }

    // ── 3. SMOOTH SCROLL ON LINK CLICK ───────────────────────────────
    navLinks.forEach(link => {
        link.addEventListener("click", function (e) {
            const href = this.getAttribute("href");
            if (!href || !href.startsWith("#")) return; // external link — let browser handle

            e.preventDefault();

            // Close mobile menu if open
            if (hamburger && mobileMenu) {
                hamburger.classList.remove("open");
                hamburger.setAttribute("aria-expanded", "false");
                mobileMenu.classList.remove("open");
            }

            const target = document.querySelector(href);
            if (target) {
                const top = target.getBoundingClientRect().top + window.scrollY - NAVBAR_HEIGHT;
                window.scrollTo({ top: Math.max(0, top), behavior: "smooth" });
            }
        });
    });

    // ── 4. HAMBURGER TOGGLE ───────────────────────────────────────────
    if (hamburger && mobileMenu) {
        hamburger.addEventListener("click", function () {
            const isOpen = this.classList.toggle("open");
            this.setAttribute("aria-expanded", isOpen);
            mobileMenu.classList.toggle("open", isOpen);
        });

        // Close menu when clicking outside
        document.addEventListener("click", function (e) {
            if (!navbar.contains(e.target)) {
                hamburger.classList.remove("open");
                hamburger.setAttribute("aria-expanded", "false");
                mobileMenu.classList.remove("open");
            }
        });
    }

    console.log("✅ Blind Monkey navbar JS inicializado");
});
