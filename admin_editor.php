<?php
session_start();
require_once 'db_connect.php';

// Check if user is logged in as admin
if (!isset($_SESSION['username'])) {
  // Redirect to login page if not logged in or not admin
  header("Location: login.php");
  exit();
}

// Function to fetch all content from the database
function getAllContent()
{
  global $connection;

  try {
    $query = "SELECT id, section_name, content_key, content FROM content order by id asc";
    $stmt = $connection->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
  }
}

// Function to update content in the database
function updateContent($id, $content)
{
  global $connection;

  try {
    $query = "UPDATE content SET content = :content WHERE id = :id";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
  } catch (PDOException $e) {
    die("Update failed: " . $e->getMessage());
  }
}

// Function to add new content to the database
function addContent($section_name, $content_key, $content)
{
  global $connection;

  try {
    $query = "INSERT INTO content (section_name, content_key, content) VALUES (:section_name, :content_key, :content)";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':section_name', $section_name);
    $stmt->bindParam(':content_key', $content_key);
    $stmt->bindParam(':content', $content);
    return $stmt->execute();
  } catch (PDOException $e) {
    die("Insert failed: " . $e->getMessage());
  }
}

// Function to delete content from the database
function deleteContent($id)
{
  global $connection;

  try {
    $query = "DELETE FROM content WHERE id = :id";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
  } catch (PDOException $e) {
    die("Delete failed: " . $e->getMessage());
  }
}

// Process form submissions
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['action'])) {

    // UPDATE 
    if ($_POST['action'] === 'update' && isset($_POST['id']) && isset($_POST['content'])) {
      if (updateContent($_POST['id'], $_POST['content'])) {
        $message = "Content updated successfully!";
      } else {
        $message = "Failed to update content.";
      }

      // ADD 
    } elseif ($_POST['action'] === 'add' && isset($_POST['section_name']) && isset($_POST['content_key']) && isset($_POST['content'])) {
      if (addContent($_POST['section_name'], $_POST['content_key'], $_POST['content'])) {
        $message = "Content added successfully!";
      } else {
        $message = "Failed to add content.";
      }

      // DELETE 
    } elseif ($_POST['action'] === 'delete' && isset($_POST['id'])) {
      if (deleteContent($_POST['id'])) {
        $message = "Content deleted successfully!";
      } else {
        $message = "Failed to delete content.";
      }
    }
  }
}

// Get content after processing form to reflect changes
$contentItems = getAllContent();

// Group content by sections
$sections = [];
foreach ($contentItems as $item) {
  $sectionName = $item['section_name'];
  if (!isset($sections[$sectionName])) {
    $sections[$sectionName] = [];
  }
  $sections[$sectionName][] = $item;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>NEXEN - Admin</title>

  <!-- Favicons -->
  <link href="assets/img/logo.jpg" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/admin.css">

</head>

<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-header">
      <a href="#" class="sidebar-brand">
        <img src="assets/img/logo-nexen-removebg-preview.png" alt="NEXEN Logo">

      </a>
    </div>

    <ul class="sidebar-menu">
      <!-- <li class="sidebar-menu-item">
        <a href="admin_editor.php" class="sidebar-menu-link">
          <i class="bi bi-speedometer2 sidebar-menu-icon"></i>
          <span>Dashboard</span>
        </a>
      </li> -->
      <div class="sidebar-heading">Pages</div>
      <li class="sidebar-menu-item">
        <a href="teams" class="sidebar-menu-link">
          <i class="bi bi-people sidebar-menu-icon"></i>
          <span>Teams</span>
        </a>
      </li>
      <li class="sidebar-menu-item"><a href="bookings" class="sidebar-menu-link">
          <i class="bi bi-journal-text sidebar-menu-icon"></i>
          <span>Bookings</span>
        </a></li>

      <div class="sidebar-divider"></div>
      <div class="sidebar-heading">Website Sections</div>

      <div class="sidebar-item-wrapper">
        <?php foreach (array_keys($sections) as $sectionName): ?>
          <li class="sidebar-menu-item">
            <a href="#section-<?php echo htmlspecialchars($sectionName); ?>" class="sidebar-menu-link">
              <i class="bi bi-layout-text-window sidebar-menu-icon"></i>
              <span><?php echo htmlspecialchars(ucfirst($sectionName)); ?></span>
            </a>
          </li>
        <?php endforeach; ?>
      </div>

      <div class="sidebar-divider"></div>

      <li class="sidebar-menu-item">
        <a href="#" class="sidebar-menu-link" id="viewWebsiteBtn">
          <i class="bi bi-eye sidebar-menu-icon"></i>
          <span>View Website</span>
        </a>
      </li>
      <li class="sidebar-menu-item">
        <a href="#" class="sidebar-menu-link" data-bs-toggle="modal" data-bs-target="#logoutModal">
          <i class="bi bi-box-arrow-right sidebar-menu-icon"></i>
          <span>Logout</span>
        </a>
      </li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Topbar -->
    <!-- Refers to the scraps.txt file  -->

    <div class="px-4 mt-4">
      <!-- Add this at the top of the page, after the topbar -->
      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?php
          echo htmlspecialchars($_SESSION['error']);
          unset($_SESSION['error']);
          ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?php
          echo htmlspecialchars($_SESSION['success']);
          unset($_SESSION['success']);
          ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>
    </div>

    <!-- Page Content -->
    <div class="dashboard-content">
      <?php if (!empty($message)): ?>
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
          <?php echo htmlspecialchars($message); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <div class="page-heading">
        <h1 class="heading-title">Content Management</h1>
      </div>

      <!-- Section Navigation -->
      <div class="section-nav rounded-4">
        <ul class="section-nav-list">
          <?php foreach (array_keys($sections) as $sectionName): ?>
            <li class="section-nav-item">
              <a href="#section-<?php echo htmlspecialchars($sectionName); ?>" class="section-nav-link">
                <?php echo htmlspecialchars(ucfirst($sectionName)); ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Section Content -->
      <?php foreach ($sections as $sectionName => $sectionItems): ?>
        <div id="section-<?php echo htmlspecialchars($sectionName); ?>" class="card overflow-hidden">

          <!-- CARD HEADER  -->
          <div class="card-header border-0">
            <button class="card-header-btn outline-0 border-0 btn d-flex align-items-center gap-3">
              <div style="width: 30px; height:30px;" class="d-center  flex-shrink-0 border rounded-circle card-header-button">
                <i class="bi bi-plus-lg"></i>
              </div>
              <h4 class="card-header-title mb-0">
                <!-- <i class="bi bi-layout-text-window mr-1"></i> -->
                <?php echo htmlspecialchars(ucfirst($sectionName)); ?> Section
              </h4>
            </button>
            <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#addContentModal"
              onclick="prefillSection('<?php echo htmlspecialchars($sectionName); ?>')">
              <i class="bi bi-plus-circle"></i> Add to <?php echo htmlspecialchars(ucfirst($sectionName)); ?>
            </button>
          </div>

          <!-- CARD BODY  -->
          <div class="card-body shrink">
            <div class="row">
              <?php foreach ($sectionItems as $item): ?>
                <div class="col-lg-6 col-xl-4 mb-4">
                  <div class="content-item rounded-3 overflow-hidden h-100 d-flex flex-column">
                    <div class="content-item-header">
                      <span><?php echo htmlspecialchars($item['content_key']); ?></span>
                      <div>
                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                          data-bs-target="#deleteModal<?php echo $item['id']; ?>">
                          <i class="bi bi-trash"></i>
                        </button>
                      </div>
                    </div>
                    <div class="content-item-body flex-grow-1 ">
                      <form method="post" action="upload_handler.php" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                        <input type="hidden" name="section_name" value="<?php echo htmlspecialchars($sectionName); ?>">
                        <input type="hidden" name="content_key"
                          value="<?php echo htmlspecialchars($item['content_key']); ?>">


                        <?php if (!empty($item['content'])): ?>
                          <input type="hidden" name="old_file" value="<?php echo htmlspecialchars($item['content']); ?>">
                        <?php endif; ?>

                        <?php
                        $isFileUpload = false;
                        $acceptTypes = '';
                        $helpText = '';

                        // Configure file upload settings based on section and content key
                        $renderImageInput = false;

                        // put the section name of section that has image contents 
                        $chosenSections = ["partners_logo", "about", "official-logo"];

                        if (
                          in_array($sectionName, $chosenSections) &&
                          (
                            // put the content key of content that has image content to render input:file 
                            str_starts_with($item['content_key'], 'p_logo_img') ||
                            str_starts_with($item['content_key'], 'nexen-logo') ||
                            str_starts_with($item['content_key'], 'about_img')
                          )
                        ) {
                          $renderImageInput = true;
                        }

                        if (
                          ($sectionName === 'hero' && $item['content_key'] === 'background') ||
                          ($sectionName === 'about' && $item['content_key'] === 'video')
                        ) {
                          $isFileUpload = true;
                          $acceptTypes = 'video/mp4,video/webm,video/ogg';
                          $helpText = 'Upload a video file (MP4, WebM, or OGG - Max 50MB)';
                        } elseif ($renderImageInput) {
                          $isFileUpload = true;
                          $acceptTypes = 'image/jpeg,image/png,image/gif';
                          $helpText = 'Upload an image file (JPG, PNG, or GIF - Max 5MB)';
                        } elseif (
                          ($sectionName === 'blog' && strpos($item['content_key'], 'img') !== false) ||
                          ($sectionName === 'why-us' && strpos($item['content_key'], 'img') !== false) ||
                          ($sectionName === 'projects' && strpos($item['content_key'], 'img') !== false)
                        ) {
                          $isFileUpload = true;
                          $acceptTypes = 'image/jpeg,image/png,image/gif';
                          $helpText = 'Upload an image file (JPG, PNG, or GIF - Max 5MB)';
                        }

                        if ($isFileUpload):
                        ?>
                          <div class="mb-3">
                            <?php if (!empty($item['content'])): ?>
                              <div class="current-file mb-2">
                                <label class="form-label">Current file:</label>
                                <?php if (strpos($acceptTypes, 'image/') !== false): ?>
                                  <img src="<?php echo htmlspecialchars($item['content']); ?>" alt="Current image"
                                    style="max-width: 100%; aspect-ratio:5/3; object-fit:contain;" class="border mb-3 d-block w-100">
                                <?php else: ?>
                                  <video src="<?php echo htmlspecialchars($item['content']); ?>"
                                    style="max-width: 100%; aspect-ratio:5/3; object-fit:contain;" class="border mb-3 d-block w-100" controls></video>
                                <?php endif; ?>
                              </div>
                            <?php endif; ?>
                            <label class="form-label">Upload new file:</label>
                            <input type="file" class="form-control" name="file" accept="<?php echo $acceptTypes; ?>">
                            <small class="text-muted"><?php echo $helpText; ?></small>
                          </div>
                        <?php else: ?>
                          <div class="mb-3">
                            <div class="form-control-wrapper">
                              <textarea class="form-control" name="content"
                                rows="8"><?php echo htmlspecialchars($item['content']); ?></textarea>
                            </div>
                          </div>
                        <?php endif; ?>

                        <div class="content-actions">
                          <button type="submit" class="btn btn-outline w-100 btn-sm">
                            <i class="bi bi-check-circle"></i> Update
                          </button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

                <!-- Delete Modal for each item -->
                <div class="modal fade" id="deleteModal<?php echo $item['id']; ?>" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <p>Are you sure you want to delete the content with key
                          <strong><?php echo htmlspecialchars($item['content_key']); ?></strong> from the
                          <?php echo htmlspecialchars(ucfirst($sectionName)); ?> section?
                        </p>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form method="post" action="">
                          <input type="hidden" name="action" value="delete">
                          <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                          <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Add Content Modal -->
  <div class="modal fade" id="addContentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add New Content</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="post" action="">
          <div class="modal-body">
            <input type="hidden" name="action" value="add">
            <div class="mb-3">
              <label for="section_name" class="form-label d-block mb-0">Section Name:</label>
              <small style="color:rgb(100, 100, 100); font-size:12px">Copy the exact name of section where you want to put the content.</small>
              <input placeholder="Enter the section name" type="text" class="form-control mt-2" id="section_name" name="section_name" required>
            </div>
            <div class="mb-3">
              <label for="content_key" class="form-label">Content Key:</label>
              <input placeholder="Enter content key" type="text" class="form-control" id="content_key" name="content_key" required>
            </div>
            <div class="mb-3">
              <label for="content" class="form-label">Content:</label>
              <textarea placeholder="Enter content here" class="form-control" id="content" name="content" rows="6"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Add Content</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Website Preview Modal -->
  <div id="websitePreviewModal" class="preview-modal">
    <div class="preview-modal-content">
      <div class="preview-header">
        <h5 class="preview-title">Website Preview</h5>
        <button id="closePreviewBtn" class="preview-close">&times;</button>
      </div>
      <div class="preview-responsive-controls">
        <button class="preview-device-btn active" data-width="100%">
          <i class="bi bi-laptop"></i> Desktop
        </button>
        <button class="preview-device-btn" data-width="768px">
          <i class="bi bi-tablet"></i> Tablet
        </button>
        <button class="preview-device-btn" data-width="375px">
          <i class="bi bi-phone"></i> Mobile
        </button>
      </div>
      <div class="preview-body">
        <iframe id="previewIframe" class="preview-iframe" src="about:blank"></iframe>
      </div>
    </div>
  </div>

  <!-- Logout Confirmation Modal -->
  <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirm Logout</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to logout?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <script>
    // Sidebar Toggle
    document.addEventListener('DOMContentLoaded', function() {
      const sidebar = document.querySelector('.sidebar');
      const mainContent = document.querySelector('.main-content');
      const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
      const cardHeaderBtns = document.getElementsByClassName("card-header-btn");
      const cardBodies = document.getElementsByClassName("card-body");

      for (let i = 0; i < cardHeaderBtns.length; i++) {

        cardHeaderBtns[i].addEventListener('click', function() {
          if (cardHeaderBtns[i].classList.contains("open")) {
            cardHeaderBtns[i].classList.remove("open");
          } else {
            cardHeaderBtns[i].classList.add("open");
          }

          cardBodies[i].classList.toggle("shrink");
        });
      }

      if (sidebarToggleBtn) {
        sidebarToggleBtn.addEventListener('click', function() {
          sidebar.classList.toggle('show');
          mainContent.classList.toggle('sidebar-open');
        });
      }

      // Auto-dismiss alerts after 5 seconds
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(function(alert) {
        setTimeout(function() {
          const closeBtn = alert.querySelector('.btn-close');
          if (closeBtn) {
            closeBtn.click();
          }
        }, 5000);
      });

      // Smooth scroll to section
      const sectionLinks = document.querySelectorAll('a[href^="#section-"]');
      sectionLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          const targetId = this.getAttribute('href');
          const targetElement = document.querySelector(targetId);
          if (targetElement) {
            window.scrollTo({
              top: targetElement.offsetTop - 80,
              behavior: 'smooth'
            });
          }
        });
      });
    });

    // Website Preview Modal Functionality
    document.addEventListener('DOMContentLoaded', function() {
      // Get DOM elements
      const viewWebsiteBtn = document.getElementById('viewWebsiteBtn');
      const websitePreviewModal = document.getElementById('websitePreviewModal');
      const closePreviewBtn = document.getElementById('closePreviewBtn');
      const previewIframe = document.getElementById('previewIframe');
      const deviceButtons = document.querySelectorAll('.preview-device-btn');

      // Check if elements exist to prevent errors
      if (!viewWebsiteBtn || !websitePreviewModal || !closePreviewBtn || !previewIframe) {
        console.error('One or more preview modal elements not found');
        return;
      }

      // Open the preview modal
      viewWebsiteBtn.addEventListener('click', function(e) {
        e.preventDefault();
        console.log('View Website button clicked'); // Debug log

        // Get the current website URL
        const websiteUrl = 'index.php';

        // Set the iframe source
        previewIframe.src = websiteUrl;

        // Show the modal
        websitePreviewModal.style.display = 'block';

        // Add a class to prevent body scrolling when modal is open
        document.body.classList.add('modal-open');
      });

      // Close the preview modal
      closePreviewBtn.addEventListener('click', function() {
        console.log('Close button clicked'); // Debug log
        websitePreviewModal.style.display = 'none';
        document.body.classList.remove('modal-open');
        // Clear the iframe source to stop any media/processes
        previewIframe.src = 'about:blank';
      });

      // Close modal if clicking outside of the content
      websitePreviewModal.addEventListener('click', function(e) {
        if (e.target === websitePreviewModal) {
          websitePreviewModal.style.display = 'none';
          document.body.classList.remove('modal-open');
          previewIframe.src = 'about:blank';
        }
      });

      // Handle responsive device buttons
      if (deviceButtons.length > 0) {
        deviceButtons.forEach(button => {
          button.addEventListener('click', function() {
            // Remove active class from all buttons
            deviceButtons.forEach(btn => btn.classList.remove('active'));

            // Add active class to clicked button
            this.classList.add('active');

            // Get the target width from data attribute
            const targetWidth = this.getAttribute('data-width');

            // Apply width to iframe
            previewIframe.style.width = targetWidth;

            // If it's mobile or tablet, add a max-height
            if (targetWidth !== '100%') {
              previewIframe.style.height = '80vh';
            } else {
              previewIframe.style.height = '100%';
            }
          });
        });
      }

      // Close modal with Escape key
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && websitePreviewModal.style.display === 'block') {
          websitePreviewModal.style.display = 'none';
          document.body.classList.remove('modal-open');
          previewIframe.src = 'about:blank';
        }
      });

      console.log('Website preview functionality initialized'); // Debug log
    });
  </script>
</body>

</html>