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
!\\                                                                          \\!
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
  <?php //include(BASE_PATH.'/src/Templates/head.php'); 
  ?>
  <!-- Adding DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
</head>

<body>
  <?php
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
              "<a href=?page=displaycampsite&Siteid=" . $row['IDX'] . ">" . ucwords(strtolower($row["name"])) . "</a> </td><td>" .
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
    </div>

  <?php
  }
  ?>

<!-- Adding jQuery, DataTables, DataTables Buttons, and PDF dependencies -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
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
        "buttons": [
          {
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
          },
          {
            extend: 'pdf',
            text: 'Export to PDF',
            title: 'Campsites',
            className: 'btn btn-primary btn-sm',
            orientation: 'landscape', // Better for wide tables
            pageSize: 'letter',
            exportOptions: {
              columns: [0, 1, 2, 3, 5, 9]
            },
            customize: function(doc) {
              // Use '*' for automatic column widths to fit page
              doc.content[1].table.widths = Array(doc.content[1].table.body[0].length).fill('*');
              // Adjust page margins to maximize table width
              doc.pageMargins = [20, 20, 20, 20]; // [left, top, right, bottom]
              // Font sizes for readability
              doc.styles.tableHeader.fontSize = 10;
              doc.styles.tableBodyEven.fontSize = 9;
              doc.styles.tableBodyOdd.fontSize = 9;
              // Ensure table uses full width
              doc.content[1].table.layout = {
                hLineWidth: function(i, node) { return 0.5; },
                vLineWidth: function(i, node) { return 0.5; },
                paddingLeft: function(i, node) { return 4; },
                paddingRight: function(i, node) { return 4; },
                paddingTop: function(i, node) { return 2; },
                paddingBottom: function(i, node) { return 2; }
              };
            }
          }
        ],
        "initComplete": function() {
          // Hide the loading overlay when the table is fully initialized
          $('#loadingOverlay').addClass('hidden');
        }
      });
    });
  </script>
</body>

</html>