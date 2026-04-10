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
  <header id="header">
    <div class="container-fluid pe-1">
      <a href="#" class="logo">
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

      <div class="hero-scroll">
        <span>Scroll</span>
        <div class="scroll-line"></div>
      </div>

      <img class="abstract-left" src="./assets/png/abstract-left.png" />
      <img class="abstract-right" src="./assets/png/abstract-right.png" />

      <!-- Radial gradient top  -->
      <div class="hero-radial-top"></div>

    </section>

    <!-- RATINGS & CLIENTS -->
    <div class="w-100 bg-background d-flex align-items-center justify-content-center hero-lower-tabs">

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
    <section id="services" class="nx-section bg-background">

      <div class="services-glow"></div>
      <img src="./assets/png/prism.png" alt="" class="prisms">
      <div class="container">
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
                <div class="outliner p-3 rounded-circle"></div>
                <div class="outliner p-3 rounded-circle"></div>
                <div class="outliner p-3 rounded-circle"></div>
              </div>
            </div>
          </div>
          <div data-aos-delay="200" data-aos="fade-up" class="col services-lower-cardd">
            <h1 class="text-secondary-foreground mb-3">02</h1>
            <div class="d-flex flex-column">
              <p class="fs-5 text-secondary-foreground fw-light lower-cards-desc">Projects are launched</p>
              <small class="text-muted-light">Lorem ipsum dolor sit.</small>
              <div class="mt-5 d-flex align-items-end">
                <div class="outliner p-3 rounded-circle"></div>
                <div class="outliner p-3 rounded-circle"></div>
                <div class="outliner p-3 rounded-circle"></div>
              </div>
            </div>
          </div>
          <div data-aos="fade-up" class="col services-lower-cardd">
            <h1 class="text-secondary-foreground mb-3">70</h1>
            <div class="d-flex flex-column">
              <p class="fs-5 text-secondary-foreground fw-light lower-cards-desc">Clients are satisfied</p>
              <small class="text-muted-light">Lorem ipsum dolor sit.</small>
              <div class="mt-5 d-flex align-items-end">
                <div class="outliner p-3 rounded-circle"></div>
                <div class="outliner p-3 rounded-circle"></div>
                <div class="outliner p-3 rounded-circle"></div>
              </div>
            </div>
          </div>
          <div data-aos-delay="200" data-aos="fade-up" class="col services-lower-cardd">
            <h1 class="text-secondary-foreground mb-3">12</h1>
            <div class="d-flex flex-column">
              <p class="fs-5 text-secondary-foreground fw-light lower-cards-desc">Projects in work</p>
              <small class="text-muted-light">Lorem ipsum dolor sit.</small>
              <div class="mt-5 d-flex align-items-end">
                <div class="outliner p-3 rounded-circle"></div>
                <div class="outliner p-3 rounded-circle"></div>
                <div class="outliner p-3 rounded-circle"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

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

    <section id="cta-banner-section" data-aos="fade-up">
      <div class="cta-banner" id="cta-banner">
        <h1 class="montserrat">Start Your Real Journey Today</h1>
        <p>
          For startups, enterprises, and growing businesses, NEXEN
          Innovation Technologies delivers smart, future-ready solutions.
          Innovate faster. Lead with confidence.
        </p>
        <a class="btn" href="#">
          Get Started
          <span class="arrow">→</span>
        </a>
      </div>
    </section>

    <!-- ══ ABOUT ════════════════════════════════════════════ -->
    <section id="about" class="nx-section bg-surface">
      <div class="container">

        <div class="section-header" data-aos="fade-up">
          <span class="section-eyebrow">Who We Are</span>
          <h2 class="section-heading">About Us</h2>
          <span class="section-rule"></span>
        </div>

        <div class="row gy-4 align-items-center">
          <div class="col-lg-6" data-aos="fade-right" data-aos-delay="100">
            <div class="about-video-wrap">
              <video src="<?php echo getContent('about', 'video'); ?>" width="100%" height="340" autoplay muted controls></video>
            </div>
          </div>
          <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
            <div class="about-content">
              <h3><?php echo getContent('about', 'title'); ?></h3>
              <p class="lead-text"><?php echo getContent('about', 'description'); ?></p>
              <ul class="about-checklist">
                <li>
                  <span class="check-icon"><i class="bi bi-check2"></i></span>
                  <span><?php echo getContent('about', 'list'); ?></span>
                </li>
                <li>
                  <span class="check-icon"><i class="bi bi-check2"></i></span>
                  <span><?php echo getContent('about', 'list1'); ?></span>
                </li>
                <li>
                  <span class="check-icon"><i class="bi bi-check2"></i></span>
                  <span><?php echo getContent('about', 'list2'); ?></span>
                </li>
              </ul>
              <p class="about-footer-text"><?php echo getContent('about', 'footer'); ?></p>
            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- ══ WHY US ════════════════════════════════════════════ -->
    <section id="why-us" class="nx-section bg-white">
      <div class="container">

        <div class="section-header" data-aos="fade-up">
          <span class="section-eyebrow">Our Advantage</span>
          <h2 class="section-heading">Why Choose NEXEN</h2>
          <span class="section-rule"></span>
        </div>

        <div class="row gy-4">
          <div class="col-md-6" data-aos="fade-right" data-aos-delay="100">
            <div class="why-card">
              <div class="why-card-img">
                <img src="<?php echo getContent('why-us', 'card_img'); ?>" alt="">
                <div class="why-card-icon"><i class="bi bi-pin-angle"></i></div>
              </div>
              <div class="why-card-body">
                <h3><a href="#"><?php echo getContent('why-us', 'card_title'); ?></a></h3>
                <p><?php echo getContent('why-us', 'card_description'); ?></p>
              </div>
            </div>
          </div>
          <div class="col-md-6" data-aos="fade-left" data-aos-delay="200">
            <div class="why-card">
              <div class="why-card-img">
                <img src="<?php echo getContent('why-us', 'card_img1'); ?>" alt="">
                <div class="why-card-icon"><i class="bi bi-brightness-high"></i></div>
              </div>
              <div class="why-card-body">
                <h3><a href="#"><?php echo getContent('why-us', 'card_title1'); ?></a></h3>
                <p><?php echo getContent('why-us', 'card_description1'); ?></p>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>


    <!-- ══ PROJECTS ══════════════════════════════════════════ -->
    <section id="projects" class="nx-section bg-navy">
      <div class="container">

        <div class="section-header" data-aos="fade-up">
          <span class="section-eyebrow" style="color:rgba(255,255,255,0.5);">Our Work</span>
          <h2 class="section-heading light">Projects</h2>
          <span class="section-rule"></span>
        </div>

        <div class="projects-wrap" data-aos="zoom-in" data-aos-delay="100">
          <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
              <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
              <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="1" aria-label="Slide 2"></button>
            </div>
            <div class="carousel-inner">
              <div class="carousel-item active" data-bs-interval="3000">
                <div class="carousel-img-wrapper">
                  <img src="<?php echo getContent('projects', 'img'); ?>" class="d-block" alt="Slide 1">
                </div>
              </div>
              <div class="carousel-item" data-bs-interval="3000">
                <div class="carousel-img-wrapper">
                  <img src="assets/img/medipro.jpg" class="d-block" alt="Slide 2">
                </div>
              </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
        </div>

      </div>
    </section>

    <!-- ══ BLOG ══════════════════════════════════════════════ -->
    <section id="blog" class="nx-section bg-surface">
      <div class="container">

        <div class="section-header" data-aos="fade-up">
          <span class="section-eyebrow">Latest Updates</span>
          <h2 class="section-heading">Blog &amp; Events</h2>
          <span class="section-rule"></span>
        </div>

        <div class="row gy-4">

          <!-- Sidebar Blogs -->
          <div class="col-lg-4" data-aos="fade-right" data-aos-delay="100">
            <div class="d-flex flex-column gap-4 h-100">

              <div class="blog-card">
                <div class="blog-card-img">
                  <img src="<?php echo getContent('blog', 'img'); ?>" alt="Blog 1">
                </div>
                <div class="blog-card-body">
                  <p class="blog-date"><i class="bi bi-calendar2-event"></i><?php echo getContent('blog', 'date'); ?></p>
                  <h5><a href="https://www.facebook.com/Nexen.ph/posts/pfbid0ij5LmrPVWTHxkLrFXVLYjoZ1pnqF6hNx399daWERG5LBo28nBtaPaXipP6YZwcojl" target="_blank"><?php echo getContent('blog', 'title'); ?></a></h5>
                  <p><?php echo getContent('blog', 'description'); ?></p>
                  <a href="https://www.facebook.com/Nexen.ph/posts/pfbid0ij5LmrPVWTHxkLrFXVLYjoZ1pnqF6hNx399daWERG5LBo28nBtaPaXipP6YZwcojl" target="_blank" class="read-more-link">Read More <i class="bi bi-arrow-right"></i></a>
                </div>
              </div>

              <div class="blog-card">
                <div class="blog-card-img">
                  <img src="<?php echo getContent('blog', 'img1'); ?>" alt="Blog 2">
                </div>
                <div class="blog-card-body">
                  <p class="blog-date"><i class="bi bi-calendar2-event"></i><?php echo getContent('blog', 'date1'); ?></p>
                  <h5><a href="https://www.facebook.com/Nexen.ph/posts/pfbid0LgszzbLA2r4359ZVv3eauGw1AzMs5V3cRNfHta3CKNsRV1oU39sgRekdTg4Qgod8l"><?php echo getContent('blog', 'title1'); ?></a></h5>
                  <p><?php echo getContent('blog', 'description1'); ?></p>
                  <a href="https://www.facebook.com/Nexen.ph/posts/pfbid0LgszzbLA2r4359ZVv3eauGw1AzMs5V3cRNfHta3CKNsRV1oU39sgRekdTg4Qgod8l" class="read-more-link">Read More <i class="bi bi-arrow-right"></i></a>
                </div>
              </div>

            </div>
          </div>

          <!-- Main Blog -->
          <div class="col-lg-8" data-aos="fade-left" data-aos-delay="200">
            <div class="blog-main-card">
              <div class="blog-main-img">
                <img src="<?php echo getContent('blog', 'img2'); ?>" alt="Main Blog">
              </div>
              <div class="blog-main-body">
                <p class="blog-date"><i class="bi bi-calendar2-event"></i><?php echo getContent('blog', 'date2'); ?></p>
                <h2><a href="https://www.facebook.com/share/p/12GLkdSMobU/"><?php echo getContent('blog', 'title2'); ?></a></h2>
                <p><?php echo getContent('blog', 'description2'); ?></p>
                <div style="margin-top: 20px;">
                  <a href="https://www.facebook.com/share/p/12GLkdSMobU/" class="read-more-link" style="font-size:14px;">
                    Read Full Article <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- ══ CONTACT ════════════════════════════════════════════ -->
    <section id="contact" class="nx-section bg-white">
      <div class="container">

        <div class="section-header" data-aos="fade-up">
          <span class="section-eyebrow">Get In Touch</span>
          <h2 class="section-heading">Contact Us</h2>
          <span class="section-rule"></span>
        </div>

        <div class="row gy-4" data-aos="fade-up" data-aos-delay="100">
          <div class="col-lg-4">
            <div class="contact-item">
              <div class="contact-icon"><i class="bi bi-geo-alt"></i></div>
              <h3><?php echo getContent('contact', 'title'); ?></h3>
              <p><?php echo getContent('contact', 'description'); ?></p>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="contact-item">
              <div class="contact-icon"><i class="bi bi-telephone"></i></div>
              <h3><?php echo getContent('contact', 'title1'); ?></h3>
              <p><?php echo getContent('contact', 'description1'); ?></p>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="contact-item">
              <div class="contact-icon"><i class="bi bi-envelope"></i></div>
              <h3><?php echo getContent('contact', 'title2'); ?></h3>
              <p><?php echo getContent('contact', 'description2'); ?></p>
            </div>
          </div>
        </div>

      </div>
    </section>

  </main>

  <!-- ══ FOOTER ════════════════════════════════════════════ -->
  <footer id="footer">
    <div class="container">
      <div class="footer-inner">
        <div class="footer-logo">NEXEN</div>
        <p class="footer-tagline">"Your NEXT Business ENGINE"</p>
        <div class="footer-socials">
          <a href="https://www.nexen.com.ph/" title="Website"><i class="bi bi-globe-asia-australia"></i></a>
          <a href="https://www.facebook.com/Nexen.ph" title="Facebook"><i class="bi bi-facebook"></i></a>
          <a href="https://www.instagram.com/nexen.ph/" title="Instagram"><i class="bi bi-instagram"></i></a>
          <a href="https://www.youtube.com/@nexenph" title="YouTube"><i class="bi bi-youtube"></i></a>
          <a href="https://ph.linkedin.com/company/nexen-innovation-technologies-inc" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
        </div>
        <div class="footer-divider"></div>
        <p class="footer-copy">
          Copyright &copy; <strong>NEXEN Innovation Technologies Inc.</strong> All Rights Reserved.
        </p>
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
        header.style.background = 'rgb(6, 6, 6)';
      } else {
        header.style.background = 'transparent';
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