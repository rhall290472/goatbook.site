<?php
if (!session_id()) {
  session_start();
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
  <?php include('head.php'); ?>
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
  include_once('header.php');
  ?>
  <div class="container-fluid">
    <div class="row flex-nowrap">
      <!-- Include the common side nav bar -->
      <?php include 'navbar.php'; ?>
      <div class="col py-3">
        <h3>Guide to Outdoor Activities for Troops</h3>
        <p class="lead">
          This guide has been prepared for Scouts and Scouters in order to share campsites, hiking trails, and other activities that have been successfully tried by other units. This site is
          designed to be used by both new Scouters and the experienced Scouter; hopefully providing new experiences and locations to better enjoy the Colorado outdoors. The site has been designed
          to be printed on standard 8 1/2x11 paper, print outs of particular pages can be made and taken on your adventure. The GOAT Site has been grouped into chapters covering a geographic
          region or area of the state such as the Guanella Pass Area. Chapters have one or more maps merged into the text.
        </p>
        <ul class="list-unstyled">
          <li>
            <h5>Acknowledgement</h5>
            The Starting point of this web site is based on the 2000 GOAT Book which was created by the then Denver Area Council, Order of the Arrow, Tahosa Lodge which
            promotes Scout camping using several different methods. One of these methods is through the G.O.A.T. Book, which provides Scouts, Scouters and campers in general a guide to campsites
            (and activities) in Colorado.
          </li>
          <li>
            <div class="map-container">
              <iframe src="https://www.google.com/maps/d/embed?mid=1h1MwNhYsCUFLAFhf7EtC6E4GEa-lWtc&ehbc=2E312F&noprof=1" width="1080" height="640"></iframe>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <!-- Main JS File -->
  <script src="./assets/js/main.js"></script>

  <?php include 'Footer.php'; ?>

</body>

</html>