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
    <title>HRMAX | NEXEN Innovation Technologies Inc.</title>
    <meta name="description" content="">
    <meta name="keywords" content="">


    <!-- Favicons -->
    <link href="assets/img/logo.jpg" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

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

    <main class="position-relative" style="padding-bottom: 12rem;">
        <div class="hrmax-bottom-glow w-100"></div>
        <section id="hrmax-hero-section" class="py-5 d-flex align-items-center" style="min-height: 100vh;">
            <div class="max-w-wrapper mx-auto w-100">
                <div class="row row-cols-1 row-cols-md-2">
                    <div class="col pt-5 pt-sm-0 d-flex flex-column justify-content-center">
                        <h1 style="font-size: 3.2rem;" class="text-white-gradient inter fw-bold">Automate Your Workforce <br /> Management with Ease</h1>
                        <p style="max-width: 600px;" class="text-slate fs-6 mb-0 pt-2"><?= htmlspecialchars(getContent("hrmax", "title-description")) ?></p>
                    </div>
                    <div class="col d-flex flex-column justify-content-center position-relative">
                        <div class="hrmax-hero-circle-1"></div>
                        <div class="hrmax-hero-circle-2"></div>
                        <img src="assets/png/hrmax-hero.png" style="aspect-ratio: 5/4; object-fit:contain; z-index:10" alt="" class="w-100">
                    </div>
                </div>
            </div>
        </section>

        <!-- HRMAX CARDS  -->
        <section id="hrmax-cards" class="py-5">
            <div class="d-center flex-column mb-5 position-relative">
                <img src="assets/png/png-3.png" style="top: -5rem;z-index:-10; left:50%; transform:translateX(-50%); max-width:700px" class="position-absolute w-100" alt="">
                <h1 class="urbanist fw-semibold text-center mb-3">Transforming HR <span class="text-red-dark">Management</span></h1>

                <p class="text-center text-muted-light mb-0 mx-auto mb-5" style="max-width:500px;">Innovative solutions to manage your workforce
                    with ease, accuracy, and efficiency.
                </p>
            </div>

            <div class="row row-cols-1 row-cols-md-2 px-1 max-w-wrapper mx-auto">
                <div class="col">
                    <div class="hrmax-banner-card p-5 d-center flex-column">
                        <div class="mt-auto d-center flex-column pb-5">
                            <h4 class="urbanist fw-semibold mb-3">
                                <?= htmlspecialchars(getContent("hrmax", "card-title-1")) ?>
                            </h4>
                            <p class="text-center fs-6 text-muted-light">
                                <?= htmlspecialchars(getContent("hrmax", "card-desc-1")) ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="hrmax-banner-card p-5 d-center flex-column">
                        <div class="mt-auto d-center flex-column pb-5">
                            <h4 class="urbanist fw-semibold mb-3">
                                <?= htmlspecialchars(getContent("hrmax", "card-title-2")) ?>
                            </h4>
                            <p class="text-center fs-6 text-muted-light">
                                <?= htmlspecialchars(getContent("hrmax", "card-desc-2")) ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="hrmax-banner-card p-5 d-center flex-column">
                        <div class="mt-auto d-center flex-column pb-5">
                            <h4 class="urbanist fw-semibold mb-3">
                                <?= htmlspecialchars(getContent("hrmax", "card-title-3")) ?>
                            </h4>
                            <p class="text-center fs-6 text-muted-light">
                                <?= htmlspecialchars(getContent("hrmax", "card-desc-3")) ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="hrmax-banner-card p-5 d-center flex-column">
                        <div class="mt-auto d-center flex-column pb-5">
                            <h4 class="urbanist fw-semibold mb-3">
                                <?= htmlspecialchars(getContent("hrmax", "card-title-4")) ?>
                            </h4>
                            <p class="text-center fs-6 text-muted-light">
                                <?= htmlspecialchars(getContent("hrmax", "card-desc-4")) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php
        $features = [
            [
                ["title" => "Hiring Management System", "icon" => "bi-person-plus"],
                ["title" => "Employee Information System", "icon" => "bi-people"],
                ["title" => "Timekeeping System", "icon" => "bi-clock-history"],
                ["title" => "Performance Appraisal System", "icon" => "bi-graph-up-arrow"]
            ],
            [
                ["title" => "Payroll System", "icon" => "bi-cash-stack"],
                ["title" => "Online Employee Portal System", "icon" => "bi-globe"],
                ["title" => "Mobile Notification System", "icon" => "bi-bell"],
                ["title" => "Disciplinary Management System", "icon" => "bi-exclamation-triangle"]
            ],
            [
                ["title" => "Medical Tracking System", "icon" => "bi-heart-pulse"],
                ["title" => "Vehicle Monitoring System", "icon" => "bi-truck"],
                ["title" => "Asset Management System", "icon" => "bi-box-seam"],
                ["title" => "Security Guard Logs System", "icon" => "bi-shield-check"]
            ]
        ];
        ?>

        <!-- HRMAX KEY FEATURES  -->
        <section id="key-features py-5">
            <div class="mx-auto max-w-wrapper mb-5">
                <div class="border-color-border-light border p-4 p-md-5">
                    <h1 class="urbanist fw-semibold mb-3 pt-5">HRMAX Key Features</h1>
                    <p class="text-muted-foreground mb-0">
                        Everything you need to manage your workforce efficiently in one seamless platform.
                    </p>
                </div>

                <?php foreach ($features as $row): ?>

                    <div class="border-color-border-light border py-3"></div>

                    <div style="padding-inline: 12px;" class="row row-cols-2 row-cols-md-4">
                        <?php foreach ($row as $index => $feature): ?>
                            <div class="col px-0">
                                <div class="border-color-border-light h-100 border p-4 p-md-5">

                                    <!-- ✅ Bootstrap Icon -->
                                    <div class="hrmax-feature-icon-wrapper border-border d-center">
                                        <i class="bi <?= htmlspecialchars($feature['icon']) ?>" style="font-size: 28px;"></i>
                                    </div>

                                    <h6 class="urbanist text-muted-foreground mb-0">
                                        <?= htmlspecialchars($feature['title']) ?>
                                    </h6>

                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                <?php endforeach; ?>

            </div>
        </section>

        <!-- HRMAX BENEFITS  -->
        <section id="hrmax-benefits" class="py-5">
            <div class="d-center flex-column">
                <h1 class="urbanist fw-semibold text-center mb-3">Discover the Benefits of HRMAX</h1>

                <p class="text-center text-muted-foreground mb-0 mx-auto mb-5" style="max-width:600px;">See how HRMAX makes a difference for you.</p>
            </div>
            <div class="row mx-auto px-2 position-relative" style="max-width: 900px; isolation:isolate">

                <div class="position-absolute hrmax-benefits-glow"></div>
                <div class="col col-12 col-sm-6 px-2 mb-2 mb-sm-3">
                    <div class="bg-glass h-100 border-border p-4 rounded-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="background-color: red;" class="small-box"></div>
                            <h5 class="urbanist mb-0">
                                HR Process Standardization
                            </h5>
                        </div>
                        <p class="text-muted-light mb-0">Comprehensive standardization tool for Human Resource Department operational processes.</p>
                    </div>
                </div>
                <div class="col col-12 col-sm-6 px-2 mb-2 mb-sm-3">
                    <div class="bg-glass h-100 border-border p-4 rounded-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="background-color: red;" class="small-box"></div>
                            <h5 class="urbanist mb-0">
                                Hybrid System Design
                            </h5>
                        </div>
                        <p class="text-muted-light mb-0">Combination of desktop design and browser-based design.</p>
                    </div>
                </div>
                <div class="col col-12 col-sm-6 px-2 mb-2 mb-sm-3">
                    <div class="bg-glass h-100 border-border p-4 rounded-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="background-color: white;" class="small-box"></div>
                            <h5 class="urbanist mb-0">
                                Cost-Effective & Reliable System
                            </h5>
                        </div>
                        <p class="text-muted-light mb-0">
                            ✓ Cost effective and dependable: <br>
                            It’s OPEX not CAPEX <br>
                            Zero cost for secure and reliable server open-source technology (PostgreSQL & CentOS Linux).
                        </p>
                    </div>
                </div>
                <div class="col col-12 col-sm-6 px-2 mb-2 mb-sm-3">
                    <div class="bg-glass h-100 border-border p-4 rounded-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="background-color: white;" class="small-box"></div>
                            <h5 class="urbanist mb-0">
                                Flexible Deployment
                            </h5>
                        </div>
                        <p class="text-muted-light mb-0">Unlimited Workstation installation</p>
                    </div>
                </div>
                <div class="col col-12 col-sm-6 px-2 mb-2 mb-sm-3">
                    <div class="bg-glass h-100 border-border p-4 rounded-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="background-color: blue;" class="small-box"></div>
                            <h5 class="urbanist mb-0">
                                HR Operations Management
                            </h5>
                        </div>
                        <p class="text-muted-light mb-0">Resource Department operational processes.</p>
                    </div>
                </div>
                <div class="col col-12 col-sm-6 px-2 mb-2 mb-sm-3">
                    <div class="bg-glass border-border p-4 rounded-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="background-color: blue;" class="small-box"></div>
                            <h5 class="urbanist mb-0">
                                Full Support Services
                            </h5>
                        </div>
                        <p class="text-muted-light mb-0">Unlimited off-site support in every phase.</p>
                    </div>
                </div>
                <div class="col col-12 px-2 mb-2 mb-sm-3">
                    <div class="d-flex justify-content-center">
                        <div style="max-width: 400px;" class="w-100 bg-glass border-border p-4 rounded-4">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div style="background-color: gray;" class="small-box"></div>
                                <h5 class="urbanist mb-0">
                                    Scalable User Access
                                </h5>
                            </div>
                            <p class="text-muted-light mb-0">Unlimited users.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php
        $plans = [
            [
                "title" => "Business Plan",
                "description" => "All-in-One Workforce Monitoring and Management System",
                "sections" => [
                    [
                        "label" => "What’s included",
                        "items" => [
                            "Employee Information System",
                            "Timekeeping System",
                            "Payroll System",
                            "Mobile Notification System",
                            "Online Employee Portal System",
                            "Payroll System"
                        ]
                    ],
                    [
                        "label" => "Add- ons",
                        "items" => [
                            "Hiring Management System",
                            "Disciplinary Management System",
                            "Performance Appraisal System",
                            "Medical Tracking System",
                            "Vehicle Monitoring System",
                            "Asset Management System",
                            "Security Guard Logs System"
                        ]
                    ]
                ]
            ],
            [
                "title" => "Enterprise-Grade Plan",
                "description" => "Enterprise HR and Operations Transformation Plan",
                "sections" => [
                    [
                        "label" => "Phase 1",
                        "items" => [
                            "Hiring Management System",
                            "Employee Information System",
                            "Timekeeping System",
                            "Payroll System",
                            "Mobile Notification System",
                            "Online Employee Portal System"
                        ]
                    ],
                    [
                        "label" => "Phase 2",
                        "items" => [
                            "Disciplinary Management System",
                            "Performance Appraisal System",
                            "Asset Management System"
                        ]
                    ],
                    [
                        "label" => "Phase 3",
                        "items" => [
                            "Medical Tracking System",
                            "Vehicle Monitoring System",
                            "Security Guard Logs System"
                        ]
                    ]
                ]
            ]
        ];
        ?>

        <div class="mx-auto max-w-wrapper mt-5">
            <div class="d-center flex-column">
                <h1 class="urbanist fw-semibold text-center mb-3">Choose the Plan That’s Right for You</h1>

                <p class="text-center text-muted-foreground mb-0 mx-auto mb-5" style="max-width:600px;">Access powerful features designed to streamline your operations. With Nexen, experience smarter systems, improved workflows, and scalable solutions.</p>
            </div>
        </div>
        <div style="max-width: 900px;" class="mx-auto py-5 row row-cols-1 row-cols-md-2 no-gutter px-2">

            <?php foreach ($plans as $plan): ?>
                <div class="col px-2 mb-3">
                    <div class="bg-glass h-100 p-3 p-md-5 d-flex flex-column border-thin rounded-4">

                        <h5 class="mb-3 urbanist"><?= htmlspecialchars($plan['title']) ?></h5>

                        <p class="text-muted-foreground fw-light mb-0 pb-4">
                            <?= htmlspecialchars($plan['description']) ?>
                        </p>

                        <div class="gradient-separator mb-4"></div>

                        <?php foreach ($plan['sections'] as $section): ?>

                            <p class="fs-7 fw-light text-muted-light">
                                <?= htmlspecialchars($section['label']) ?>
                            </p>

                            <div class="d-flex flex-column gap-3 mb-3">
                                <?php foreach ($section['items'] as $item): ?>
                                    <div class="d-flex gap-3">
                                        <i class="bi bi-check-circle-fill text-red-dark"></i>
                                        <p class="mb-0 text-muted-foreground fw-light">
                                            <?= htmlspecialchars($item) ?>
                                        </p>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                        <?php endforeach; ?>

                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </main>

    <!-- FOOTER  -->
    <footer class="bg-background">
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
            // header.style.background = 'rgba(10, 10, 10, 0.9)';
            // header.style.borderBottom = '1px solid rgba(240,240,240,0.1)';
            // header.style.backdropFilter = "blur(10px)";
            header.classList.add("scrolled")
        } else {
            header.classList.remove("scrolled")
            // header.style.background = 'transparent';
            // header.style.backdropFilter = "blur(0)";
            // header.style.borderBottom = '1px solid transparent';
        }
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

    // const lenis = new Lenis({
    //     autoRaf: true,
    //     autoToggle: true,
    //     anchors: true,
    //     allowNestedScroll: true,
    //     naiveDimensions: true,
    //     stopInertiaOnNavigate: true
    // });
</script>

</html>