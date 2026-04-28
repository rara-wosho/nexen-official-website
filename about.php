<?php
require_once 'db_connect.php';
require_once 'config.php';

// Get bulk images
function getImages($section_name, $content_key_initial)
{
    global $connection;

    try {
        $keyword = $content_key_initial . '%';

        $query = "SELECT content 
              FROM content 
              WHERE section_name = :section_name 
              AND content_key ILIKE :keyword";

        $stmt = $connection->prepare($query);
        $stmt->execute([
            ':section_name' => $section_name,
            ':keyword' => $keyword
        ]);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($results) {
            // Extract only the content column into array
            return array_column($results, 'content');
        }

        return []; // better than "No content"

    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}

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

function getTeamMembers()
{
    global $connection;

    try {
        // Select only the columns you actually need for better performance
        $query = "SELECT * FROM team_members ORDER BY added_at asc";

        $stmt = $connection->prepare($query);
        $stmt->execute();

        // Fetch all rows as an associative array
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return the results, or an empty array if nothing is found
        return $results ?: [];
    } catch (PDOException $e) {
        // In production, consider logging this instead of using die()
        die("Query failed: " . $e->getMessage());
    }
}

// Store the result in the $members variable
$members = getTeamMembers();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>About NEXEN</title>
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
                    <button onclick="window.location.href='about'" class="text-red"><span>About</span></button>
                </div>

                <div class="d-flex align-items-center gap-2 ms-auto">
                    <div class="talk-to-us">
                        <button onclick="window.location.href='contact'">Contact Us</button>
                    </div>
                    <button onclick="window.location.href='login'" class="btn text-secondary-foreground nav-login-btn fs-7">Admin Login</button>
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

                    <a href="about" class="mobile-nav-link text-red">About</a>
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

    <!-- MAIN CONTENT  -->
    <main class="about-main-wrapper position-relative">
        <div class="about-page-top-glow w-100"></div>
        <section class="row row-cols-1 row-cols-md-2 no-gutter mx-auto max-w-wrapper" id="about-hero">
            <div class="col px-0">
                <div class="ascii-wrapper mb-2">
                    <p class="mb-0 text-muted-light">✦</p>
                    <p class="mb-0 text-muted-light">✦</p>
                    <p class="mb-0 text-muted-light">✦</p>
                </div>
                <h2 class="urbanist mb-4"><?= getContent("about", "intro_title") ?></h2>
                <p class="text-muted-foreground urbanist about-introduction"><?= getContent("about", "intro_paragraph") ?></p>

                <!-- happy customers section  -->
                <div class="row row-cols-3 px-1 gap-0 gap-md-0 mb-5 mt-5">
                    <div class="col px-2">
                        <div class="p-2 p-md-3 h-100 rounded-3 bg-card-down border-upper">
                            <h2><?= getContent("about", "clients") ?></h2>
                            <p class="mt-1 mb-0 text-muted-foreground fs-7">Clients</p>
                        </div>
                    </div>
                    <div class="col px-2">
                        <div class="p-2 p-md-3 h-100 rounded-3 bg-card-down border-upper">
                            <h2><?= getContent("about", "projects") ?></h2>
                            <p class="mt-1 mb-0 text-muted-foreground fs-7">Projects</p>
                        </div>
                    </div>
                    <div class="col px-2">
                        <div class="p-2 p-md-3 h-100 rounded-3 bg-card-down border-upper">
                            <h2><?= getContent("about", "years_of_experience") ?></h2>
                            <p class="mt-1 mb-0 text-muted-foreground fs-7">Years of experience</p>
                        </div>
                    </div>
                </div>

                <!-- mission vision section  -->
                <div class="row row-cols-2 px-1 mt-3 mb-3 mb-md-0">
                    <div class="col px-2">
                        <div class="ascii-wrapper mb-2">
                            <p class="mb-0 text-muted-light">✦</p>
                            <p class="mb-0 text-muted-light">✦</p>
                            <p class="mb-0 text-muted-light">✦</p>
                        </div>
                        <h2>Our Mission</h2>
                        <p class="mb-0 text-muted-light fs-7">
                            <?= getContent("about", "mission") ?>
                        </p>

                    </div>
                    <div class="col px-2">
                        <div class="ascii-wrapper mb-2">
                            <p class="mb-0 text-muted-light">✦</p>
                            <p class="mb-0 text-muted-light">✦</p>
                            <p class="mb-0 text-muted-light">✦</p>
                        </div>
                        <h2>Our Vision</h2>
                        <p class="mb-0 text-muted-light fs-7">
                            <?= getContent("about", "vision") ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Employees Images  -->
            <div class="col px-md-0 px-0 ps-0 ps-md-3">
                <div class="mb-3 mb-md-5 bg-card-down w-100 border-upper p-sm-3 p-md-4 p-2 rounded-3">
                    <img src="<?= getContent("about", "about_img_1") ?>" style="aspect-ratio: 8/5;" class="w-full rounded-1" alt="">
                </div>
                <div class="bg-card-down w-100 p-sm-3 p-md-4 p-2 border-upper rounded-3">
                    <img src="<?= getContent("about", "about_img_2") ?>" style="aspect-ratio: 8/5;" class="w-full rounded-1" alt="">
                </div>
            </div>
        </section>

        <!-- Core values -->
        <section id="mission-vision" class="mx-auto max-w-wrapper mb-5">
            <div class="row">
                <div class="col py-3 col-12 col-md-3 d-flex flex-column justify-content-center">
                    <div class="mission-block">
                        <div class="ascii-wrapper mb-2">
                            <p class="mb-0 text-muted-light">✦</p>
                            <p class="mb-0 text-muted-light">✦</p>
                            <p class="mb-0 text-muted-light">✦</p>
                        </div>
                        <h2 class="urbanist text-secondary-foreground">Core Values</h2>
                    </div>
                </div>
                <div class="col py-3 col-12 col-md-9">
                    <div style="row-gap:8px" class="border-thick bg-card-dark p-1 p-sm-3 p-md-4 rounded-3">
                        <div class="row row-cols-1 row-cols-md-2 py-2 mb-3">
                            <div class="col px-3 border-end border-color-border">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="mission-vision-badge-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hard-hat-icon lucide-hard-hat">
                                            <path d="M10 10V5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v5" />
                                            <path d="M14 6a6 6 0 0 1 6 6v3" />
                                            <path d="M4 15v-3a6 6 0 0 1 6-6" />
                                            <rect x="2" y="15" width="20" height="4" rx="1" />
                                        </svg>
                                    </div>
                                    <p class="fw-bold mb-0 ms-2 urbanist">Engineering that Performs</p>
                                </div>
                                <p class="text-muted-light fs-7 urbanist"><?= getContent("core-values", "value-1") ?></p>
                            </div>
                            <div class="col px-3 ">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="mission-vision-badge-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lightbulb-icon lucide-lightbulb">
                                            <path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5" />
                                            <path d="M9 18h6" />
                                            <path d="M10 22h4" />
                                        </svg>
                                    </div>
                                    <p class="fw-medium mb-0 ms-2">eXecution that Delivers</p>
                                </div>
                                <p class="text-muted-light fs-7 urbanist">
                                    <?= getContent("core-values", "value-2") ?>
                                </p>
                            </div>
                        </div>
                        <!-- separator  -->
                        <div class="border-color-border border-bottom mb-3"></div>

                        <div class="d-flex align-items-center py-2">
                            <div class="w-100 px-3">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="mission-vision-badge-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-round-icon lucide-users-round">
                                            <path d="M18 21a8 8 0 0 0-16 0" />
                                            <circle cx="10" cy="8" r="5" />
                                            <path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3" />
                                        </svg></div>
                                    <p class="fw-medium mb-0 ms-2">Client-Centric</p>
                                </div>
                                <p class="text-muted-light fs-7 urbanist"><?= getContent("core-values", "value-3") ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- OUR ACHIEVEMENTS  -->
        <section class="our-achievements py-4 mx-auto max-w-wrapper">
            <div class="mb-4">
                <div class="ascii-wrapper mb-2">
                    <p class="mb-0 text-muted-light">✦</p>
                    <p class="mb-0 text-muted-light">✦</p>
                    <p class="mb-0 text-muted-light">✦</p>
                </div>
                <h2 class="urbanist text-secondary-foreground">Our Achievements</h2>
                <p class="text-muted-foreground fs-7">
                    <?= htmlspecialchars(getContent("achievements", "title-description")) ?>
                </p>
            </div>

            <div class="row row-cols-1 px-1 row-cols-md-3">
                <div class="col px-2 mb-2">
                    <div class="p-4 bg-card-dark h-100 our-achievement-card border-thick rounded-3">
                        <h5 class="text-secondary-foreground urbanist mb-4">
                            <?= htmlspecialchars(getContent("achievements", "achieve-title-1")) ?>
                        </h5>
                        <p class="text-muted-light mb-0 fs-7 urbanist">
                            <?= htmlspecialchars(getContent("achievements", "achieve-desc-1")) ?>
                        </p>
                    </div>
                </div>
                <div class="col px-2 mb-2">
                    <div class="p-4 bg-card-dark h-100 our-achievement-card border-thick rounded-3">
                        <h5 class="text-secondary-foreground urbanist mb-4"><?= htmlspecialchars(getContent("achievements", "achieve-title-2")) ?>
                        </h5>
                        <p class="text-muted-light mb-0 fs-7 urbanist">
                            <?= htmlspecialchars(getContent("achievements", "achieve-desc-2")) ?>
                        </p>
                    </div>
                </div>
                <div class="col px-2 mb-2">
                    <div class="p-4 bg-card-dark h-100 our-achievement-card border-thick rounded-3">
                        <h5 class="text-secondary-foreground urbanist mb-4">
                            <?= htmlspecialchars(getContent("achievements", "achieve-title-3")) ?>
                        </h5>
                        <p class="text-muted-light mb-0 fs-7 urbanist">
                            <?= htmlspecialchars(getContent("achievements", "achieve-desc-3")) ?>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- MEET THE TEAM -->
        <section id="meet-the-team" class="mx-auto max-w-wrapper py-5">
            <div class="mb-4">
                <div class="ascii-wrapper mb-2">
                    <p class="mb-0 text-muted-light">✦</p>
                    <p class="mb-0 text-muted-light">✦</p>
                    <p class="mb-0 text-muted-light">✦</p>
                </div>
                <h2 class="urbanist text-secondary-foreground">Meet the Nexen Team</h2>
                <p class="text-muted-foreground fs-7">Our story is one of continuous growth and evolution. We started as a small team with big dreams, determined to create a real estate platform that transcended the ordinary.</p>
            </div>

            <div class="row row-cols-1 px-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4">
                <?php if (!empty($members)): ?>
                    <?php foreach ($members as $member): ?>
                        <div class="col mb-3 px-2">
                            <div class="border-border bg-glass p-4 rounded-3 h-100">
                                <?php
                                // Use the image from the database, fallback to default if empty or invalid
                                $src = !empty($member['avatar']) ? $member['avatar'] : 'https://placehold.co/600x400';

                                ?>

                                <img src="uploads/avatars/<?= htmlspecialchars($src) ?>"
                                    class="w-100 rounded-1"
                                    style="aspect-ratio: 5/6; object-fit:cover;"
                                    alt="<?= htmlspecialchars($member['full_name'] ?? 'Team Member') ?>">

                                <h5 class="text-secondary-foreground text-center mt-4 urbanist">
                                    <?= htmlspecialchars($member['full_name'] ?? 'Unknown Member') ?>
                                </h5>

                                <p class="text-muted-light fs-7 text-center mb-0 urbanist">
                                    <?= htmlspecialchars($member['position'] ?? 'Team Member') ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p class="text-muted">No team members found.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- BLOG SECTIONS  -->
        <section id="blog" class="mx-auto max-w-wrapper py-5">
            <div class="mb-4">
                <div class="ascii-wrapper mb-2">
                    <p class="mb-0 text-muted-light">✦</p>
                    <p class="mb-0 text-muted-light">✦</p>
                    <p class="mb-0 text-muted-light">✦</p>
                </div>
                <h2 class="urbanist text-secondary-foreground"><?= getContent("blog", "blog_header") ?></h2>
                <p class="text-muted-foreground fs-7"><?= getContent("blog", "blog_description") ?></p>
            </div>

            <div class="row row-cols-1 px-1 row-cols-sm-2 row-cols-md-3">
                <div class="col px-2 mb-3">
                    <div class="border-border h-100 rounded-3 p-4 d-flex flex-column align-items-start">
                        <img src="<?= htmlspecialchars(getContent("blog", "img")) ?>" class="w-full rounded-1" style="object-fit: cover; aspect-ratio:5/3" alt="">
                        <div class="blog-date-badge mt-3">
                            <p class="text-blue mb-0 fs-7"><i class="me-2 bi bi-calendar4"></i> Jan 21 2024</p>
                        </div>
                        <h6 class="urbanist mt-3"><?= htmlspecialchars(getContent("blog", "title")) ?></h6>
                        <p class="fs-7 text-muted-light mb-5">Newly closed deal HRMAX partner NSCC Cooperative...</p>
                        <div class="w-100 d-flex justify-content-end">
                            <a href="https://www.facebook.com/Nexen.ph/posts/pfbid0ij5LmrPVWTHxkLrFXVLYjoZ1pnqF6hNx399daWERG5LBo28nBtaPaXipP6YZwcojl" target="_blank" class="pt-1 text-red fs-7">Read More</a>
                        </div>
                    </div>
                </div>
                <div class="col px-2 mb-3">
                    <div class="border-border h-100 rounded-3 p-4 d-flex flex-column align-items-start">
                        <img src="<?= htmlspecialchars(getContent("blog", "img1")) ?>" class="w-full rounded-1" style="object-fit: cover; aspect-ratio:5/3" alt="">
                        <div class="blog-date-badge mt-3">
                            <p class="text-blue mb-0 fs-7"><i class="me-2 bi bi-calendar4"></i> April 24 2024</p>
                        </div>

                        <h6 class="urbanist mt-3"><?= htmlspecialchars(getContent("blog", "title1")) ?></h6>
                        <p class="fs-7 text-muted-light mb-5">Tam-an BMPC has signed a strategic partnership with Nexen Innovation Technologies, Inc...</p>
                        <div class="w-100 d-flex justify-content-end">
                            <a href="https://www.facebook.com/Nexen.ph/posts/pfbid0LgszzbLA2r4359ZVv3eauGw1AzMs5V3cRNfHta3CKNsRV1oU39sgRekdTg4Qgod8l" target="_blank" class="pt-1 text-red fs-7">Read More</a>
                        </div>
                    </div>
                </div>
                <div class="col px-2 mb-3">
                    <div class="border-border h-100 rounded-3 p-4 d-flex flex-column align-items-start">
                        <img src="<?= htmlspecialchars(getContent("blog", "img2")) ?>" class="w-full rounded-1" style="object-fit: cover; aspect-ratio:5/3" alt="nexen-img">
                        <div class="blog-date-badge  mt-3">
                            <p class="text-blue mb-0 fs-7"><i class="me-2 bi bi-calendar4"></i> May 17 2024</p>
                        </div>

                        <h6 class="urbanist mt-3"><?= htmlspecialchars(getContent("blog", "title2")) ?></h6>
                        <p class="fs-7 text-muted-light mb-5">A week long celebration of NEXEN 14th years of continuous success...
                        </p>
                        <div class="w-100 d-flex justify-content-end">
                            <a href="https://www.facebook.com/share/p/12GLkdSMobU" target="_blank" class="pt-1 text-red fs-7">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- VALUED CLIENTS  -->
        <!-- <section id="our-valued-clients" class="overflow-x-hidden">
            <div class="mb-4 mx-auto max-w-wrapper">
                <h2 class="urbanist text-secondary-foreground">Our Valued Clients</h2>
                <p class="text-muted-foreground fs-7">Our story is one of continuous growth and evolution. We started as a small team with big dreams, determined to create a real estate platform that transcended the ordinary.</p>
            </div>
            <div class="value-clients-wrapper pb-5 mb-5 position-relative">
                <div class="max-w-wrapper mx-auto w-100">
                    <div class="d-flex gap-2 align-items-center w-100 first-row">
                        <div class="mb-2 valued-clients-card">
                            <div class="bg-card-dark border-thick overflow-hidden p-4 rounded-3 position-relative">
                                <img src="assets/png/image 98.png" class="bi bi-8-square position-absolute" style="right:2rem; bottom:1.3rem; width:5.5rem; height:5rem;"></i>
                                <p class="text-muted-light fs-7">Since 2019</p>
                                <h3 class="urbanist">JFC Coop</h3>
                            </div>
                        </div>
                        <div class="mb-2 valued-clients-card">
                            <div class="bg-card-dark border-thick overflow-hidden p-4 rounded-3 position-relative">
                                <img src="assets/png/image 98.png" class="bi bi-8-square position-absolute" style="right:2rem; bottom:1.3rem; width:5.5rem; height:5rem;"></i>
                                <p class="text-muted-light fs-7">Since 2019</p>
                                <h3 class="urbanist">JFC Coop</h3>
                            </div>
                        </div>
                        <div class="mb-2 valued-clients-card">
                            <div class="bg-card-dark border-thick overflow-hidden p-4 rounded-3 position-relative">
                                <img src="assets/png/image 98.png" class="bi bi-8-square position-absolute" style="right:2rem; bottom:1.3rem; width:5.5rem; height:5rem;"></i>
                                <p class="text-muted-light fs-7">Since 2019</p>
                                <h3 class="urbanist">JFC Coop</h3>
                            </div>
                        </div>
                        <div class="mb-2 valued-clients-card">
                            <div class="bg-card-dark border-thick overflow-hidden p-4 rounded-3 position-relative">
                                <img src="assets/png/image 98.png" class="bi bi-8-square position-absolute" style="right:2rem; bottom:1.3rem; width:5.5rem; height:5rem;"></i>
                                <p class="text-muted-light fs-7">Since 2019</p>
                                <h3 class="urbanist">JFC Coop</h3>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 align-items-center w-100 second-row">
                        <div class="mb-2 valued-clients-card">
                            <div class="bg-card-dark border-thick overflow-hidden p-4 rounded-3 position-relative">
                                <img src="assets/png/image 98.png" class="bi bi-8-square position-absolute" style="right:2rem; bottom:1.3rem; width:5.5rem; height:5rem;"></i>
                                <p class="text-muted-light fs-7">Since 2019</p>
                                <h3 class="urbanist">JFC Coop</h3>
                            </div>
                        </div>
                        <div class="mb-2 valued-clients-card">
                            <div class="bg-card-dark border-thick overflow-hidden p-4 rounded-3 position-relative">
                                <img src="assets/png/image 98.png" class="bi bi-8-square position-absolute" style="right:2rem; bottom:1.3rem; width:5.5rem; height:5rem;"></i>
                                <p class="text-muted-light fs-7">Since 2019</p>
                                <h3 class="urbanist">JFC Coop</h3>
                            </div>
                        </div>
                        <div class="mb-2 valued-clients-card">
                            <div class="bg-card-dark border-thick overflow-hidden p-4 rounded-3 position-relative">
                                <img src="assets/png/image 98.png" class="bi bi-8-square position-absolute" style="right:2rem; bottom:1.3rem; width:5.5rem; height:5rem;"></i>
                                <p class="text-muted-light fs-7">Since 2019</p>
                                <h3 class="urbanist">JFC Coop</h3>
                            </div>
                        </div>
                        <div class="mb-2 valued-clients-card">
                            <div class="bg-card-dark border-thick overflow-hidden p-4 rounded-3 position-relative">
                                <img src="assets/png/image 98.png" class="bi bi-8-square position-absolute" style="right:2rem; bottom:1.3rem; width:5.5rem; height:5rem;"></i>
                                <p class="text-muted-light fs-7">Since 2019</p>
                                <h3 class="urbanist">JFC Coop</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section> -->
    </main>

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