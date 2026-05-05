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
              AND content_key ILIKE :keyword ORDER BY RANDOM()";

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

$heroTitle = getContent("hero", "title");
$herroTitleArr = explode(" ", $heroTitle);

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

<body class="index-page">
  <!-- ══ HEADER ══════════════════════════════════════════ -->
  <header id="header" class="">
    <div class="max-w-wrapper  w-100 d-flex align-items-center mx-auto">
      <a href="#" class="logo ">
        <img src="<?= htmlspecialchars(getContent("official-logo", "nexen-logo")) ?>" class="nav-logo" alt="nexen-logo">
      </a>

      <!-- Desktop Dropdown Nav -->
      <div class="links-container">
        <div class="dropdown"><button><a href="#" class="text-red">Home</a></button></div>
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
            <a href="<?= url("hrmax") ?>" style="--i:1">HRMAX</a>
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

        <!-- right side navbar  -->
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
          <a href="#" class="mobile-nav-link text-red">Home</a>

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
              <a href="<?= url("hrmax") ?>">HRMAX</a>
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

          <a href="<?= url("about") ?>" class="mobile-nav-link">About</a>
        </div>

        <div class="mobile-nav-footer">
          <a href="<?= url("contact") ?>" class="mobile-nav-cta">Contact Us</a>
          <a href="<?= url("login") ?>" class="mobile-nav-login">
            <i class="bi bi-person-circle"></i>
            Admin Login
          </a>
        </div>
      </nav>
    </div>
  </header>

  <main>
    <!-- ══ HERO ════════════════════════════════════════════ -->
    <section id="hero">
      <div class="hero-inner w-100" data-aos="fade-up" data-aos-delay="100">
        <h1 class="hero-title w-100">
          <?php
          if (count($herroTitleArr) < 1) {
            $herroTitleArr = ["Required"];
          }

          foreach ($herroTitleArr as $word) {
            echo "<span>$word</span>";
          }
          ?>

        </h1>
        <p class="hero-sub">
          <?php echo getContent('hero', 'subtitle'); ?>
        </p>
        <div class="hero-actions">
          <a href="about" class="btn-ghost-hero">
            About Us
          </a>
          <a href="book-a-demo" class="btn-primary-hero">
            Book a Demo
            <i class="bi bi-arrow-right"></i>
          </a>
        </div>
      </div>

      <!-- <div class="hero-scroll">
        <span>Scroll</span>
        <div class="scroll-line"></div>
      </div> -->

      <img class="abstract-left" src="./assets/png/abstract-left.png" />
      <img class="abstract-right" src="./assets/png/abstract-right.png" />

      <!-- Radial gradient top  -->
      <div class="hero-radial-top"></div>

    </section>

    <!-- RATINGS & CLIENTS -->
    <div class="bg-background d-flex align-items-center justify-content-center hero-lower-tabs">
      <div class="wrapper row py-4 w-100 row-cols-1 row-cols-md-3">

        <div class="hero-lower-tab d-flex flex-column col py-3">
          <p class="text-primary text-center">Clients</p>
          <h3 class="text-secondary-foreground text-center fw-bold">
            <?= getContent('ratings_clients', 'value_clients'); ?>
          </h3>
        </div>

        <div class="hero-lower-tab d-flex flex-column col py-3">
          <p class="text-primary text-center">Projects</p>
          <h3 class="text-secondary-foreground text-center fw-bold">
            <?= getContent('ratings_clients', 'value_projects'); ?>
          </h3>
        </div>

        <div class="hero-lower-tab d-flex flex-column col py-3">
          <p class="text-primary text-center">Years of Experience</p>
          <h3 class="text-secondary-foreground text-center fw-bold">
            <?= getContent("ratings_clients", "five_star_reviews") ?>
          </h3>
        </div>

      </div>
    </div>

    <!-- ══ SERVICES ══════════════════════════════════════════ -->
    <section id="services" class="bg-background ">
      <div class="services-glow"></div>
      <img src="./assets/png/prism.png" alt="" class="prisms">
      <div class="max-w-wrapper mx-auto position-relative" style="isolation: isolate;">
        <img src="assets/png/flare.png" class="position-absolute opacity-25 flare-image" style="z-index:-1;width:700px; aspect-ratio:8/12;bottom:-10rem; right:-6rem; transform:rotate(50deg)" alt="">
        <div class="section-header" data-aos="fade-up">
          <h2 class="montserrat fs-1 text-secondary-foreground">Services We <span class="text-primary">Offer</span></h2>

          <p class="inter text-muted-foreground services-description"><?= getContent("services", "subtitle") ?></p>
        </div>

        <div class="row gy-3 gy-md-4 service-card-wrapper">
          <?php
          $services = [
            ['icon' => 'bi-server',              'key' => '1'],
            ['icon' => 'bi-distribute-horizontal', 'key' => '2'],
            ['icon' => 'bi-window-fullscreen',   'key' => '3'],
            ['icon' => 'bi-chat-dots-fill',      'key' => '4'],
            ['icon' => 'bi-cloud-check-fill',    'key' => '5'],
            ['icon' => 'bi-lightbulb-fill',      'key' => '6'],
          ];
          $delays = [100, 200, 300, 400, 500, 600];
          foreach ($services as $i => $s):
            $k = $s['key'];
            $evenIndex = floor($i / 2) + 1;
          ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo $delays[$i]; ?>">
              <div class="service-card <?php if ($i % 2 == 1) echo 'card-even' ?>">
                <div class="service-icon"><i class="bi fs-6 <?php echo $s['icon']; ?>"></i></div>
                <p class="service-content-description text-muted-foreground"><?php echo getContent('services', 'service_content_' . $k); ?></p>
                <h2 class="text-secondary-foreground mt-auto"><?php echo getContent('services', 'service_' . $k); ?></h2>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="section-header mt-5 pt-5" data-aos="fade-up">
          <h2 class="montserrat fs-1 text-secondary-foreground">With Us You Can <span class="text-primary">Trust</span></h2>
        </div>

        <div class="services-lower-cards-wrapper row row-cols-1 row-cols-sm-2 row-cols-lg-4">
          <div class="mb-5 pe-4 col services-lower-cardd">
            <h1 class="text-secondary-foreground mb-3"><?= getContent("you_can_trust", "yct_value_1") ?></h1>
            <div class="d-flex flex-column">
              <p class="fs-5 text-secondary-foreground fw-light lower-cards-desc"><?= getContent("you_can_trust", "yct_title_1") ?></p>
              <small class="text-muted-light lower-cards-small"><?= getContent("you_can_trust", "yct_desc_1") ?></small>
            </div>
            <div class="mt-5 d-flex align-items-end lower-cards-images">
              <div style="z-index:20;" class="bg-white p-1 rounded-circle">
                <img src="assets/img/others/viber_image_2026-04-28_14-25-42-825.jpg" alt="" class="service-circle-img">
              </div>
              <div style="z-index:20; transform:translateX(-30%);" class="bg-white p-1 rounded-circle">
                <img src="assets/img/others/475453264_1442850116605837_6348550667114118015_n.jpg" alt="" class="service-circle-img">
              </div>
              <div style="z-index:20; transform:translateX(-60%);" class="bg-white p-1 rounded-circle">
                <img src="assets/img/others/68087205ad60a.jpg" alt="" class="service-circle-img">
              </div>
            </div>
          </div>
          <div data-aos-delay="200" class="mb-5 pe-4 col services-lower-cardd">
            <h1 class="text-secondary-foreground mb-3"><?= getContent("you_can_trust", "yct_value_2") ?></h1>
            <div class="d-flex flex-column">
              <p class="fs-5 text-secondary-foreground fw-light lower-cards-desc"><?= getContent("you_can_trust", "yct_title_2") ?></p>
              <small class="text-muted-light lower-cards-small"><?= getContent("you_can_trust", "yct_desc_2") ?></small>
            </div>
            <div class="mt-5 d-flex align-items-end  lower-cards-images">
              <div style="z-index:20;" class="bg-white p-1 rounded-circle">
                <img src="assets/img/others/viber_image_2026-04-28_14-25-24-578.jpg" alt="" class="service-circle-img">
              </div>
              <div style="z-index:20; transform:translateX(-30%);" class="bg-white p-1 rounded-circle">
                <img src="assets/img/others/viber_image_2026-04-28_13-53-50-851.png" alt="" class="service-circle-img">
              </div>
            </div>
          </div>

          <div class="mb-5 pe-4 col services-lower-cardd">
            <h1 class="text-secondary-foreground mb-3"><?= getContent("you_can_trust", "yct_value_3") ?></h1>
            <div class="d-flex flex-column">
              <p class="fs-5 text-secondary-foreground fw-light lower-cards-desc"><?= getContent("you_can_trust", "yct_title_3") ?></p>
              <small class="text-muted-light lower-cards-small"><?= getContent("you_can_trust", "yct_desc_3") ?></small>
            </div>
            <div class="mt-5 d-flex align-items-end lower-cards-images">
              <div style="z-index:20;" class="bg-white p-1 rounded-circle">
                <img src="assets/img/v2/69f049bf79463.png" alt="" class="service-circle-img">
              </div>
              <div style="z-index:20; transform:translateX(-30%);" class="bg-white p-1 rounded-circle">
                <img src="assets/img/69e8381ec920a.png" alt="" class="service-circle-img">
              </div>
              <div style="z-index:20; transform:translateX(-60%);" class="bg-white p-1 rounded-circle">
                <img src="assets/img/v2/69e8389950ed7.png" alt="" class="service-circle-img">
              </div>
            </div>
          </div>

          <div data-aos-delay="200" class="mb-5 pe-4 col services-lower-cardd">
            <h1 class="text-secondary-foreground mb-3"><?= getContent("you_can_trust", "yct_value_4") ?></h1>
            <div class="d-flex flex-column">
              <p class="fs-5 text-secondary-foreground fw-light lower-cards-desc"><?= getContent("you_can_trust", "yct_title_4") ?></p>
              <small class="text-muted-light lower-cards-small"><?= getContent("you_can_trust", "yct_desc_4") ?></small>
            </div>
            <div class="mt-5 d-flex align-items-end lower-cards-images">
              <div style="z-index:20;" class="bg-white p-1 rounded-circle">
                <img src="assets/img/others/viber_image_2026-04-28_14-25-24-578.jpg" alt="" class="service-circle-img">
              </div>
              <div style="z-index:20; transform:translateX(-30%);" class="bg-white p-1 rounded-circle">
                <img src="assets/img/others/viber_image_2026-04-28_13-53-50-851.png" alt="" class="service-circle-img">
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- PEOPLE WE ARE WORKING WITH  -->
    <section id="working-with" class="py-3 py-md-5">
      <p class="text-center px-5 fs-5 fw-semibold text-secondary-foreground"><span class="text-primary">Meet</span> the People We are Working With</p>
      <div class="gradient-01"></div>
      <div class="gradient-02"></div>

      <div class="overflow-x-hidden">
        <div class="d-flex partners-logo-wrapper align-items-center gap-4">
          <?php
          $images = getImages("partners_logo", "p_logo_img");

          foreach ($images as $img) {
          ?>
            <div class="d-flex align-items-center justify-content-center rounded-circle">
              <?php
              $source = $img === "." ? "assets/png/prism.png" : $img;
              ?>
              <img src="<?= htmlspecialchars($source) ?>" style="flex-shrink:0; height:90px; object-fit:contain; min-width:70px" class="" alt="logo1" />
            </div>
          <?php } ?>
        </div>
      </div>
    </section>


    <!-- LOWER CTA BANNER  -->
    <section id="cta-banner-section" class="px-4" data-aos="fade-up">
      <div class="cta-banner" id="cta-banner">
        <h1 class="montserrat">Start Your Real Journey Today</h1>
        <p>
          For startups, enterprises, and growing businesses, NEXEN
          Innovation Technologies delivers smart, future-ready solutions.
          Innovate faster. Lead with confidence.
        </p>
        <a class="btn bg-gradient-red" href="book-a-demo">
          Book a Demo
          <i class="bi bi-arrow-right"></i>
        </a>
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

    <!-- Scroll Top -->
    <a href="#" id="scroll-top"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader">
      <div class="preloader-ring"></div>
    </div>
  </main>
</body>


<!-- Vendor JS -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>

<script>
  // Preloader
  window.addEventListener('load', () => {
    const pl = document.getElementById('preloader');
    if (pl) {
      pl.classList.add('done');
    }
  });

  // AOS
  AOS.init({
    duration: 650,
    easing: 'ease-out-quad',
    once: true,
    offset: 60
  });

  // Scroll top
  const scrollBtn = document.getElementById('scroll-top');
  window.addEventListener('scroll', () => {
    scrollBtn.classList.toggle('active', window.scrollY > 400);
  });

  scrollBtn.addEventListener('click', e => {
    e.preventDefault();
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });

  // Header shrink on scroll
  const header = document.getElementById('header');
  window.addEventListener('scroll', () => {
    if (window.scrollY > 400) {
      header.classList.add("scrolled")
    } else {
      header.classList.remove("scrolled")
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
</script>

<script>
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