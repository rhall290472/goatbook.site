<?php
if (!session_id()) {
  session_start();
}

require_once 'cGOAT.php';
$cGoat = cGOAT::getInstance();

// This code stops anyone for seeing this page unless they have logged in and
// their account is enabled.
if (!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)) {
  header("HTTP/1.0 403 Forbidden");
  exit;
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
  <?php include 'head.php'; ?>
</head>

<body>
  <?php include 'header.php'; ?>

  <!-- If user is not logged in, then they can see nonething and do nonething. -->
  <?php
  if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    //include('navmenu.php');
  ?>

  <?php }

  ?>

  <center>
    <?php

    // Get a list of sites
    $querySite = "SELECT * FROM site ORDER BY name";
    $queryUser = "SELECT * FROM users WHERE is_deleted <> 1 ORDER BY username";

    $result_Site = $cGoat->doQuery($querySite);
    if (!$result_Site) {
      // Error should be reported by doQuery
      $cGOAT->GotoURL('./index.php');
    }

    $result_User = $cGoat->doQuery($queryUser);
    if (!$result_User) {
      // Error should be reported by doQuery
      $cGOAT->GotoURL('./index.php');
    }

    ?>
    <form method=post>
      <div class="form-row px-5">
        <div class="col-2">
          <label for='SiteName'>Choose a Site: </label>
          <select class='form-control' id='SiteName' name='SiteName'>
            <option value=\"\" </option>
              <?php
              while ($rowSite = $result_Site->fetch_assoc()) {
                echo "<option value=" . $rowSite['IDX'] . ">" . $rowSite['name'] . "</option>";
              }
              ?>
          </select>
        </div>
        <div class="col-2 py-4">
          <input class='btn btn-primary btn-sm' type='submit' name='SubmitSite' value='Select Site' />
        </div>
        <div class="col-2">
          <label for='UserName'>Choose a User: </label>
          <select class='form-control' id='UserName' name='UserName'>
            <option value=\"\" </option>
              <?php
              while ($rowUser = $result_User->fetch_assoc()) {
                echo "<option value=" . $rowUser['username'] . ">" . $rowUser['username'] . "</option>";
              }
              ?>
          </select>
        </div>
        <div class="col-2 py-4">
          <input class='btn btn-primary btn-sm' type='submit' name='SubmitUser' value='Select User' />
        </div>
      </div>
      </div>
    </form>




    <?php
    //#####################################################
    //
    // Check to see if user as Submitted the SITE form.
    //
    //#####################################################
    if (isset($_POST['SubmitSite'])) {
      $SelectedSite = $_POST['SiteName'];

      $querySite = "SELECT * FROM `site_audit_trail` WHERE IDX='$SelectedSite'";

      if (!$Site = $cGoat->doQuery($querySite)) {
        $msg = "Error: doQuery()";
        $cEagle->function_alert($msg);
      }
    }

    //#####################################################
    //
    // Check to see if user as Submitted the USER form.
    //
    //#####################################################
    else if (isset($_POST['SubmitUser'])) {
      $SelectedUser = $_POST['UserName'];

      $queryUser = "SELECT * FROM `site_audit_trail` WHERE done_by='$SelectedUser'";

      if (!$Site = $cGoat->doQuery($queryUser)) {
        $msg = "Error: doQuery()";
        $cEagle->function_alert($msg);
      }
    }

    if(isset($Site)){
    ?>
      <div class="px-5">

        <table class="fixed_header table table-striped">
          <thead>
            <tr>
              <th> SiteID </th>
              <th> Column_name </th>
              <th> old_value </th>
              <th> new_value </th>
              <th> Done By </th>
              <th> Date/Time </th>
            </tr>
          </thead>

        <?php
        while ($rowSite = $Site->fetch_assoc()) {
          echo "<tr><td>" .
            $rowSite["IDX"] . "</td><td>" .
            $rowSite["column_name"] . "</td><td>" .
            $rowSite["old_value"] . "</td><td>" .
            $rowSite["new_value"] . "</td><td>" .
            $rowSite["done_by"] . "</td><td>" .
            $rowSite["done_at"] . "</td></tr>";
        }
      }
        ?>
        </table>
      </div>
  </center>
  </div>
  <?php include('Footer.php'); ?>
</body>
</header>