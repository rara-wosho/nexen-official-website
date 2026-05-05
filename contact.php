<?php
require_once 'db_connect.php';
require_once 'config.php';

// Function to fetch content from the database
function getContent($section_name, $content_key)
{
    global $connection;

    try {
        $query = "SELECT content FROM content WHERE section_name = :section_name AND content_key = :content_key";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':section_name', $section_name);
        $stmt->bindParam(':content_key', $content_key);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result['content'];
        } else {
            return "No content";
        }
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }

    return '';
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>NEXEN Innovation Technologies Inc.</title>
    <meta name="description" content="">
    <meta name="keywords" content="">


    <!-- Favicons -->
    <link href="assets/img/logo.jpg" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">

    <!-- Bootstrap + Icons -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

    <!-- Lenis smooh  scrolling  -->
    <link rel="stylesheet" href="https://unpkg.com/lenis@1.3.21/dist/lenis.css">
    <script src="https://unpkg.com/lenis@1.3.21/dist/lenis.min.js"></script>

</head>

<body>
    <!-- ══ HEADER ══════════════════════════════════════════ -->
    <header id="header" class="">
        <div class="max-w-wrapper  w-100 d-flex align-items-center mx-auto">
            <a href="#" class="logo ">
                <img src="<?= htmlspecialchars(getContent("official-logo", "nexen-logo")) ?>" class="nav-logo" alt="nexen-logo">
            </a>

            <!-- Desktop Dropdown Nav -->
            <div class="links-container">
                <div class="dropdown"><button><a href="<?= url("/") ?>">Home</a></button></div>
                <div class="dropdown">
                    <button>Solutions</button>
                    <div class="dropdown-content">
                        <a href="#" style="--i:1">Software Subscriptions</a>
                        <a href="#" style="--i:2">Payroll Outsourcing</a>
                        <a href="#" style="--i:3">Firewall/VPN Integration</a>
                        <a href="#" style="--i:4">CCTV Setup</a>
                    </div>
                </div>
                <div class="dropdown">
                    <button>Products</button>
                    <div class="dropdown-content">
                        <a href="hrmax" style="--i:1">HRMAX</a>
                        <a href="#" style="--i:2">PROFILE MANAGER</a>
                        <a href="#" style="--i:3">MEDIXPRO</a>
                        <a href="#" style="--i:4">LINEONE</a>
                        <a href="#" style="--i:5">CITIZENPULSE</a>
                        <a href="#" style="--i:6">TRAILMASTER</a>
                        <a href="#" style="--i:7">iBOSS</a>
                        <a href="#" style="--i:8">iNXUREPRO</a>
                        <a href="#" style="--i:9">QUICKOPS</a>
                        <a href="#" style="--i:10">SMARTPOINT</a>
                        <a href="#" style="--i:11">SPEEDCOUNT</a>
                    </div>
                </div>
                <div class="dropdown">
                    <button>Insights</button>
                    <div class="dropdown-content">
                        <a href="#" style="--i:1">Implementation</a>
                        <a href="#" style="--i:2">Activities</a>
                        <a href="#" style="--i:3">Partners</a>
                    </div>
                </div>
                <div class="dropdown">
                    <button onclick="window.location.href='about'"><span>About</span></button>
                </div>

                <div class="d-flex align-items-center gap-2 ms-auto">
                    <div class="talk-to-us">
                        <button onclick="window.location.href='contact'">Contact Us</button>
                    </div>
                    <div class="dropdown">
                        <button>Gateway</button>
                        <div class="dropdown-content">
                            <a href="#" style="--i:1">Get Support</a>
                            <a href="#" style="--i:2">Employee Portal</a>
                            <a href="login" style="--i:3">Admin Portal</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Hamburger Toggle -->
            <button class="mobile-nav-toggle text-secondary-foreground" id="mobileNavToggle" aria-label="Toggle navigation">
                <i class="bi bi-list"></i>
            </button>

            <!-- Mobile Nav Overlay -->
            <div class="mobile-nav-overlay" id="mobileNavOverlay"></div>

            <!-- Mobile Nav Panel -->
            <nav class="mobile-nav-panel" id="mobileNavPanel">
                <div class="mobile-nav-header">
                    <a href="#">
                        <img src="<?= htmlspecialchars(getContent("official-logo", "nexen-logo")) ?>" class="nav-logo" alt="nexen-logo">
                    </a>
                    <button class="mobile-nav-close" id="mobileNavClose" aria-label="Close navigation">
                        <!-- <i class="bi bi-x-lg"></i>  -->
                    </button>
                </div>

                <div class="mobile-nav-links">
                    <a href="<?= url("/") ?>" class="mobile-nav-link">Home</a>

                    <div class="mobile-nav-accordion">
                        <button class="mobile-nav-accordion-btn">
                            Solutions
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div class="mobile-nav-accordion-content">
                            <a href="#">Software Subscriptions</a>
                            <a href="#">Payroll Outsourcing</a>
                            <a href="#">Firewall/VPN Integration</a>
                            <a href="#">CCTV Setup</a>
                        </div>
                    </div>

                    <div class="mobile-nav-accordion">
                        <button class="mobile-nav-accordion-btn">
                            Products
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div class="mobile-nav-accordion-content">
                            <a href="hrmax">HRMAX</a>
                            <a href="#">PROFILE MANAGER</a>
                            <a href="#">MEDIXPRO</a>
                            <a href="#">LINEONE</a>
                            <a href="#">CITIZENPULSE</a>
                            <a href="#">TRAILMASTER</a>
                            <a href="#">iBOSS</a>
                            <a href="#">iNXUREPRO</a>
                            <a href="#">QUICKOPS</a>
                            <a href="#">SMARTPOINT</a>
                            <a href="#">SPEEDCOUNT</a>
                        </div>
                    </div>

                    <div class="mobile-nav-accordion">
                        <button class="mobile-nav-accordion-btn">
                            Insights
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div class="mobile-nav-accordion-content">
                            <a href="#">Implementation</a>
                            <a href="#">Activities</a>
                            <a href="#">Partners</a>
                        </div>
                    </div>

                    <a href="about" class="mobile-nav-link">About</a>
                </div>

                <div class="mobile-nav-footer">
                    <a href="contact" class="mobile-nav-cta">Contact Us</a>
                    <a href="login" class="mobile-nav-login">
                        <i class="bi bi-person-circle"></i>
                        Admin Login
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <section class="get-in-touch">
        <div class="mx-auto max-w-wrapper mb-5">
            <div class="get-in-touch-upper-wrapper rounded-4 p-3 p-md-5">
                <div class="ascii-wrapper mb-2">
                    <p class="mb-0 text-muted-light">✦</p>
                    <p class="mb-0 text-muted-light">✦</p>
                    <p class="mb-0 text-muted-light">✦</p>
                </div>
                <h1 class="urbanist">Get in Touch with <span class="text-red-gradient">Nexen</span></h1>
                <p class="text-muted-foreground fs-7 mt-4">Welcome to Nexen’s Contact Us page. We’re here to assist you with any inquiries, requests, or feedback. Whether you need our services, want to explore opportunities, or simply connect, we’re just a message away. Let’s start a conversation.</p>

                <div class="mt-4 row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-4">
                    <div class="col px-2 h-100 mb-3">
                        <div class="get-in-touch-card p-3 bg-card-down rounded-3 position-relative">
                            <div class="d-flex align-items-center gap-3">
                                <i class="bi bi-envelope-fill"></i>
                                <p class="text-secondary-foreground mb-0">Email</p>
                            </div>
                            <a href="mailto:<?= htmlspecialchars(getContent("contact", "c-email"))  ?>" class="mt-2 mb-0 text-muted-foreground fw-light"><?= htmlspecialchars(getContent("contact", "c-email")) ?></a>
                        </div>
                    </div>
                    <div class="col px-2 h-100 mb-3">
                        <div class="get-in-touch-card p-3 bg-card-down rounded-3 position-relative">
                            <div class="d-flex align-items-center gap-3">
                                <i class="bi bi-telephone-fill"></i>
                                <p class="text-secondary-foreground mb-0">Phone</p>
                            </div>
                            <p class="mt-2 mb-0 text-muted-foreground fw-light">
                                <?= htmlspecialchars(getContent("contact", "c-phone")) ?>
                            </p>
                        </div>
                    </div>
                    <div class="col px-2 h-100 mb-3">
                        <a target="_blank" href="<?= htmlspecialchars(getContent("contact", "link-linkedin")) ?>" class="get-in-touch-card p-3 bg-card-down rounded-3 position-relative">
                            <div class="d-flex align-items-center gap-3">
                                <i class="bi bi-linkedin"></i>
                                <p class="text-secondary-foreground mb-0">Linkedin</p>
                            </div>
                            <p class="mt-2 mb-0 text-muted-foreground fw-light">
                                <?= htmlspecialchars(getContent("contact", "c-linkedin")) ?>
                            </p>
                        </a>
                    </div>
                    <div class="col px-2 h-100 mb-3">
                        <a target="_blank" href="<?= htmlspecialchars(getContent("contact", "link-facebook")) ?>" class="get-in-touch-card p-3 bg-card-down rounded-3 position-relative">
                            <div class="d-flex mb-2 align-items-center gap-3">
                                <i class="bi bi-facebook"></i>
                                <p class="text-secondary-foreground mb-0">Facebook</p>
                            </div>
                            <p class="mb-0 text-muted-foreground fw-light">
                                <?= htmlspecialchars(getContent("contact", "c-facebook")) ?>
                            </p>
                        </a>
                    </div>
                </div>
            </div>
            <div class="p-3 p-md-5 bg-card-dark rounded-4 mt-3 urbanist">
                <p class="fs-7 text-muted-foreground"><i class="bi bi-building me-2"></i> Central Office</p>
                <h5>
                    <?= htmlspecialchars(getContent("contact", "description")) ?>
                </h5>

                <p class="mt-1 text-muted-foreground fs-7">Our main office serves as the core of Nexen. Located in a strategic area of the city, it is where our team works together to drive innovation, deliver quality solutions, and support our clients with excellence.</p>

                <div class="embed-map-responsive p-5 mt-4 w-full rounded-3 overflow-hidden">
                    <div class="embed-map-container"><iframe class="embed-map-frame" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=600&height=400&hl=en&q=nexen%20oroquita&t=&z=14&ie=UTF8&iwloc=B&output=embed"></iframe><a href="https://idolsofash.app" style="font-size:2px!important;color:gray!important;position:absolute;bottom:0;left:0;z-index:1;max-height:1px;overflow:hidden">Idols of Ash</a></div>
                    <style>
                        .embed-map-responsive {
                            position: relative;
                            text-align: right;
                            width: 100%;
                            height: 500px;
                            padding-bottom: 66.66666666666666%;
                        }

                        .embed-map-container {
                            overflow: hidden;
                            background: none !important;
                            width: 100%;
                            height: 100%;
                            position: absolute;
                            top: 0;
                            left: 0;
                        }

                        .embed-map-frame {
                            width: 100% !important;
                            height: 100% !important;
                            position: absolute;
                            top: 0;
                            left: 0;
                        }
                    </style>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER  -->
    <footer>
        <div class="glow-4"></div>
        <div class="upper-footer max-w-wrapper mx-auto">
            <div class="row">
                <div class="col-12 mb-3 flex-grow-1 col-sm-4 d-flex flex-column align-items-center align-items-md-start">
                    <img src="<?= htmlspecialchars(getContent("official-logo", "nexen-logo")) ?>" style="width: 90px; aspect-ratio:5/5" class="mb-3" alt="">
                    <p class="fw-semibold text-center text-md-start">NEXEN INNOVATION TECHNOLOGIES</p>
                </div>
                <div class="col-12 mb-3 flex-grow-1 col-md-2 d-flex flex-column align-items-center align-items-md-start">
                    <p class="text-muted-foreground mb-3">Home</p>
                    <p class="text-secondary-foreground">Features</p>
                </div>
                <div class="col-12 mb-3 flex-grow-1 col-md-2 d-flex flex-column align-items-center align-items-md-start">
                    <p class="text-muted-foreground mb-3">About Us</p>
                    <p class="text-secondary-foreground">Our Story</p>
                    <p class="text-secondary-foreground">Our Works</p>
                    <p class="text-secondary-foreground">How It Works</p>
                    <p class="text-secondary-foreground">Our Team</p>
                    <p class="text-secondary-foreground">Our Clients</p>
                </div>
                <div class="col-12 mb-3 flex-grow-1 col-md-2 d-flex flex-column align-items-center align-items-md-start">
                    <p class="text-muted-foreground mb-3">Services</p>
                    <p class="text-secondary-foreground">Strategic Marketing</p>
                    <p class="text-secondary-foreground">Closing Success</p>
                </div>
                <div class="col-12 mb-3 flex-grow-1 col-md-2 d-flex flex-column align-items-center align-items-md-start">
                    <p class="text-muted-foreground mb-3">Contact Us</p>
                    <p class="text-secondary-foreground">Contact Form</p>
                    <p class="text-secondary-foreground">Our Offices</p>
                </div>
            </div>
        </div>

        <!-- LOWER FOOTER SECTION  -->
        <div class="py-4 lower-footer">
            <div class="inner-lower-footer max-w-wrapper mx-auto d-flex align-items-center justify-content-center justify-content-sm-between" style="flex-wrap: wrap;">
                <small class="fs-7 text-muted-foreground">Copyright NEXEN All Rights Reserved</small>

                <div class="d-flex align-items-center gap-1 mt-3 mt-sm-0">
                    <a href="<?= htmlspecialchars(getContent("contact", "link-facebook")) ?>" target="_blank" rel="noopener noreferrer" class="footer-icon-wrapper">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="<?= htmlspecialchars(getContent("contact", "link-linkedin")) ?>" target="_blank" rel="noopener noreferrer" class="footer-icon-wrapper">
                        <i class="bi bi-linkedin"></i>
                    </a>
                    <a href="<?= htmlspecialchars(getContent("contact", "link-instagram")) ?>" target="_blank" rel="noopener noreferrer" class="footer-icon-wrapper">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="mailto:<?= htmlspecialchars(getContent("contact", "c-email")) ?>" target="_blank" rel="noopener noreferrer" class="footer-icon-wrapper">
                        <i class="bi bi-envelope-fill"></i>
                    </a>
                    <a href="<?= htmlspecialchars(getContent("contact", "link-youtube")) ?>" target="_blank" rel="noopener noreferrer" class="footer-icon-wrapper">
                        <i class="bi bi-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

</body>
<script>
    // Header shrink on scroll
    const header = document.getElementById('header');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 200) {
            header.classList.add("scrolled")
        } else {
            header.classList.remove("scrolled")
        }
    });

    const lenis = new Lenis({
        autoRaf: true,
        autoToggle: true,
        anchors: true,
        allowNestedScroll: true,
        naiveDimensions: true,
        stopInertiaOnNavigate: true
    });


    // ── Mobile Nav ──
    const mobileToggle = document.getElementById('mobileNavToggle');
    const mobilePanel = document.getElementById('mobileNavPanel');
    const mobileOverlay = document.getElementById('mobileNavOverlay');
    const mobileClose = document.getElementById('mobileNavClose');

    function openMobileNav() {
        mobilePanel.classList.add('open');
        mobileOverlay.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileNav() {
        mobilePanel.classList.remove('open');
        mobileOverlay.classList.remove('open');
        document.body.style.overflow = '';
    }

    mobileToggle.addEventListener('click', () => {
        mobilePanel.classList.contains('open') ? closeMobileNav() : openMobileNav();
    });

    mobileClose.addEventListener('click', closeMobileNav);
    mobileOverlay.addEventListener('click', closeMobileNav);

    // Accordion toggles
    document.querySelectorAll('.mobile-nav-accordion-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const accordion = btn.parentElement;
            const isOpen = accordion.classList.contains('open');

            // Close all accordions first
            document.querySelectorAll('.mobile-nav-accordion').forEach(a => a.classList.remove('open'));

            // Toggle current
            if (!isOpen) accordion.classList.add('open');
        });
    });

    // Close mobile nav when a link is clicked
    document.querySelectorAll('.mobile-nav-panel a').forEach(link => {
        link.addEventListener('click', () => {
            closeMobileNav();
        });
    });
</script>

</html>