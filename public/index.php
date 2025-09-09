<?php
if (!session_id()) {
  session_start();
}

// Load configuration
if (file_exists(__DIR__ . '/config/config.php')) {
  require_once __DIR__ . '/config/config.php';
} else {
  echo __DIR__ ;
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php load_template('/head.php'); ?>
  <style>
    /* Center the map iframe */
    .map-container {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-top: 20px;
      /* Optional: Add spacing above the map */
    }

    /* Ensure the iframe is responsive */
    .map-container iframe {
      max-width: 100%;
      /* Prevent overflow on smaller screens */
      width: 1080px;
      /* Maintain original width */
      height: 640px;
      /* Maintain original height */
    }

    @media (max-width: 1200px) {
      .map-container iframe {
        width: 100%;
        /* Adjust width to fit smaller screens */
        height: 400px;
        /* Reduce height for better mobile display */
      }
    }
  </style>
</head>

<body>
  <?php
  load_template('/navbar.php');
  ?>
  <div class="container-fluid">
    <div class="row flex-nowrap">
      <!-- Include the common side nav bar -->
      <?php include 'sidebar.php'; ?>
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
  <!-- Main JS File -->
  <script src="./assets/js/main.js"></script>

  <?php //include 'Footer.php'; 
  ?>

</body>

</html>