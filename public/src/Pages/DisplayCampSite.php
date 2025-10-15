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

<head>
</head>

<body>
  <?php

  // Check which type of camp view they wish to view
// Check which type of camp view they wish to view
if (isset($_GET['Siteid'])) {
    $site_id = filter_input(INPUT_GET, 'Siteid', FILTER_VALIDATE_INT);
    if ($site_id === false || $site_id <= 0) {
        $strErr = "Invalid Site ID";
        $cGOAT->function_alert($strErr);
        exit();
    }

    // Fetch campsite images
    $sql_images = "SELECT * FROM `campsite_images` WHERE `site_id` = ?";
    $stmt = mysqli_prepare($cGOAT->getDbConn(), $sql_images);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $site_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $images = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);

        // Display images
        if (!empty($images)) {
            echo '<div class="campsite-images">';
            foreach ($images as $image) {
                echo '<img src="' . htmlspecialchars($image['file_path'], ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($image['file_name'], ENT_QUOTES, 'UTF-8') . '" style="max-width: 300px; margin: 10px;" />';
            }
            echo '</div>';
        }
    } else {
        $strErr = "Database Error: Unable to prepare image query";
        $cGOAT->function_alert($strErr);
        exit();
    }

    // Fetch campsite details
    $sql_site = "SELECT * FROM `site` WHERE `IDX` = ?"; // Adjust table name if different
    $stmt = mysqli_prepare($cGOAT->getDbConn(), $sql_site);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $site_id);
        mysqli_stmt_execute($stmt);
        $ResultSIte = mysqli_stmt_get_result($stmt);
        if ($ResultSIte && $Site = mysqli_fetch_assoc($ResultSIte)) {
            mysqli_stmt_close($stmt);
        } else {
            $strErr = "Campsite not found";
            $cGOAT->function_alert($strErr);
            exit();
        }
    } else {
        $strErr = "Database Error: Unable to prepare campsite query";
        $cGOAT->function_alert($strErr);
        exit();
    }
} else {
    $strErr = "No Site ID provided";
    $cGOAT->function_alert($strErr);
    exit();
}
?>

  <div class="container-fluid">
    <div class="row flex-nowrap">
      <!-- Include common side bar nav -->
      <div class="col py-3">
        <!-- Page content Here -->
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

              <p><?php echo htmlspecialchars_decode($Site['directions'], ENT_QUOTES); ?></p>

              <?php if (isset($Site['embedmap'])) {
                echo stripslashes($Site['embedmap']);
                //echo htmlspecialchars_decode('<iframe src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3198.830166525641!2d-105.23833394049939!3d39.25315366783766!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMznCsDE1JzExLjUiTiAxMDXCsDE0JzEwLjUiVw!5e1!3m2!1sen!2sus!4v1760472798852!5m2!1sen!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>');
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
            <?php echo "<a href=?page=editcampsites&Siteid=" . $Site['IDX'] . " class='btn btn-primary' role='button'>Edit Site</a>"; ?>
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
              <!-- <script src="<?php //echo htmlspecialchars(SITE_URL . '/assets/js/reviews.js'); ?>"></script> -->
              <script src="<?php echo htmlspecialchars(SITE_URL . '/assets/js/reviews.js'); ?>"></script>
              <script>
                var site_idx = <?php echo json_encode($Site['IDX'], JSON_HEX_TAG); ?>;
                new Reviews({
                  site_idx: site_idx,
                  php_file_url: '<?php echo htmlspecialchars(SITE_URL . '/src/Pages/reviews.php'); ?>',
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
</body>

</html>