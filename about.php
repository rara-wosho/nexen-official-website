<?php
require_once 'db_connect.php';


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
        <div class="max-w-wrapper w-100 d-flex align-items-center mx-auto">
            <a href="/nexen-official-website" class="logo ">
                <img src="assets/png/nexen-logo.png" class="nav-logo" alt="nexen-logo"> <!-- DO NOT TOUCH, TAGUON NAKO NI -->
            </a>

            <!-- Desktop Dropdown Nav -->
            <div class="links-container">
                <div class="dropdown"><button onclick="window.location.href='/nexen-official-website'">Home</button></div>
                <div class="dropdown">
                    <button>Solutions</button>
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
                    <button> <a href="">About</a></button>
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

    <!-- MAIN CONTENT  -->
    <main class="about-main-wrapper relative">
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

                <div class="row row-cols-3 gap-0 gap-md-0 mb-5 mb-md-0 mt-5">
                    <div class="col">
                        <div class="p-2 p-md-4 rounded-3 bg-card-down border-upper">
                            <h2><?= getContent("about", "happy_customers") ?></h2>
                            <p class="mt-1 mb-0 text-muted-foreground fs-7">Happy Customers</p>
                        </div>
                    </div>
                    <div class="col">
                        <div class="p-2 p-md-4 rounded-3 bg-card-down border-upper">
                            <h2><?= getContent("about", "properties_for_clients") ?></h2>
                            <p class="mt-1 mb-0 text-muted-foreground fs-7">Properties for clients</p>
                        </div>
                    </div>
                    <div class="col">
                        <div class="p-2 p-md-4 rounded-3 bg-card-down border-upper">
                            <h2><?= getContent("about", "years_of_experience") ?></h2>
                            <p class="mt-1 mb-0 text-muted-foreground fs-7">Years of experience</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col ps-0 ps-md-3 ps-lg-4 pe-0">
                <div class="mb-3 mb-md-5 bg-card-down border-upper p-sm-3 p-md-4 p-2 rounded-3">
                    <img src="assets/png/nexen2 1.png" style="aspect-ratio: 8/5;" class="w-full rounded-1" alt="">
                </div>
                <div class="bg-card-down p-sm-3 p-md-4 p-2 border-upper rounded-3">
                    <img src="assets/png/68087205ad60a 2.png" style="aspect-ratio: 8/5;" class="w-full rounded-1" alt="">
                </div>
            </div>
        </section>

        <!-- MISSION VISION -->
        <section id="mission-vision" class="mx-auto max-w-wrapper mb-5">
            <div class="row">
                <div class="col py-3 col-12 col-md-4">
                    <div class="d-flex align-items-center mission-vision-wrapper">
                        <div class="mission-block">
                            <div class="ascii-wrapper mb-2">
                                <p class="mb-0 text-muted-light">✦</p>
                                <p class="mb-0 text-muted-light">✦</p>
                                <p class="mb-0 text-muted-light">✦</p>
                            </div>
                            <h2 class="urbanist text-secondary-foreground">Our Mission</h2>
                            <p class="text-muted-foreground fs-7">Bring technological systems innovation to every partner institution to help them reach their full potential.</p>
                        </div>
                        <div class="vision-block">
                            <div class="ascii-wrapper mb-2">
                                <p class="mb-0 text-muted-light">✦</p>
                                <p class="mb-0 text-muted-light">✦</p>
                                <p class="mb-0 text-muted-light">✦</p>
                            </div>
                            <h2 class="urbanist text-secondary-foreground">Our Vision</h2>
                            <p class="text-muted-foreground fs-7">To become the leading provider of innovative business engine solutions.</p>
                        </div>
                    </div>
                </div>
                <div class="col py-3 col-12 col-md-8">
                    <div style="row-gap:8px" class="border-thick bg-card-dark p-1 p-sm-3 p-md-4 rounded-3">
                        <div class="d-flex align-items-center py-2 mb-3">
                            <div class="w-50 px-3 border-end border-color-border">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="mission-vision-badge-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-handshake-icon lucide-heart-handshake">
                                            <path d="M19.414 14.414C21 12.828 22 11.5 22 9.5a5.5 5.5 0 0 0-9.591-3.676.6.6 0 0 1-.818.001A5.5 5.5 0 0 0 2 9.5c0 2.3 1.5 4 3 5.5l5.535 5.362a2 2 0 0 0 2.879.052 2.12 2.12 0 0 0-.004-3 2.124 2.124 0 1 0 3-3 2.124 2.124 0 0 0 3.004 0 2 2 0 0 0 0-2.828l-1.881-1.882a2.41 2.41 0 0 0-3.409 0l-1.71 1.71a2 2 0 0 1-2.828 0 2 2 0 0 1 0-2.828l2 mb-3.823-2.762" />
                                        </svg></div>
                                    <p class="fw-bold mb-0 ms-2 urbanist">Trust</p>
                                </div>
                                <p class="text-muted-light fs-7 urbanist">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, rem.</p>
                            </div>
                            <div class="w-50 px-3 ">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="mission-vision-badge-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-graduation-cap-icon lucide-graduation-cap">
                                            <path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z" />
                                            <path d="M22 10v6" />
                                            <path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5" />
                                        </svg>
                                    </div>
                                    <p class="fw-medium mb-0 ms-2">Excellence</p>
                                </div>
                                <p class="text-muted-light fs-7 urbanist">We set the bar high for ourselves. From the properties we list to the services we provide.</p>
                            </div>
                        </div>
                        <!-- separator  -->
                        <div class="border-color-border border-bottom mb-3"></div>

                        <div class="d-flex align-items-center py-2">
                            <div class="w-50 px-3 border-end border-color-border">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="mission-vision-badge-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-round-icon lucide-users-round">
                                            <path d="M18 21a8 8 0 0 0-16 0" />
                                            <circle cx="10" cy="8" r="5" />
                                            <path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3" />
                                        </svg></div>
                                    <p class="fw-medium mb-0 ms-2">Client-Centric</p>
                                </div>
                                <p class="text-muted-light fs-7 urbanist">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, rem.</p>
                            </div>
                            <div class="w-50 px-3 ">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="mission-vision-badge-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star-icon lucide-star">
                                            <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z" />
                                        </svg></div>
                                    <p class="fw-medium mb-0 ms-2">Our Commitment</p>
                                </div>
                                <p class="text-muted-light fs-7 urbanist">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, rem.</p>
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
                <p class="text-muted-foreground fs-7">Our story is one of continuous growth and evolution. We started as a small team with big dreams, determined to create a real estate platform that transcended the ordinary.</p>
            </div>

            <div class="row row-cols-1 px-1 row-cols-md-3">
                <div class="col px-2 mb-2">
                    <div class="p-4 bg-card-dark our-achievement-card border-thick rounded-3">
                        <h5 class="text-secondary-foreground urbanist mb-4">3+ Years of Excellence</h5>
                        <p class="text-muted-light mb-0 fs-7 urbanist">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolor numquam officia impedit labore natus qui doloremque sit itaque illum hic.</p>
                    </div>
                </div>
                <div class="col px-2 mb-2">
                    <div class="p-4 bg-card-dark our-achievement-card border-thick rounded-3">
                        <h5 class="text-secondary-foreground urbanist mb-4">3+ Years of Excellence</h5>
                        <p class="text-muted-light mb-0 fs-7 urbanist">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolor numquam officia impedit labore natus qui doloremque sit itaque illum hic.</p>
                    </div>
                </div>
                <div class="col px-2 mb-2">
                    <div class="p-4 bg-card-dark our-achievement-card border-thick rounded-3">
                        <h5 class="text-secondary-foreground urbanist mb-4">3+ Years of Excellence</h5>
                        <p class="text-muted-light mb-0 fs-7 urbanist">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolor numquam officia impedit labore natus qui doloremque sit itaque illum hic.</p>
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
                <div class="col mb-3 px-2">
                    <div class="border-border p-4 rounded-3">
                        <img src="assets/png/nexen2 1.png" class="w-100 rounded-1" style="aspect-ratio: 5/4; object-fit:cover;" alt="">
                        <h5 class="text-secondary-foreground text-center mt-4 urbanist">Max Mitchel</h5>
                        <p class="text-muted-light fs-7 text-center mb-0 urbanist">Founder</p>
                    </div>
                </div>
                <div class="col mb-3 px-2">
                    <div class="border-border p-4 rounded-3">
                        <img src="assets/png/nexen2 1.png" class="w-100 rounded-1" style="aspect-ratio: 5/4; object-fit:cover;" alt="">
                        <h5 class="text-secondary-foreground text-center mt-4 urbanist">Max Mitchel</h5>
                        <p class="text-muted-light fs-7 text-center mb-0 urbanist">Founder</p>
                    </div>
                </div>
                <div class="col mb-3 px-2">
                    <div class="border-border p-4 rounded-3">
                        <img src="assets/png/nexen2 1.png" class="w-100 rounded-1" style="aspect-ratio: 5/4; object-fit:cover;" alt="">
                        <h5 class="text-secondary-foreground text-center mt-4 urbanist">Max Mitchel</h5>
                        <p class="text-muted-light fs-7 text-center mb-0 urbanist">Founder</p>
                    </div>
                </div>
                <div class="col mb-3 px-2">
                    <div class="border-border p-4 rounded-3">
                        <img src="assets/png/nexen2 1.png" class="w-100 rounded-1" style="aspect-ratio: 5/4; object-fit:cover;" alt="">
                        <h5 class="text-secondary-foreground text-center mt-4 urbanist">Max Mitchel</h5>
                        <p class="text-muted-light fs-7 text-center mb-0 urbanist">Founder</p>
                    </div>
                </div>
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
                    <div class="border-border rounded-3 p-4">
                        <img src="assets/png/group_pic-3.png" class="w-full rounded-1" style="object-fit: cover; aspect-ratio:5/3" alt="">
                        <div class="blog-date-badge mt-3">
                            <p class="text-blue mb-0 fs-7"><i class="me-2 bi bi-calendar4"></i> Jan 21 2024</p>
                        </div>

                        <h6 class="urbanist mt-3">NSCC Cooperative Partnetship</h6>
                        <p class="fs-7 text-muted-light mb-5">Newly closed deal HRMAX partner NSCC Cooperative...</p>

                        <div class="w-100 d-flex justify-content-end">
                            <a href="https://www.facebook.com/Nexen.ph/posts/pfbid0ij5LmrPVWTHxkLrFXVLYjoZ1pnqF6hNx399daWERG5LBo28nBtaPaXipP6YZwcojl" target="_blank" class="pt-1 text-red fs-7">Read More</a>
                        </div>
                    </div>
                </div>
                <div class="col px-2 mb-3">
                    <div class="border-border rounded-3 p-4">
                        <img src="assets/png/group_pic-2.png" class="w-full rounded-1" style="object-fit: cover; aspect-ratio:5/3" alt="">
                        <div class="blog-date-badge mt-3">
                            <p class="text-blue mb-0 fs-7"><i class="me-2 bi bi-calendar4"></i> April 24 2024</p>
                        </div>

                        <h6 class="urbanist mt-3">Tam-an BMPC Partnership</h6>
                        <p class="fs-7 text-muted-light mb-5">Tam-an BMPC has signed a strategic partnership with Nexen Innovation Technologies, Inc...</p>
                        <div class="w-100 d-flex justify-content-end">
                            <a href="https://www.facebook.com/Nexen.ph/posts/pfbid0LgszzbLA2r4359ZVv3eauGw1AzMs5V3cRNfHta3CKNsRV1oU39sgRekdTg4Qgod8l" target="_blank" class="pt-1 text-red fs-7">Read More</a>
                        </div>
                    </div>
                </div>
                <div class="col px-2 mb-3">
                    <div class="border-border rounded-3 p-4">
                        <img src="assets/png/group_pic-1.png" class="w-full rounded-1" style="object-fit: cover; aspect-ratio:5/3" alt="nexen-img">
                        <div class="blog-date-badge  mt-3">
                            <p class="text-blue mb-0 fs-7"><i class="me-2 bi bi-calendar4"></i> May 17 2024</p>
                        </div>

                        <h6 class="urbanist mt-3">14th Anniversary</h6>
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
        <section id="our-valued-clients" class="mx-auto max-w-wrapper py-5">
            <div class="mb-4">
                <h2 class="urbanist text-secondary-foreground">Our Valued Clients</h2>
                <p class="text-muted-foreground fs-7">Our story is one of continuous growth and evolution. We started as a small team with big dreams, determined to create a real estate platform that transcended the ordinary.</p>
            </div>

            <div class="row row-cols-1 row-cols-md-2">
                <div class="col">
                    <div class="bg-card-dark border-thick p-4 rounded-3">
                        <p class="text-muted-light fs-7">Since 2019</p>
                        <h3 class="urbanist">JFC Coop</h3>
                    </div>
                </div>
                <div class="col">
                    <div class="bg-card-dark border-thick p-4 rounded-3">
                        <p class="text-muted-light fs-7">Since 2018</p>
                        <h3 class="urbanist">AFCCO</h3>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- FOOTER  -->
    <footer>
        <div class="glow-4"></div>
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

    const lenis = new Lenis({
        autoRaf: true,
        autoToggle: true,
        anchors: true,
        allowNestedScroll: true,
        naiveDimensions: true,
        stopInertiaOnNavigate: true
    });

    // function updateVisibleClass() {
    //   document.querySelectorAll('.animation-div, .watch-on-screen').forEach((box) => {
    //     const rect = box.getBoundingClientRect();
    //     const visible = rect.top < window.innerHeight - 150 && rect.bottom > 0;
    //     box.classList.toggle('active', visible);
    //   });
    // }

    // lenis.on('scroll', updateVisibleClass);
    // window.addEventListener('load', updateVisibleClass);
    // window.addEventListener('resize', updateVisibleClass);
</script>

</html>