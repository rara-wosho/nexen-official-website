<?php
require_once 'db_connect.php';

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
        <div class="max-w-wrapper w-100 d-flex align-items-center mx-auto">
            <a href="/nexen-official-website" class="logo ">
                <img src="assets/png/nexen-logo.png" class="nav-logo" alt="nexen-logo"> <!-- DO NOT TOUCH, TAGUON NAKO NI -->
            </a>

            <!-- Desktop Dropdown Nav -->
            <div class="links-container">
                <div class="dropdown"><button onclick="window.location.href='/nexen-official-website'">Home</button></div>
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
                        <a href="#" style="--i:1">HRMAX</a>
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
                    <button><a href="about">About</a></button>
                </div>
                <div class="talk-to-us ms-auto">
                    <button onclick="window.location.href='contact'">Contact Us</button>
                </div>
            </div>

            <!-- Fallback / Mobile Nav -->
            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="#hero">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#why-us">Why Us</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#projects">Projects</a></li>
                    <li><a href="#blog">Blog</a></li>
                    <li><a href="#contact">Contact Us</a></li>
                </ul>
                <a href="login.php" class="login bi-person-circle"> Login</a>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

        </div>
    </header>

    <main class="position-relative" style="padding-bottom: 12rem;">
        <div class="hrmax-bottom-glow w-100"></div>
        <section id="hrmax-hero-section" class="py-5 d-flex align-items-center" style="min-height: 100vh;">
            <div class="max-w-wrapper mx-auto w-100">
                <div class="row row-cols-1 row-cols-md-2">
                    <div class="col d-flex flex-column justify-content-center">
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
            <div class="d-center flex-column mb-5">
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
                <div class="border-color-border-light border p-5">
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

        <!-- HRMAX KEY FEATURES  -->
        <!-- <section id="key-features py-5">
            <div class="mx-auto max-w-wrapper mb-5">
                <div class="border-border p-5">
                    <h1 class="urbanist fw-semibold mb-3 pt-5">HRMAX Key Features</h1>

                    <p class="text-muted-foreground mb-0">Everything you need to manage your workforce efficiently in one seamless platform.</p>
                </div>

                <div style="border-inline: 1px solid red;" class="border-color-border py-3"></div>

                <div style="padding-inline: 12px;" class="row row-cols-2 row-cols-md-4">
                    <div class="col px-0">
                        <div class="border-color-border h-100 border-start border-top border-bottom p-4 p-md-5">
                            <div class="hrmax-feature-icon-wrapper border-border d-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-briefcase-business-icon lucide-briefcase-business">
                                    <path d="M12 12h.01" />
                                    <path d="M16 6V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2" />
                                    <path d="M22 13a18.15 18.15 0 0 1-20 0" />
                                    <rect width="20" height="14" x="2" y="6" rx="2" />
                                </svg>
                            </div>
                            <h6 class="urbanist text-muted-foreground mb-0">Hiring Management System</h6>
                        </div>
                    </div>
                    <div class="col px-0">
                        <div class="border-color-border h-100 border-start border-top border-bottom p-4 p-md-5">
                            <div class="hrmax-feature-icon-wrapper border-border d-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-round-icon lucide-users-round">
                                    <path d="M18 21a8 8 0 0 0-16 0" />
                                    <circle cx="10" cy="8" r="5" />
                                    <path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3" />
                                </svg>
                            </div>
                            <h6 class="urbanist text-muted-foreground mb-0">Employee Information System</h6>
                        </div>
                    </div>
                    <div class="col px-0">
                        <div class="border-color-border h-100 border-start border-top border-bottom p-4 p-md-5">
                            <div class="hrmax-feature-icon-wrapper border-border d-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock-icon lucide-clock">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 6v6l4 2" />
                                </svg>
                            </div>
                            <h6 class="urbanist text-muted-foreground mb-0">Timekeeping System</h6>
                        </div>
                    </div>
                    <div class="col px-0">
                        <div class="border-color-border h-100 border-start border-top border-bottom  border-end p-4 p-md-5">
                            <div class="hrmax-feature-icon-wrapper border-border d-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-gauge-icon lucide-gauge">
                                    <path d="m12 14 4-4" />
                                    <path d="M3.34 19a10 10 0 1 1 17.32 0" />
                                </svg>
                            </div>
                            <h6 class="urbanist text-muted-foreground mb-0">Performance Appraisal System</h6>
                        </div>
                    </div>
                </div>
                <div style="border-inline: 1px solid red;" class="border-color-border py-3"></div>

                <div style="padding-inline: 12px;" class="row row-cols-2 row-cols-md-4">
                    <div class="col px-0">
                        <div class="border-color-border h-100 border-start border-top border-bottom p-4 p-md-5">
                            <div class="hrmax-feature-icon-wrapper border-border d-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-briefcase-business-icon lucide-briefcase-business">
                                    <path d="M12 12h.01" />
                                    <path d="M16 6V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2" />
                                    <path d="M22 13a18.15 18.15 0 0 1-20 0" />
                                    <rect width="20" height="14" x="2" y="6" rx="2" />
                                </svg>
                            </div>
                            <h6 class="urbanist text-muted-foreground mb-0">Hiring Management System</h6>
                        </div>
                    </div>
                    <div class="col px-0">
                        <div class="border-color-border h-100 border-start border-top border-bottom p-4 p-md-5">
                            <div class="hrmax-feature-icon-wrapper border-border d-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-round-icon lucide-users-round">
                                    <path d="M18 21a8 8 0 0 0-16 0" />
                                    <circle cx="10" cy="8" r="5" />
                                    <path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3" />
                                </svg>
                            </div>
                            <h6 class="urbanist text-muted-foreground mb-0">Employee Information System</h6>
                        </div>
                    </div>
                    <div class="col px-0">
                        <div class="border-color-border h-100 border-start border-top border-bottom p-4 p-md-5">
                            <div class="hrmax-feature-icon-wrapper border-border d-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock-icon lucide-clock">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 6v6l4 2" />
                                </svg>
                            </div>
                            <h6 class="urbanist text-muted-foreground mb-0">Timekeeping System</h6>
                        </div>
                    </div>
                    <div class="col px-0">
                        <div class="border-color-border h-100 border-start border-top border-bottom  border-end p-4 p-md-5">
                            <div class="hrmax-feature-icon-wrapper border-border d-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-gauge-icon lucide-gauge">
                                    <path d="m12 14 4-4" />
                                    <path d="M3.34 19a10 10 0 1 1 17.32 0" />
                                </svg>
                            </div>
                            <h6 class="urbanist text-muted-foreground mb-0">Performance Appraisal System</h6>
                        </div>
                    </div>
                </div>
                <div style="border-inline: 1px solid red;" class="border-color-border py-3"></div>

                <div style="padding-inline: 12px;" class="row row-cols-2 row-cols-md-4">
                    <div class="col px-0">
                        <div class="border-color-border h-100 border-start border-top border-bottom p-4 p-md-5">
                            <div class="hrmax-feature-icon-wrapper border-border d-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-briefcase-business-icon lucide-briefcase-business">
                                    <path d="M12 12h.01" />
                                    <path d="M16 6V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2" />
                                    <path d="M22 13a18.15 18.15 0 0 1-20 0" />
                                    <rect width="20" height="14" x="2" y="6" rx="2" />
                                </svg>
                            </div>
                            <h6 class="urbanist text-muted-foreground mb-0">Hiring Management System</h6>
                        </div>
                    </div>
                    <div class="col px-0">
                        <div class="border-color-border h-100 border-start border-top border-bottom p-4 p-md-5">
                            <div class="hrmax-feature-icon-wrapper border-border d-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-round-icon lucide-users-round">
                                    <path d="M18 21a8 8 0 0 0-16 0" />
                                    <circle cx="10" cy="8" r="5" />
                                    <path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3" />
                                </svg>
                            </div>
                            <h6 class="urbanist text-muted-foreground mb-0">Employee Information System</h6>
                        </div>
                    </div>
                    <div class="col px-0">
                        <div class="border-color-border h-100 border-start border-top border-bottom p-4 p-md-5">
                            <div class="hrmax-feature-icon-wrapper border-border d-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock-icon lucide-clock">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 6v6l4 2" />
                                </svg>
                            </div>
                            <h6 class="urbanist text-muted-foreground mb-0">Timekeeping System</h6>
                        </div>
                    </div>
                    <div class="col px-0">
                        <div class="border-color-border h-100 border-start border-top border-bottom  border-end p-4 p-md-5">
                            <div class="hrmax-feature-icon-wrapper border-border d-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-gauge-icon lucide-gauge">
                                    <path d="m12 14 4-4" />
                                    <path d="M3.34 19a10 10 0 1 1 17.32 0" />
                                </svg>
                            </div>
                            <h6 class="urbanist text-muted-foreground mb-0">Performance Appraisal System</h6>
                        </div>
                    </div>
                </div>

            </div>
        </section> -->

        <!-- HRMAX BENEFITS  -->
        <section id="hrmax-benefits" class="py-5">
            <div class="d-center flex-column">
                <h1 class="urbanist fw-semibold text-center mb-3">Discover the Benefits of HRMAX</h1>

                <p class="text-center text-muted-foreground mb-0 mx-auto mb-5" style="max-width:600px;">See how HRMAX makes a difference for you.</p>
            </div>
            <div class="row mx-auto px-1 position-relative" style="max-width: 900px; isolation:isolate">

                <div class="position-absolute hrmax-benefits-glow"></div>
                <div class="col col-12 col-sm-6 px-2 mb-3">
                    <div class="bg-glass border-border p-4 rounded-4">
                        <h5 class="urbanist mb-3">HR Process Standardization</h5>
                        <p class="text-muted-light mb-0">✓ Comprehensive standardization tool for Human Resource Department operational processes.</p>
                    </div>
                </div>
                <div class="col col-12 col-sm-6 px-2 mb-3">
                    <div class="bg-glass border-border p-4 rounded-4">
                        <h5 class="urbanist mb-3">Hybrid System Design</h5>
                        <p class="text-muted-light mb-0">✓ Comprehensive standardization tool for Human Resource Department operational processes.</p>
                    </div>
                </div>
                <div class="col col-12 col-sm-6 px-2 mb-3">
                    <div class="bg-glass border-border p-4 rounded-4">
                        <h5 class="urbanist mb-3">HR Process Standardization</h5>
                        <p class="text-muted-light mb-0">✓ Comprehensive standardization tool for Human Resource Department operational processes.</p>
                    </div>
                </div>
                <div class="col col-12 col-sm-6 px-2 mb-3">
                    <div class="bg-glass border-border p-4 rounded-4">
                        <h5 class="urbanist mb-3">HR Process Standardization</h5>
                        <p class="text-muted-light mb-0">✓ Comprehensive standardization tool for Human Resource Department operational processes.</p>
                    </div>
                </div>
                <div class="col col-12 col-sm-6 px-2 mb-3">
                    <div class="bg-glass border-border p-4 rounded-4">
                        <h5 class="urbanist mb-3">HR Process Standardization</h5>
                        <p class="text-muted-light mb-0">✓ Comprehensive standardization tool for Human Resource Department operational processes.</p>
                    </div>
                </div>
                <div class="col col-12 col-sm-6 px-2 mb-3">
                    <div class="bg-glass border-border p-4 rounded-4">
                        <h5 class="urbanist mb-3">HR Process Standardization</h5>
                        <p class="text-muted-light mb-0">✓ Comprehensive standardization tool for Human Resource Department operational processes.</p>
                    </div>
                </div>
                <div class="col col-12 px-2 mb-3">
                    <div class="bg-glass border-border p-4 rounded-4"></div>
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

        <div style="max-width: 900px;" class="mx-auto py-5 row row-cols-1 row-cols-md-2 no-gutter">

            <?php foreach ($plans as $plan): ?>
                <div class="col mb-3">
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
        <div class="upper-footer max-w-wrapper mx-auto">
            <div class="row">
                <div class="col-12 col-sm-4">
                    <img src="assets/png/nexen-logo.png" class="w-25 mb-3" alt="">
                    <p class="fw-semibold">NEXEN INNOVATION TECHNOLOGIES</p>
                </div>
                <div class="col-12 col-md-2">
                    <p class="text-muted-foreground mb-3">Home</p>
                    <p class="text-secondary-foreground">Features</p>
                </div>
                <div class="col-12 col-md-2">
                    <p class="text-muted-foreground mb-3">About Us</p>
                    <p class="text-secondary-foreground">Our Story</p>
                    <p class="text-secondary-foreground">Our Works</p>
                    <p class="text-secondary-foreground">How It Works</p>
                    <p class="text-secondary-foreground">Our Team</p>
                    <p class="text-secondary-foreground">Our Clients</p>
                </div>
                <div class="col-12 col-md-2">
                    <p class="text-muted-foreground mb-3">Services</p>
                    <p class="text-secondary-foreground">Strategic Marketing</p>
                    <p class="text-secondary-foreground">Closing Success</p>
                </div>
                <div class="col-12 col-md-2">
                    <p class="text-muted-foreground mb-3">Contact Us</p>
                    <p class="text-secondary-foreground">Contact Form</p>
                    <p class="text-secondary-foreground">Our Offices</p>
                </div>
            </div>
        </div>

        <!-- LOWER FOOTER SECTION  -->
        <div class="py-4 lower-footer">
            <div class="inner-lower-footer max-w-wrapper mx-auto d-flex align-items-center justify-content-between">
                <small class="fs-7 text-muted-foreground">Copyright NEXEN All Rights Reserved</small>

                <div class="d-flex align-items-center gap-1">
                    <div class="footer-icon-wrapper">
                        <i class="bi bi-facebook"></i>
                    </div>
                    <div class="footer-icon-wrapper">
                        <i class="bi bi-linkedin"></i>
                    </div>

                    <div class="footer-icon-wrapper">
                        <i class="bi bi-instagram"></i>
                    </div>
                    <div class="footer-icon-wrapper">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <div class="footer-icon-wrapper">
                        <i class="bi bi-youtube"></i>
                    </div>
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