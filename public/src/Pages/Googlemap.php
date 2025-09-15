<?php
// Secure session start
if (session_status() === PHP_SESSION_NONE) {
  session_start([
    'cookie_httponly' => true,
    'use_strict_mode' => true,
    'cookie_secure' => isset($_SERVER['HTTPS'])
  ]);
}
include('cGOAT.php');
$cGOAT = cGOAT::getInstance();

ini_set("memory_limit", "90000M");

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


  $csv_hdr = "WKT, Name, Area, Activity 1, Activity 2, Site Link";
  $csv_output = "";
  ?>
  <table class='table' style='width:1024' ;>
    <tr>
      <th>WKT</th>
      <th>Name</th>
      <th>Area</th>
      <th>Activity 1</th>
      <th>Activity 2</th>
      <th>Site Link</th>
    </tr>
    <?php


    $TodaysDate = strtotime("now");
    $WKT = NULL;

    $sql = "SELECT * FROM `site` WHERE IsDeleted <> 1";
    $site_sresults = $cGOAT->doQuery($sql);

    while ($site = $site_sresults->fetch_assoc()) {

      $first_token  = strpos($site['embedmap'], "!2d");
      $second_token = strpos($site['embedmap'], '!2m');
      if (!$second_token)
        $second_token = strpos($site['embedmap'], '!3m');
      $Point = substr($site['embedmap'], $first_token + 3, $second_token - ($first_token + 3));
      $point = str_replace("!3d", " ", $Point);
      $WKT = "POINT(" . $point . ")";
      $SiteLink = "Siteid=" . $site['IDX'];
      $SiteURL = "https://goatbook.site/DisplayCampSite.php?" . $SiteLink;


      echo "<tr><td>" .
        $WKT . '</td><td>' .
        ucwords(strtolower($site['name'])) . '</td><td>' .
        $cGOAT->GetAreaText($site['area']) . '</td><td>' .
        $cGOAT->GetActivityText($site['type1']) . '</td><td>' .
        $cGOAT->GetActivityText($site['type2']) . '</td><td>' .
        $SiteURL . '<td></tr>';


      $csv_output .= $WKT . ",";
      $csv_output .= ucwords(strtolower($site['name'])) . ",";
      $csv_output .= $cGOAT->GetAreaText($site['area']) . ",";
      $csv_output .= $cGOAT->GetActivityText($site['type1']) . ",";
      $csv_output .= $cGOAT->GetActivityText($site['type2']) . ",";
      $csv_output .= $SiteURL . "\n";
    }

    echo "</table>";


    ?>
    <br /><br /><br />
    <center>
      <form name="export" action="export.php" method="post">
        <input class='RoundButton' style="width:220px" type="submit" value="Export table to CSV">
        <input type="hidden" value="<?php echo $csv_hdr; ?>" name="csv_hdr">
        <input type="hidden" value="<?php echo $csv_output; ?>" name="csv_output">
      </form>
    </center>
    <br />

</body>

</html>