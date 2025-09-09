<?php
if (!session_id()) {
  session_start();

  // Load configuration
  if (file_exists(__DIR__ . '/config/config.php')) {
    require_once __DIR__ . '/config/config.php';
  } else {
    echo __DIR__;
    die('An error occurred. Please try again later.');
  }
}
include('cGOAT.php');
$cGOAT = cGOAT::getInstance();

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
</head>

<body>
  <?php
  load_template('/navbar.php');
  ?>

  <!-- <section class="pt-4">
    <div class="container px-lg-5">
      <!-- Page Features-->
      <!--<div class="row gx-lg-5">

        <div class="col-lg-10 col-xxl-10 mb-5">
          <h1>The Wikipedia of Troop Activities</h1>
          <p>The G.O.A.T. Book/Site, which provides Scouts, Scouters and campers in general a guide to campsites (and activities) in Colorado.</p></br>
          <p>The term GOAT is an acronym, that is easy to remember and means Guide to Outdoor Activities for Troops. This guide has been prepared for Scouts
            and Scouters in order to share campsites, hiking trails, and other activities that have been successfully tried by other units. This site is
            designed to be used by both new Scouters and the experienced Scouter; hopefully providing new experiences and locations to better enjoy the
            Colorado outdoors.</p>
          <p> The site has been designed to be printed out on standard 8 1/2 x 11 paper, copies of particular pages can be made and taken on your adventure.
            The GOAT Site has been grouped into areas covering a geographic region or area of the state such as the Guanella Pass Area and also by activitiy
            types. Each having one or more maps merged into the text. In many cases information is provided on campsite facilities and known restrictions. The majority of
            the GOAT Site activities reference the National Forest Service Maps; these maps show a large land area with access roads to campgrounds and
            trailheads. In addition, land ownership is color coded, and hiking/backpacking trails are shown and designated with Forest Service trail numbers. </p>
          <p>A number of Forest Service maps have been changed when new editions were printed between 1989 -1995. Road numbers have been changed from Forest
            Service to County Road numbers; at least that portion of the road before the National Forest boundary. Some road numbers have been removed with
            no replacement number on the new edition. Many of the Forest Service road numbers are still posted along with the county signs on these roads.
            The trick in using the GOAT Book is to remember that the new editions of the Forest Service maps were used for map and text references.</p>
        </div>
      </div>
    </div>
  </section> -->


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
        <h4>Features:</h4>
        <li class="lead">Comprehensive Guide: Covers campsites, hiking trails, and other outdoor activities successfully
          tried by Scout units, organized into chapters by geographic regions (e.g., Guanella Pass Area).:
        </li>

        <li class="lead">Maps Integration: Each chapter includes one or more maps merged into the text to aid navigation and
          planning.</li>
        <li class="lead">Printable Format: Designed for printing on standard 8.5x11 paper, allowing users to take specific
          pages on their adventures.</li>
        <li class="lead">Scout-Focused: Tailored for Scouts and Scouters, with content rooted in the 2000 GOAT
          Book by the Order of the Arrow, Tahosa Lodge, promoting Scout camping.</li>
        <li class="lead">Accessibility: Aimed at both novice and
          seasoned outdoor enthusiasts, making it versatile for different experience levels.
        </li>

        </br>
        <h4>Specialized for Colorado Outdoors:</h4>
        <p class="lead">Focuses specifically on Colorado, providing localized, practical information for Scouts and campers.
          User-Friendly Design: The printable format is highly practical for outdoor use, where digital access may be limited.
          Map Integration: Including maps within the text enhances usability for navigation and trip planning.
          Community-Driven: Builds on Scout community contributions, ensuring tried-and-tested recommendations.
          Versatile for All Levels: Caters to both new and experienced Scouters, broadening its appeal.</p>

        <h4>Comparison to Alternatives:</h4>
        <p class="lead">Compared to general outdoor resources like AllTrails or REI’s hiking guides, goatbook.site is more
          niche, focusing on Scout-friendly activities in Colorado. Its print-focused design is unique but may feel less
          modern compared to apps or websites with interactive maps or user-generated reviews. For Scout troops, it’s likely
          more tailored than broader platforms, but it may lack the dynamic features of sites like Campendium or modern
          Scout apps.</p>

        <h4>Conclusion:</h4>
        <p class="lead">The GOAT Site is a valuable resource for Scout troops and outdoor enthusiasts planning activities
          in Colorado. Its focus on printable, region-specific guides with integrated maps makes it practical for fieldwork,
          especially for Scout leaders seeking tested campsites and trails. For Scout groups in Colorado, this site is
          likely a helpful starting point, but users should verify details (e.g., trail conditions or campsite availability)
          through additional sources. If you’re planning a Scout trip in Colorado, printing relevant chapters from this
          site could be a great asset for offline use.</p>

        <h4>Acknowledgement</h4>
        <p class="lead">The Starting point of this web site is based on the 2000 GOAT Book which was created by the then Denver Area Council, Order of the Arrow, Tahosa Lodge which
          promotes Scout camping using several different methods. One of these methods is through the G.O.A.T. Book, which provides Scouts, Scouters and campers in general a guide to campsites
          (and activities) in Colorado.</p>


      </div>
    </div>
  </div>



  <?php //include 'Footer.php'; ?>

</body>

</html>