<?php
require_once "db_connect.php";

// Fetch company info
$company_info_query = "SELECT * FROM company_info WHERE id = 1";
$company_info_result = $connection->query($company_info_query);
$company_info = $company_info_result->fetch_assoc();

// Fetch services
$services_query = "SELECT * FROM services WHERE active = 1 ORDER BY display_order ASC";
$services_result = $connection->query($services_query);

// Fetch projects
$projects_query = "SELECT p.*, i.file_path, i.file_name, i.alt_text 
                  FROM projects p 
                  LEFT JOIN images i ON p.id = i.related_id AND i.type = 'project' 
                  WHERE p.active = 1 
                  ORDER BY p.display_order ASC";
$projects_result = $connection->query($projects_query);

// Fetch hero video
$hero_video_query = "SELECT * FROM videos WHERE type = 'hero' AND active = 1 ORDER BY display_order ASC LIMIT 1";
$hero_video_result = $connection->query($hero_video_query);
$hero_video = $hero_video_result->fetch_assoc();

// Fetch about video
$about_video_query = "SELECT * FROM videos WHERE type = 'about' AND active = 1 ORDER BY display_order ASC LIMIT 1";
$about_video_result = $connection->query($about_video_query);
$about_video = $about_video_result->fetch_assoc();

// Fetch logo
$logo_query = "SELECT * FROM images WHERE type = 'logo' AND active = 1 ORDER BY display_order ASC LIMIT 1";
$logo_result = $connection->query($logo_query);
$logo = $logo_result->fetch_assoc();

// Fetch social media links
$social_media_query = "SELECT * FROM social_media WHERE active = 1 ORDER BY display_order ASC";
$social_media_result = $connection->query($social_media_query);

// Fetch why us images
$why_us_query = "SELECT * FROM images WHERE type = 'why_us' AND active = 1 ORDER BY display_order ASC LIMIT 2";
$why_us_result = $connection->query($why_us_query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?php echo htmlspecialchars($company_info['company_name']); ?></title>
  <meta name="description" content="<?php echo htmlspecialchars($company_info['tagline']); ?>">
  <meta name="keywords" content="IT solutions, software development, NEXEN, payroll outsourcing, website development">

  <!-- Favicons -->
  <link href="assets/img/logo.jpg" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnectionect">
  <link href="https://fonts.gstatic.com" rel="preconnectionect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid position-relative d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo d-flex align-items-center">
        <!-- Logo from database -->
        <img src="<?php echo htmlspecialchars($logo['file_path'] . $logo['file_name']); ?>" alt="<?php echo htmlspecialchars($logo['alt_text']); ?>">
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Home</a></li>
          <li><a href="#about" class="active">About</a></li>
          <li><a href="#why-us" class="active">Why Us</a></li>
          <li><a href="#services" class="active">Services</a></li>
          <li><a href="#projects" class="active">Projects</a></li>
          <li><a href="#contact" class="active">Contact</a></li>
        </ul>
        <a href="login.php" class="login bi-person-circle"> Login</a>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">
      <!-- Hero video from database -->
      <video src="<?php echo htmlspecialchars($hero_video['file_path'] . $hero_video['file_name']); ?>" autoplay muted loop></video>

      <div class="container text-center" data-aos="zoom-out" data-aos-delay="150">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <h2 style="color: rgb(218, 3, 3); font-size: 40px; font-weight: bold;">WELCOME TO <?php echo strtoupper(htmlspecialchars($company_info['company_name'])); ?></h2>
            <p style="color: rgb(77, 77, 229); font-size: 25px; font-style: italic;"><?php echo htmlspecialchars($company_info['tagline']); ?></p>
            <a href="#about" class="btn-get-started">Get Started</a>
          </div>
        </div>
      </div>
    </section><!-- /Hero Section -->
    
    <!-- About Section-->
    <section id="about" class="about section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>About Us</h2>
        <div class="vert-line"></div>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">
          <div class="col-lg-6">
            <!-- About video from database -->
            <video src="<?php echo htmlspecialchars($about_video['file_path'] . $about_video['file_name']); ?>" type="video/mp4" width="100%" height="300" class="video" autoplay muted controls></video>
          </div>
          <div class="col-lg-6 content">
            <h3>Innovating for a Smarter Future</h3>
              <p class="fst-italic">
                <?php echo htmlspecialchars($company_info['about_description']); ?>
              </p>
              <ul>
                <li><i class="bi bi-check2-all"></i> <span>Expert IT services tailored to enhance business efficiency.</span></li>
                <li><i class="bi bi-check2-all"></i> <span>Innovative software solutions for seamless HR management.</span></li>
                <li><i class="bi bi-check2-all"></i> <span>Reliable and secure automation to drive digital transformation.</span></li>
              </ul>
              <p>
                Established in <?php echo htmlspecialchars($company_info['established_year']); ?>, our mission is to empower businesses with technology that simplifies processes, improves productivity, and ensures long-term success.
              </p>
          </div>
        </div>

      </div>

    </section><!-- /About Section -->
    
    <!-- Why Us Section -->
    <section id="why-us" class="why-us section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Why Us</h2>
        <div class="vert-line"></div>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4" data-aos="fade-right" data-aos-delay="100">
          <?php
          $why_us_counter = 0;
          $why_us_icons = ['bi-hdd-stack', 'bi-brightness-high'];
          $why_us_titles = ['Our Mission', 'Our Vision'];
          $why_us_contents = [$company_info['mission'], $company_info['vision']];
          
          while($why_us_image = $why_us_result->fetch_assoc()) {
          ?>
          <div class="col-md-6" <?php if($why_us_counter > 0) { echo 'data-aos="fade-left" data-aos-delay="200"'; } ?>>
            <div class="card">
              <div class="img">
                <img src="<?php echo htmlspecialchars($why_us_image['file_path'] . $why_us_image['file_name']); ?>" alt="<?php echo htmlspecialchars($why_us_image['alt_text']); ?>" class="img-fluid">
                <div class="icon"><i class="<?php echo $why_us_icons[$why_us_counter]; ?>"></i></div>
              </div>
              <h2 class="title"><a href="#" class="stretched-link"><?php echo $why_us_titles[$why_us_counter]; ?></a></h2>
              <p>
                <?php echo htmlspecialchars($why_us_contents[$why_us_counter]); ?>
              </p>
            </div>
          </div><!-- End Card Item -->
          <?php
            $why_us_counter++;
          }
          ?>
        </div>

      </div>

    </section><!-- /Why Us Section -->

    <!-- Services Section -->
    <section id="services" class="services section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Services</h2>
        <div class="vert-line"></div>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">
          <?php
          $service_counter = 0;
          $aos_delays = [100, 200, 300, 400, 500, 600];
          $aos_directions = ['fade-down-right', 'fade-down', 'fade-down-left', 'fade-up-right', 'fade-up', 'fade-up-left'];
          
          while($service = $services_result->fetch_assoc()) {
          ?>
          <div class="col-lg-4 col-md-6" data-aos="<?php echo $aos_directions[$service_counter]; ?>" data-aos-delay="<?php echo $aos_delays[$service_counter]; ?>">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="<?php echo htmlspecialchars($service['icon']); ?>"></i>
              </div>
              <a href="#" class="stretched-link">
                <h3><?php echo htmlspecialchars($service['title']); ?></h3>
              </a>
              <p><?php echo htmlspecialchars($service['description']); ?></p>
            </div>
          </div><!-- End Service Item -->
          <?php
            $service_counter++;
          }
          ?>
        </div>

      </div>

    </section><!-- /Services Section -->

    <!-- Projects Section / Carousel -->
    <section id="projects" class="projects section light-background">
      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Projects</h2>
        <div class="vert-line-projects"></div>
      </div><!-- End Section Title -->

      <div class="container mt-5" data-aos="zoom-in">
        <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
            <!-- Indicators -->
            <div class="carousel-indicators">
                <?php
                $projects_count = $projects_result->num_rows;
                $projects_array = [];
                
                while($project = $projects_result->fetch_assoc()) {
                    $projects_array[] = $project;
                }
                
                for($i = 0; $i < $projects_count; $i++) {
                ?>
                <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="<?php echo $i; ?>" <?php if($i == 0) echo 'class="active" aria-current="true"'; ?> aria-label="Slide <?php echo $i+1; ?>"></button>
                <?php
                }
                ?>
            </div>
    
            <!-- Carousel Items -->
            <div class="carousel-inner">
                <?php
                for($i = 0; $i < $projects_count; $i++) {
                ?>
                <div class="carousel-item <?php if($i == 0) echo 'active'; ?>" data-bs-interval="3000">
                    <img src="<?php echo htmlspecialchars($projects_array[$i]['file_path'] . $projects_array[$i]['file_name']); ?>" class="d-block w-100" alt="<?php echo htmlspecialchars($projects_array[$i]['alt_text']); ?>">
                    <div class="carousel-caption d-none d-md-block">
                        <h4><?php echo htmlspecialchars($projects_array[$i]['title']); ?></h4>
                        <p><?php echo htmlspecialchars($projects_array[$i]['short_description']); ?></p>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>
    
            <!-- Controls -->
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
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Contact Us</h2>
        <div class="vert-line"></div>
      </div><!-- End Section Title -->

      <div class="container" data-aos="zoom-in" data-aos-delay="100">

        <div class="row gy-4" data-aos="zoom-in" data-aos-delay="200">

          <div class="col-lg-4">
            <div class="info-item d-flex flex-column justify-content-center align-items-center text-center">
              <i class="bi bi-geo-alt"></i>
              <h3>Address</h3>
              <p><?php echo htmlspecialchars($company_info['address']); ?></p>
            </div>
          </div><!-- End Info Item -->

          <div class="col-lg-4">
            <div class="info-item d-flex flex-column justify-content-center align-items-center info-item-borders">
              <i class="bi bi-telephone"></i>
              <h3>Call Us</h3>
              <p><?php echo htmlspecialchars($company_info['phone1']); ?></p>
              <p><?php echo htmlspecialchars($company_info['phone2']); ?></p>
            </div>
          </div><!-- End Info Item -->

          <div class="col-lg-4">
            <div class="info-item d-flex flex-column justify-content-center align-items-center">
              <i class="bi bi-envelope"></i>
              <h3>Email Us</h3>
              <p><?php echo htmlspecialchars($company_info['email']); ?></p>
            </div>
          </div><!-- End Info Item -->

        </div>

        <form action="forms/contact.php" method="post" class="php-email-form" data-aos="flip-up" data-aos-delay="300">
          <!-- Contact form fields here if needed -->
        </form><!-- End Contact Form -->

      </div>

    </section><!-- /Contact Section -->

  </main>

  <footer id="footer" class="footer dark-background">
    <div class="container">
      <h3 class="sitename"><?php echo strtoupper(explode(' ', $company_info['company_name'])[0]); ?></h3>
      <p><?php echo htmlspecialchars($company_info['tagline']); ?></p>
      <div class="social-links d-flex justify-content-center">
        <?php
        while($social = $social_media_result->fetch_assoc()) {
        ?>
        <a href="<?php echo htmlspecialchars($social['url']); ?>"><i class="<?php echo htmlspecialchars($social['icon']); ?>"></i></a>
        <?php
        }
        ?>
      </div>
      <div class="container">
        <div class="copyright">
          <span>Copyright</span> <strong class="px-1 sitename"><?php echo strtoupper(explode(' ', $company_info['company_name'])[0]); ?></strong> <span>All Rights Reserved</span>
        </div>
        <div class="credits">
          Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
<?php
// Close the database connectionection
$connection->close();
?>