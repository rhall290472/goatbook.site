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
  include_once('header.php');


  $csv_hdr = "WKT, Name, Area, Activity 1, Activity 2, Site Link";
  $csv_output = "";
?>
  <table class='table'  style='width:1024';>
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
    $WKT = "POINT()";
    $SiteLink = "Siteid=".$site['IDX'];
    //$SiteURL = "<a href='https://goatbook.site/DisplayCampSite.php?".$SiteLink."'>https://goatbook.site/DisplayCampSite.php?".$SiteLink;


        $WKT. '</td><td>' .
        ucwords(strtolower($site['name'])). '</td><td>' .
        $cGOAT->GetAreaText($site['area']). '</td><td>' .
        $cGOAT->GetActivityText($site['type1']). '</td><td>' .
        $cGOAT->GetActivityText($site['type2']). '</td><td>' .
        $SiteLink. '<td></tr>';


      $csv_output .= "\n";
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