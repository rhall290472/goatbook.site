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

    // Get a life of active life scouts..
    $querySite = "SELECT * FROM site ORDER BY name";

    $result_Site = $cGoat->doQuery($querySite);
    if (!$result_Site) {
      // Error should be reported by doQuery
      $cGOAT->GoToURL('./index.php');
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
      </div>
      </div>
    </form>
    <?php
    //#####################################################
    //
    // Check to see if user as Submitted the form.
    //
    //#####################################################
    if (isset($_POST['SubmitSite'])) {
      $SelectedSite = $_POST['SiteName'];

      $querySite = "SELECT * FROM `site_audit_trail` WHERE IDX='$SelectedSite'";

      if (!$Site = $cGoat->doQuery($querySite)) {
        $msg = "Error: doQuery()";
        $cEagle->function_alert($msg);
      }

    ?>
      <div class="px-5">

        <table class="fixed_header table table-striped">
          <thead>
            <tr>
              <th> ScoutID </th>
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