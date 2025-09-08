<?php
if (!session_id()) {
  session_start();
}

  // Load configuration
  if (file_exists(__DIR__ . '/config/config.php')) {
    require_once __DIR__ . '/config/config.php';
  } else {
    echo __DIR__;
    die('An error occurred. Please try again later.');
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
  <!-- Adding Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/styles.css" rel="stylesheet" />
  <!-- Adding DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
  <!-- Adding DataTables Buttons CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
  <style>
    /* Ensure DataTables buttons match SelectCampSite button style (red background, white text) */
    .dt-buttons .btn {
      margin-right: 5px;
    }

    .dt-buttons .btn-primary {
      background-color: #dc3545 !important;
      /* Red background, matching assumed styles.css */
      border-color: #dc3545 !important;
      color: #fff !important;
      /* White text */
      font-size: 0.875rem !important;
      padding: 0.25rem 0.5rem !important;
    }

    .dt-buttons .btn-primary:hover {
      background-color: #c82333 !important;
      /* Darker red on hover */
      border-color: #bd2130 !important;
      color: #fff !important;
    }

    /* Loading overlay styles */
    .loading-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.8);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }

    .loading-overlay.hidden {
      display: none;
    }
  </style>
</head>

<body>
  <?php
  load_template('/navbar.php');

  $cGOAT->SelectCampSite();

  // Check which type of camp view they wish to view
  if (isset($_POST['SubmitArea']) && isset($_POST['Area'])) {
    $_SESSION["campselectionArea"] = $_POST['Area'];
    unset($_SESSION["campselectionActivity"]);
    // create a table of all of the campsites selected
    $area = $_POST['Area'];
    // If no area select display default data
    if ($area == 0)
      $sql = "SELECT * FROM `site` WHERE (`IsDeleted` IS NULL OR `IsDeleted` <> '1') ORDER BY name ASC";
    else
      $sql = "SELECT * FROM `site` WHERE area = '" . $area . "' AND (`IsDeleted` IS NULL OR `IsDeleted` <> '1') ORDER BY area ASC, name ASC";
  } else if (isset($_POST['SubmitActivityType'])) {
    $_SESSION["campselectionActivity"] = $_POST['Type'];
    unset($_SESSION["campselectionArea"]);
    // create a table by select activity
    $type = $_POST['Type'];
    if ($type == 0)
      $sql = "SELECT * FROM `site` WHERE (`IsDeleted` IS NULL OR `IsDeleted` <> '1') ORDER BY name ASC";
    else
      $sql = "SELECT * FROM `site` WHERE (`type1` = '" . $type . "' OR `type2` = '" . $type . "') AND (`IsDeleted` IS NULL OR `IsDeleted` <> '1') ORDER BY area ASC, name ASC";
  } else if (isset($_SESSION["campselectionArea"])) {
    $area = $_SESSION["campselectionArea"];
    if ($area == 0)
      $sql = "SELECT * FROM `site` WHERE (`IsDeleted` IS NULL OR `IsDeleted` <> '1') ORDER BY name ASC";
    else
      $sql = "SELECT * FROM `site` WHERE area = '" . $area . "' AND (`IsDeleted` IS NULL OR `IsDeleted` <> '1') ORDER BY name ASC";
  } else if (isset($_SESSION["campselectionActivity"])) {
    $type = $_SESSION["campselectionActivity"];
    $sql = "SELECT * FROM `site` WHERE (`type1` = '" . $type . "' OR `type2` = '" . $type . "') AND (`IsDeleted` IS NULL OR `IsDeleted` <> '1') ORDER BY area ASC, name ASC";
  } else {
    // This will be the default view, all the sites sorted by name
    $sql = "SELECT * FROM `site` WHERE (`IsDeleted` IS NULL OR `IsDeleted` <> '1') ORDER BY `name` ASC";
  }

  if (isset($sql)) {
  ?>
    <!-- Loading overlay with Bootstrap spinner -->
    <div class="loading-overlay" id="loadingOverlay">
      <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div class="px-3">
      <table id="campSitesTable" class="table table-striped">
        <thead>
          <tr>
            <th>Area</th>
            <th>Name</th>
            <th>Primary Activity</th>
            <th>Secondary Activity</th>
            <th>Rating</th>
            <th>Last Reviewed</th>
            <th>Scout Skill Level</th>
            <th>Has Info</th>
            <th>Has Map</th>
            <th>Facilities</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if (!$CampSite = $cGOAT->doQuery($sql)) {
            $msg = "Error: doQuery()";
            $cGOAT->function_alert($msg);
            $cGOAT::GotoURL('./index.php');
          }

          while ($row = $CampSite->fetch_assoc()) {
            echo "<tr><td>" .
              $cGOAT->GetAreaText($row["area"]) . "</td><td>" .
              "<a href=./DisplayCampSite.php?Siteid=" . $row['IDX'] . ">" . ucwords(strtolower($row["name"])) . "</a> </td><td>" .
              $cGOAT->GetActivityText($row["type1"]) . "</td><td>" .
              $cGOAT->GetActivityText($row["type2"]) . "</td><td>" .
              $cGOAT->GetRating($row["IDX"]) . "</td><td>" .
              $cGOAT->GetLastReviewd($row["IDX"]) . "</td><td>" .
              $cGOAT->GetSkillLevel($row["IDX"]) . "</td><td>" .
              $cGOAT->HasInfo($row["map"]) . "</td><td>" .
              $cGOAT->HasMap($row["embedmap"]) . "</td><td>" .
              $row["facilities"] . "</td></tr>";
          }
          ?>
        </tbody>
      </table>
      <b>For a total of <?php echo mysqli_num_rows($CampSite); ?> sites</b>
    </div>

  <?php
  }
  ?>

  <!-- Adding jQuery, DataTables, and DataTables Buttons JS -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#campSitesTable').DataTable({
        "paging": false,
        "pageLength": 10,
        "searching": true,
        "ordering": true,
        "info": true,
        "columnDefs": [{
          "orderable": true,
          "targets": "_all"
        }],
        "dom": 'Bfrtip',
        "buttons": [{
            extend: 'csv',
            text: 'Export to CSV',
            title: 'Campsites',
            className: 'btn btn-primary btn-sm'
          },
          {
            extend: 'excel',
            text: 'Export to Excel',
            title: 'Campsites',
            className: 'btn btn-primary btn-sm'
          }
        ],
        "initComplete": function() {
          // Hide the loading overlay when the table is fully initialized
          $('#loadingOverlay').addClass('hidden');
        }
      });
    });
  </script>

  <?php //include('Footer.php'); ?>

</body>

</html>