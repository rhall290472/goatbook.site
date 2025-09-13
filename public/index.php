<?php
/*
 * Main entry point for the GOAT book website.
 * Handles routing, form submissions, and includes views based on the 'page' GET parameter.
 */

// Secure session start
if (session_status() === PHP_SESSION_NONE) {
  session_start([
    'cookie_httponly' => true,
    'use_strict_mode' => true,
    'cookie_secure' => isset($_SERVER['HTTPS'])
  ]);
}

// Load configuration
if (file_exists(__DIR__ . '/../config/config.php')) {
  require_once __DIR__ . '/../config/config.php';
} else {
  error_log("Unable to find file config.php @ " . __FILE__ . ' ' . __LINE__);
  die('An error occurred. Please try again later.');
}

// Define SITE_URL fallback if not set
// if (!defined('SITE_URL')) {
  // define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST']);
// }

// Include cGOAT class
include(BASE_PATH . '/public/src/Classes/cGOAT.php');
$cGOAT = cGOAT::getInstance();

// Handle login logic before any output
$page = filter_input(INPUT_GET, 'page') ?? 'home';
$page = strtolower(trim($page));

// Check if the user is already logged in
if ($page === 'login' && isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
  $_SESSION['feedback'] = ['type' => 'success', 'message' => 'You are already logged in.'];
  header("Location: index.php?page=home");
  exit;
}

// Process login form submission
if ($page === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

  if (empty($username)) {
    $_SESSION['feedback'] = ['type' => 'danger', 'message' => 'Please enter username.'];
    header("Location: index.php?page=login");
    exit;
  }
  if (empty($password)) {
    $_SESSION['feedback'] = ['type' => 'danger', 'message' => 'Please enter your password.'];
    header("Location: index.php?page=login");
    exit;
  }

  try {
    $sql = "SELECT id, username, password, enabled FROM users WHERE username = ?";
    if ($stmt = mysqli_prepare($cGOAT->getDbConn(), $sql)) {
      mysqli_stmt_bind_param($stmt, "s", $username);
      if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) == 1) {
          mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $enabled);
          if (mysqli_stmt_fetch($stmt)) {
            if (password_verify($password, $hashed_password) && $enabled) {
              $_SESSION["loggedin"] = true;
              $_SESSION["id"] = $id;
              $_SESSION["username"] = $username;
              $_SESSION["enabled"] = $enabled;
              $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
              $_SESSION['feedback'] = ['type' => 'success', 'message' => 'Login successful.'];
              header("Location: index.php?page=home");
            } else {
              $_SESSION['feedback'] = ['type' => 'danger', 'message' => 'Invalid username or password or your account is not enabled.'];
              header("Location: index.php?page=login");
            }
          }
        } else {
          $_SESSION['feedback'] = ['type' => 'danger', 'message' => 'Invalid username or password.'];
          header("Location: index.php?page=login");
        }
      } else {
        throw new Exception("Database query failed: " . mysqli_error($cGOAT->getDbConn()));
      }
      mysqli_stmt_close($stmt);
    } else {
      throw new Exception("Failed to prepare statement: " . mysqli_error($cGOAT->getDbConn()));
    }
  } catch (Exception $e) {
    error_log("index.php - Login error: " . $e->getMessage(), 0);
    $_SESSION['feedback'] = ['type' => 'danger', 'message' => 'An error occurred during login. Please try again later.'];
    header("Location: index.php?page=login");
  }
  exit;
}

// Simple routing for other pages
$valid_pages = [
  'home',
  'campsites',
  'displaycampsite',
  'addcampsite',
  'editcampsites',
  'goatbook2000',
  'goatbook1995',
  'tahosalodge',
  'age-appropriate',
  'camping-outdoor',
  'safescouting',
  'cubcamping',
  'viewusers',
  'edituser',
  'viewlog',
  'about',
  'contact',
  'sendemail',
  'login',
  'logout',
  'register'
];
if (!in_array($page, $valid_pages)) {
  $page = 'home'; // Default to home if page is invalid
}

// Store form feedback
$feedback = isset($_SESSION['feedback']) ? $_SESSION['feedback'] : [];
unset($_SESSION['feedback']);

// Set CSRF token if not set
if (!isset($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Start output buffering to capture HTML
ob_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php load_template("/public/src/Templates/head.php"); ?>
</head>

<body>
  <!-- Navbar -->
  <?php load_template("/public/src/Templates/navbar.php", ['page' => $page]); ?>

  <!-- Sidebar -->
  <?php load_template("/public/src/Templates/sidebar.php", ['page' => $page]); ?>

  <!-- Main Content -->
  <main class="main-content">
    
    <div class="container-fluid mt-5 pt-3">
      <!-- Display Feedback -->
      <?php if (!empty($feedback)): ?>
        <div class="alert alert-<?php echo htmlspecialchars($feedback['type']); ?> alert-dismissible fade show" role="alert">
          <?php echo htmlspecialchars($feedback['message']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <?php
      switch ($page) {
        case 'home':
      ?>
          <div class="container-fluid">
            <div class="row flex-nowrap">
              
              <!-- Include the common side nav bar -->
              <div class="col py-3">
                <h3 style="text-align: center;">Guide to Outdoor Activities for Troops</h3>
                <p class="lead">The GOAT Site is a web-based guide created to assist Scouts, Scouters, and outdoor enthusiasts
                  in exploring Colorado’s outdoor activities. It builds on the 2000 GOAT Book by the Denver Area Council, Order
                  of the Arrow, Tahosa Lodge, and aims to provide detailed information on campsites, hiking trails, and other
                  activities across various geographic regions of Colorado. The site is intended for both new and experienced
                  Scouters to enhance their outdoor experiences.</p>
                <div class="map-container">
                  <iframe src="https://www.google.com/maps/d/embed?mid=1h1MwNhYsCUFLAFhf7EtC6E4GEa-lWtc&ehbc=2E312F&noprof=1" width="1080" height="640"></iframe>
                </div>
              </div>
            </div>
          </div>
      <?php
          break;
        case 'campsites':
          include('src/Pages/CampSites.php');
          break;
        case 'displaycampsite':
          include('src/Pages/DisplayCampSite.php');
          break;
        case 'addcampsite':
          include('src/Pages/AddCampSite.php');
          break;
        case 'editcampsites':
          include('src/Pages/EditCampSite.php');
          break;
        case 'viewusers':
          include('src/Pages/ViewUsers.php');
          break;
        case 'edituser':
          include('src/Pages/EditUser.php');
          break;
        case 'viewlog':
          include('src/Pages/ViewErrors.php'); 
          break;
        case 'login':
          include('src/Pages/logon.php'); // Now only includes the form
          break;
        case 'about':
          include('src/Pages/About.php');
          break;
        case 'contact';
          include('src/Pages/Contact.php');
          break;
        case 'sendemail':
          include('src/Pages/send_email.php');
          break;
        case 'logout':
          include('src/Pages/logoff.php');
          break;
        case 'register':
          include('src/Pages/register.php');
          break;
        default:
          echo '<h1>404</h1><p>Page not found.</p>';
      }
      ?>
    </div>
  </main>

<!-- Bootstrap JS (single instance, bundle includes Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const sidebar = document.getElementById('sidebar');
      if (window.innerWidth < 992) {
        sidebar.classList.add('collapse');
      }
      window.addEventListener('resize', function() {
        if (window.innerWidth < 992) {
          sidebar.classList.add('collapse');
        } else {
          sidebar.classList.remove('collapse');
        }
      });
    });
  </script>
  
</body>

</html>

<?php
// Flush output buffer
ob_end_flush();
?>