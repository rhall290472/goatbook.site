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

  <div class="container-fluid">
    <div class="row flex-nowrap">
      <!-- Include common side bar nav -->
      <?php include '
      <div class="col py-3">
        <!- Page content Here -->
        <div class="container px-3">
          <div class="row gx-lg-3">

            <div class="col-lg-10 col-xxl-10 mb-3">
              <h1><?php echo isset($Site['area']) ? $cGOAT->GetAreaText($Site['area']) : "" ?></h1>
              <h2><?php
                  echo ucwords(strtolower($Site["name"])) . " - ";
                  echo $cGOAT->GetActivityText($Site['type1']);
                  if ($Site['type2'] != 0) {
                    echo " / " . $cGOAT->GetActivityText($Site['type2']);
                  }
                  ?></h2>

              <?php if (!empty($Site['facilities']))
                echo "<p> Facilities: " . $Site['facilities'] . "</p>";
              ?>
              <?php if (!empty($Site['map'])) {  ?>
                <p><?php echo "Link: "; ?><?php echo "<a href=" . $Site['map'] . " target='_blank'>More Information" ?></a></p>
              <?php } ?>

              <p><?php echo $Site['directions']; ?></p>

              <?php if (isset($Site['embedmap'])) {
                echo $Site['embedmap'];
              }

              if (isset($Site['created'])) {
                echo "<p>Created on: " . $Site['created'] . "</p>";
              }
              if (isset($Site['edited_by'])) {
                echo "<p>Last edited by: " . $Site['edited_by'] . " On: " . $Site['edited_on'] . "</p>";
              }

              ?>
            </div>
          </div>
          <div class="row gx-lg-5 d-print-none">
            <?php echo "<a href=./EditCampSite.php?Siteid=" . $Site['IDX'] . " class='btn btn-primary' role='button'>Edit Site</a>"; ?>
          </div>
        </div>
        <!-- </section> -->
      </div>
      <div class="col py-3">
        <!-- Page content Here -->
        <div class="container px-lg-5">
          <div class="row gx-lg-5">
            <div class="col-lg-10 col-xxl-10 mb-5">
              <h2>Reviews</h2>
              <div class="reviews"></div>
              <script src="assets/js/reviews.js"></script>
              <script>
                var site_idx = <?php echo json_encode($Site['IDX'], JSON_HEX_TAG); ?>;
                new Reviews({
                  site_idx: site_idx,
                  reviews_per_pagination_page: 5,
                  current_pagination_page: 1
                });
              </script>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
  </div>

  <?php include('Footer.php'); ?>

</body>

</html>