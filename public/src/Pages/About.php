<?php
// Secure session start
if (session_status() === PHP_SESSION_NONE) {
  session_start([
    'cookie_httponly' => true,
    'use_strict_mode' => true,
    'cookie_secure' => isset($_SERVER['HTTPS'])
  ]);
}

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

<body>
  <!-- <div class="container-fluid"> -->
  <div class="row flex-nowrap">
    <!-- Include the common side nav bar -->
    <?php //include 'sidebar.php'; 
    ?>
    <div class="col py-3">
      <h3 style="text-align: center;">Guide to Outdoor Activities for Troops</h3>
      <h4>Overview:</h4>
      <p class="lead">The GOAT Site is a web-based guide created to assist Scouts, Scouters, and outdoor enthusiasts
        in exploring Colorado’s outdoor activities. It builds on the 2000 GOAT Book by the Denver Area Council, Order
        of the Arrow, Tahosa Lodge, and aims to provide detailed information on campsites, hiking trails, and other
        activities across various geographic regions of Colorado. The site is intended for both new and experienced
        Scouters to enhance their outdoor experiences.</p>
      <h4>Features:</h4>
      <ul style="padding-left: 40px;"> <!-- Adjust padding-left for desired indentation -->
        <li class="lead">Comprehensive Guide: Covers campsites, hiking trails, and other outdoor activities successfully tried by Scout units, organized into chapters by geographic regions (e.g., Guanella Pass Area).</li>
        <li class="lead">Maps Integration: Each chapter includes one or more maps merged into the text to aid navigation and planning.</li>
        <li class="lead">Printable Format: Designed for printing on standard 8.5x11 paper, allowing users to take specific pages on their adventures.</li>
        <li class="lead">Scout-Focused: Tailored for Scouts and Scouters, with content rooted in the 2000 GOAT Book by the Order of the Arrow, Tahosa Lodge, promoting Scout camping.</li>
        <li class="lead">Accessibility: Aimed at both novice and seasoned outdoor enthusiasts, making it versatile for different experience levels.</li>
      </ul>
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
  <!-- </div> -->



  <?php //include 'Footer.php'; 
  ?>

</body>

</html>