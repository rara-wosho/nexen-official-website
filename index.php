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
    }
  } catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
  }

  return '';
}


$heroTitle = getContent("hero", "title");
$herroTitleArr = explode(" ", $heroTitle);

$title

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
  <header id="header" class="outliner">
    <div class="max-w-wrapper outliner w-100 d-flex align-items-center mx-auto">
      <a href="#" class="logo outliner">
        <img src="assets/png/nexen-logo.png" alt="nexen-logo"> <!-- DO NOT TOUCH, TAGUON NAKO NI -->
      </a>

      <!-- Desktop Dropdown Nav -->
      <div class="links-container">
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
          <button>About</button>
          <div class="dropdown-content">
            <a href="#" style="--i:1">My Story</a>
            <a href="#" style="--i:2">Careers</a>
            <a href="#" style="--i:3">myPortal</a>
          </div>
        </div>
        <div class="talk-to-us ms-auto">
          <button onclick="window.location.href='talk-to-us.php'">Contact Us</button>
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
          <a href="#about" class="btn-primary-hero">
            Get Started
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
              <path d="M3 8h10M9 5l3 3-3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </a>
          <a href="#services" class="btn-ghost-hero">
            Book a Demo
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
      <div class="wrapper row py-4 w-100">
        <div class="hero-lower-tab col-8 col-md-4 py-3">
          <p class="text-primary text-center">Clients</p>
          <h3 class="text-secondary-foreground text-center fw-bold">30+</h3>
        </div>
        <div class="hero-lower-tab col-8 col-md-4 py-3">
          <p class="text-primary text-center">Projects</p>
          <h3 class="text-secondary-foreground text-center fw-bold">30+</h3>
        </div>
        <div class="hero-lower-tab col-8 col-md-4 py-3">
          <p class="text-primary text-center">5-Star Reviews</p>
          <h3 class="text-secondary-foreground text-center fw-bold">30+</h3>
        </div>
      </div>
    </div>

    <!-- ══ SERVICES ══════════════════════════════════════════ -->
    <section id="services" class="bg-background outliner">
      <div class="services-glow"></div>
      <img src="./assets/png/prism.png" alt="" class="prisms">
      <div class="max-w-wrapper mx-auto outliner">
        <div class="section-header" data-aos="fade-up">
          <h2 class="montserrat fs-1 text-secondary-foreground">Services We <span class="text-primary">Offer</span></h2>

          <p class="inter text-muted-foreground services-description">NEXEN delivers innovative tech solutions to streamline operations, boost
            efficiency, and drive business growth.</p>
        </div>

        <div class="row gy-4 service-card-wrapper">
          <?php
          $services = [
            ['icon' => 'bi-server',              'key' => ''],
            ['icon' => 'bi-distribute-horizontal', 'key' => '1'],
            ['icon' => 'bi-window-fullscreen',   'key' => '2'],
            ['icon' => 'bi-chat-dots-fill',      'key' => '3'],
            ['icon' => 'bi-cloud-check-fill',    'key' => '4'],
            ['icon' => 'bi-lightbulb-fill',      'key' => '5'],
          ];
          $delays = [100, 200, 300, 400, 500, 600];
          foreach ($services as $i => $s):
            $k = $s['key'];
            $evenIndex = floor($i / 2) + 1;
          ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo $delays[$i]; ?>">
              <div class="service-card <?php if ($i % 2 == 1) echo 'card-even' ?>">
                <div class="service-icon"><i class="bi fs-6 <?php echo $s['icon']; ?>"></i></div>
                <p class="service-content-description text-muted-foreground"><?php echo getContent('services', 'description' . $k); ?></p>
                <h2><a class="text-secondary-foreground mt-auto" href="#"><?php echo getContent('services', 'title' . $k); ?></a></h2>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="section-header" data-aos="fade-up">
          <h2 class="montserrat fs-1 text-secondary-foreground">With Us You Can <span class="text-primary">Trust</span></h2>
        </div>

        <div class="services-lower-cards-wrapper row">
          <div data-aos="fade-up" class="col services-lower-cardd">
            <h1 class="text-secondary-foreground mb-3">2010</h1>
            <div class="d-flex flex-column">
              <p class="fs-5 text-secondary-foreground fw-light lower-cards-desc">Year of establishment</p>
              <small class="text-muted-light">Lorem ipsum dolor sit.</small>
              <div class="mt-5 d-flex align-items-end">
                <div style="transform:translateY(22px); z-index:20;" class="bg-white p-4 rounded-circle"></div>
                <div style="transform:translateY(22px); z-index:20;" class="bg-white p-4 rounded-circle"></div>
                <div style="transform:translateY(22px); z-index:20;" class="bg-white p-4 rounded-circle"></div>
              </div>
            </div>
          </div>
          <div data-aos-delay="200" data-aos="fade-up" class="col services-lower-cardd">
            <h1 class="text-secondary-foreground mb-3">02</h1>
            <div class="d-flex flex-column">
              <p class="fs-5 text-secondary-foreground fw-light lower-cards-desc">Projects are launched</p>
              <small class="text-muted-light">Lorem ipsum dolor sit.</small>
              <div class="mt-5 d-flex align-items-end">
                <div style="transform:translateY(22px); z-index:20;" class="bg-white p-4 rounded-circle"></div>
                <div style="transform:translateY(22px); z-index:20;" class="bg-white p-4 rounded-circle"></div>
                <div style="transform:translateY(22px); z-index:20;" class="bg-white p-4 rounded-circle"></div>
              </div>
            </div>
          </div>
          <div data-aos="fade-up" class="col services-lower-cardd">
            <h1 class="text-secondary-foreground mb-3">70</h1>
            <div class="d-flex flex-column">
              <p class="fs-5 text-secondary-foreground fw-light lower-cards-desc">Clients are satisfied</p>
              <small class="text-muted-light">Lorem ipsum dolor sit.</small>
              <div class="mt-5 d-flex align-items-end">
                <div style="transform:translateY(22px); z-index:20;" class="bg-white p-4 rounded-circle"></div>
                <div style="transform:translateY(22px); z-index:20;" class="bg-white p-4 rounded-circle"></div>
                <div style="transform:translateY(22px); z-index:20;" class="bg-white p-4 rounded-circle"></div>
              </div>
            </div>
          </div>
          <div data-aos-delay="200" data-aos="fade-up" class="col services-lower-cardd">
            <h1 class="text-secondary-foreground mb-3">12</h1>
            <div class="d-flex flex-column">
              <p class="fs-5 text-secondary-foreground fw-light lower-cards-desc">Projects in work</p>
              <small class="text-muted-light">Lorem ipsum dolor sit.</small>
              <div class="mt-5 d-flex align-items-end">
                <div style="transform:translateY(22px); z-index:20;" class="bg-white p-4 rounded-circle"></div>
                <div style="transform:translateY(22px); z-index:20;" class="bg-white p-4 rounded-circle"></div>
                <div style="transform:translateY(22px); z-index:20;" class="bg-white p-4 rounded-circle"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- PEOPLE WE ARE WORKING WITH  -->
    <section id="working-with" class="py-5">
      <p class="text-center fs-5 fw-semibold text-secondary-foreground"><span class="text-primary">Meet</span> the People We are Working With</p>

      <div class="d-flex align-items-center justify-content-center gap-4">
        <div class="p-4 border rounded-circle"></div>
        <div class="p-4 border rounded-circle"></div>
        <div class="p-4 border rounded-circle"></div>
        <div class="p-4 border rounded-circle"></div>
        <div class="p-4 border rounded-circle"></div>
        <div class="p-4 border rounded-circle"></div>
      </div>
    </section>

    <!-- BOOK A DEMO SECTION  -->
    <section id="book-a-demo" class="book-a-demo outliner">
      <div class="row outliner max-w-wrapper mx-auto">
        <div class="col-12 col-md-5 outliner">
          <p class="text-muted-foreground">Get in Touch</p>
          <h2 class="urbanist mb-3"><span>Let’s Explore the</span> <br>
            <span class="text-red-gradient">Nexen System Demo</span>
          </h2>

          <p class="urbanist text-muted-foreground">Experience how Nexen streamlines workflows, boosts productivity,
            and delivers efficient digital solutions- built to support your business
            and turn ideas into reality.</p>
          <ul class="mt-4 list-unstyled">
            <li class="fw-thin mb-2 text-muted-foreground urbanist d-flex gap-3"><i class="bi bi-check-circle-fill text-danger"></i><span> User-Friendly Interface</span></li>
            <li class="fw-thin mb-2 text-muted-foreground urbanist d-flex gap-3"><i class="bi bi-check-circle-fill text-danger"></i><span> Smart System Features</span></li>
            <li class="fw-thin mb-2 text-muted-foreground urbanist d-flex gap-3"><i class="bi bi-check-circle-fill text-danger"></i><span> Custom Web Solutions</span></li>
            <li class="fw-thin mb-2 text-muted-foreground urbanist d-flex gap-3"><i class="bi bi-check-circle-fill text-danger"></i><span> Reliable Technical Support</span></li>
          </ul>
        </div>
        <div class="col-12 col-md-7 outliner p-4">
          <div class="row mb-4 px-1 no-gutter">
            <div class="col-12 col-md-6 d-flex flex-column px-2">
              <label for="org" class="text-muted-foreground fs-7 mb-2">
                Organization
              </label>
              <input type="text" placeholder="Enter your company or organization name" class="modern-input" id="org">
            </div>
            <div class="col-12 col-md-6 d-flex flex-column px-2">
              <label for="org" class="text-muted-foreground fs-7 mb-2">
                Number of employees</label>
              <input type="text" placeholder="Enter number of employees" class="modern-input" id="org">
            </div>
          </div>
          <div class="row mb-4 px-1 no-gutter">
            <div class="col-12 col-md-6 d-flex flex-column px-2">
              <label for="org" class="text-muted-foreground fs-7 mb-2">
                Organization
              </label>
              <input type="text" placeholder="Enter your company or organization name" class="modern-input" id="org">
            </div>
            <div class="col-12 col-md-6 d-flex flex-column px-2">
              <label for="org" class="text-muted-foreground fs-7 mb-2">
                Number of employees</label>
              <input type="text" placeholder="Enter number of employees" class="modern-input" id="org">
            </div>
          </div>
          <div class="row mb-4 px-1 no-gutter">
            <div class="col-12 col-md-6 d-flex flex-column px-2">
              <label for="org" class="text-muted-foreground fs-7 mb-2">
                Organization
              </label>
              <input type="text" placeholder="Enter your company or organization name" class="modern-input" id="org">
            </div>
            <div class="col-12 col-md-6 d-flex flex-column px-2">
              <label for="org" class="text-muted-foreground fs-7 mb-2">
                Number of employees</label>
              <input type="text" placeholder="Enter number of employees" class="modern-input" id="org">
            </div>
          </div>

          <div class="row mb-4 px-1">
            <div class="col d-flex flex-column px-2">
              <label for="org" class="text-muted-foreground fs-7 mb-2">
                Number of employees</label>
              <input type="text" placeholder="Enter number of employees" class="modern-input" id="org">
            </div>
          </div>

          <button class="bg-gradient-red rounded-3 outline-0 border-0 px-5 text-secondary-foreground urbanist py-2">Book Demo</button>


        </div>
      </div>
    </section>

    <!-- LOWER CTA BANNER  -->
    <section id="cta-banner-section" data-aos="fade-up">
      <div class="cta-banner" id="cta-banner">
        <h1 class="montserrat">Start Your Real Journey Today</h1>
        <p>
          For startups, enterprises, and growing businesses, NEXEN
          Innovation Technologies delivers smart, future-ready solutions.
          Innovate faster. Lead with confidence.
        </p>
        <a class="btn bg-gradient-red" href="#">
          Get Started
          <span class="arrow">→</span>
        </a>
      </div>
    </section>


    <!-- FOOTER  -->
    <footer>
      <div class="glow-4"></div>
      <div class="upper-footer max-w-wrapper mx-auto">
        <div class="row">
          <div class="col-12 col-sm-4">
            <img src="assets/png/nexen-logo.png" class="w-25" alt="">
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
              <i class="bi bi-instagram"></i>
            </div>
            <div class="footer-icon-wrapper">
              <i class="bi bi-facebook"></i>
            </div>
            <div class="footer-icon-wrapper">
              <i class="bi bi-instagram"></i>
            </div>
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
          header.style.background = 'rgba(10, 10, 10, 0.9)';
          header.style.borderBottom = '1px solid rgba(240,240,240,0.1)';
          header.style.backdropFilter = "blur(10px)";
        } else {
          header.style.background = 'transparent';
          header.style.backdropFilter = "blur(0)";
          header.style.borderBottom = '1px solid transparent';
        }
      });
    </script>

</body>

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