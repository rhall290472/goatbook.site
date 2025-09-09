<?php
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
  echo __DIR__;
  die(' - </br>An error occurred. Please try again later.');
}
/*
!==============================================================================!
!\                                                                            /!
!\\                                                                          //!
! \##########################################################################/ !
!  #         This is Proprietary Software of Richard Hall                   #  !
!  ##########################################################################  !
!  #                                                                        #  !
!  #                                                                        #  !
!  #   Copyright 2024 - Richard Hall                                        #  !
!  #                                                                        #  !
!  #   The information contained herein is the property of Richard          #  !
!  #   Hall, and shall not be copied, in whole or in part, or               #  !
!  #   disclosed to others in any manner without the express written        #  !
!  #   authorization of Richard Hall.                                       #  !
!  #                                                                        #  !
!  #                                                                        #  !
! /##########################################################################\ !
!//                                                                          \\!
!/                                                                            \!
!==============================================================================!
*/

// Simple routing based on 'page' GET parameter
$page = filter_input(INPUT_GET, 'page') ?? 'home';
$page = strtolower(trim($page));
$valid_pages = [
  'home',
  'campsites',
  'displaycampsite',
  'addcampsite',
  'goatbook2000',
  'goatbook1995',
  'tahosalodge',
  '',
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

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php load_template('/src/templates/head.php'); ?>
</head>

<body>
  <?php
  load_template('/src/templates/navbar.php');
  ?>

  <?php
  switch ($page) {
    case 'home':
  ?>
      <div class="container-fluid">
        <div class="row flex-nowrap">
          <!-- Include the common side nav bar -->
          <?php load_template('/src/templates/sidebar.php'); ?>
          <div class="col py-3">
            <h3 style="text-align: center;">Guide to Outdoor Activities for Troops</h3>
            <h4>Overview:</h4>
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
      include('../src/pages/CampSites.php');
      break;
    case 'displaycampsite':
      include('../src/pages/DisplayCampSite.php');
      break;
    case 'addcampsite':
      include('../src/pages/AddCampSite.php');
      break;
    case 'goatbook2000':
      echo SITE_URL . '/assets/Book/GoatBook_2000_OCR.pdf';
      echo '<div class="container-fluid"><div class="row flex-nowrap">';
      echo load_template('/src/templates/sidebar.php');
      echo '<div class="col py-3"><div style="text-align: center; padding: 20px;"><iframe src="' . htmlspecialchars(SITE_URL . '/assets/Book/GoatBook_2000_OCR.pdf') . '#toolbar=1&navpanes=1&scrollbar=1" width="100%" height="800px" style="border: 1px solid #ccc; max-width: 100%;"></iframe></div></div>';
      echo '</div></div>';
      break;
    case 'goatbook1995':
      echo SITE_URL . '/assets/Book/GoatBook_1995_OCR.pdf';
      echo '<div class="container-fluid"><div class="row flex-nowrap">';
      echo load_template('/src/templates/sidebar.php');
      echo '<div class="col py-3"><div style="text-align: center; padding: 20px;"><iframe src="' . htmlspecialchars(SITE_URL . '/assets/Book/GoatBook_1995_OCR.pdf') . '#toolbar=1&navpanes=1&scrollbar=1" width="100%" height="800px" style="border: 1px solid #ccc; max-width: 100%;"></iframe></div></div>';
      echo '</div></div>';
      break;
    case 'tahosalodge':
      echo SITE_URL . '/assets/Book/Tahosa_Lodge_383.pdf';
      echo '<div class="container-fluid"><div class="row flex-nowrap">';
      echo load_template('/src/templates/sidebar.php');
      echo '<div class="col py-3"><div style="text-align: center; padding: 20px;"><iframe src="' . htmlspecialchars(SITE_URL . '/assets/Book/Tahosa_Lodge_383.pdf') . '#toolbar=1&navpanes=1&scrollbar=1" width="100%" height="800px" style="border: 1px solid #ccc; max-width: 100%;"></iframe></div></div>';
      echo '</div></div>';
      break;
    case '':
      include('');
      break;
    case '':
      include('');
      break;
    case '':
      include('');
      break;
    case '':
      include('');
      break;
    case '':
      include('');
      break;
    case '':
      include('');
      break;
    case 'login':
      include('../src/pages/logon.php');
      break;
    case 'register':
      include('../src/pages/register.php');
      break;
    case 'logout':
      include('../src/pages/logoff.php');
      break;
    default:
      echo '<h1>404</h1><p>Page not found.</p>';
  }
  ?>

  <!-- Main JS File -->
  <script src="<?php echo SITE_URL . '/assets/js/main.js'; ?>" defer></script>
</body>

</html>