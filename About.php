<?php
if (!session_id()) {
  session_start();
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
  <?php include('head.php'); ?>
</head>

<body>
  <?php
  load_template('/navbar.php');


  // Check which type of camp view they wish to view
  if (isset($_GET['Siteid'])) {
    $site = $_GET['Siteid'];
    $sql = "SELECT * FROM `site` WHERE IDX = '" . $site . "'";

    $ResultSIte = $cGOAT->doQuery($sql);
    if (!$ResultSIte) {
      $strErr = "Internal Error";
      $cGOAT->function_alert($strErr);
      exit();
    }
    $Site = $ResultSIte->fetch_assoc();
  }
  ?>

  <!-- <div class="container-fluid"> -->
  <!-- <div class="row flex-nowrap"> -->
  <!-- <div class="col-auto col-md-3 col-xl-auto px-sm-2 px-0 bg-dark"> -->
  <!-- <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100"> -->
  <!--  -->
  <!--  -->
  <!-- </div> -->
  <!-- </div> -->
  <!-- </div> -->
  <!-- </div> -->


  <section class="pt-4">
    <div class="container px-lg-5">
      <!-- Page Features-->
      <div class="row gx-lg-5">

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
  </section>


  <?php include 'Footer.php'; ?>

</body>

</html>