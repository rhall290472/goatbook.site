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
!  ##########################################################################  !
!  #                                                                        #  !
!  #                                                                        #  !
!  #   Copyright 2017-2024 - Richard Hall                                   #  !
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

/* Check if the user is already logged in, if yes then redirect him to welcome page */
if (!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)) {
  header("HTTP/1.0 403 Forbidden");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include('./head.php'); ?>
</head>
<?php load_template('/navbar.php'); ?>

<body class="body" style="padding:20px">

  <div class="my_div">
    <?php
    if (file_exists('./php_errors.log')) {
      echo "<h2>Below is a list of recorded errors found.</h2>";

      $errorlog = file_get_contents('./php_errors.log');
      if (false == $errorlog) {
        $cGOAT->function_alert("Unable to read php_errors.log" . $errorlog);
      } else {
        echo nl2br($errorlog);
      }
    }
    else
    echo "<h2>NO recorded errors found.</h2>";
    ?>
  </div>

  <?php include("./Footer.php"); ?>
</body>

</html>