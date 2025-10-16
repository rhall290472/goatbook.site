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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <!-- DataTables Buttons CSS -->
    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css"> -->
    <!-- Bootstrap CSS for spinner (assuming Bootstrap is used site-wide) -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> -->
    <style>
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
    <!-- Loading overlay with Bootstrap spinner -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="px-3">
        <table id="siteTable" class="table table-striped">
            <thead>
                <tr>
                    <th>WKT</th>
                    <th>name</th>
                    <th>Area</th>
                    <th>Activity 1</th>
                    <th>Activity 2</th>
                    <th>Site Link</th>
                </tr>
            </thead>
            <tbody>
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
                    $SiteURL = "https://goatbook.site/index.php?page=displaycampsite&" . $SiteLink;

                    echo "<tr><td>" .
                        htmlspecialchars($WKT, ENT_QUOTES, 'UTF-8') . '</td><td>' .
                        htmlspecialchars(ucwords(strtolower($site['name'])), ENT_QUOTES, 'UTF-8') . '</td><td>' .
                        htmlspecialchars($cGOAT->GetAreaText($site['area']), ENT_QUOTES, 'UTF-8') . '</td><td>' .
                        htmlspecialchars($cGOAT->GetActivityText($site['type1']), ENT_QUOTES, 'UTF-8') . '</td><td>' .
                        htmlspecialchars($cGOAT->GetActivityText($site['type2']), ENT_QUOTES, 'UTF-8') . '</td><td>' .
                        htmlspecialchars($SiteURL, ENT_QUOTES, 'UTF-8') . '</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- jQuery, DataTables, DataTables Buttons, and PDF dependencies -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#siteTable').DataTable({
                "paging": false,
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
                        title: 'Sites',
                        className: 'btn btn-primary btn-sm'
                    },
                    {
                        extend: 'excel',
                        text: 'Export to Excel',
                        title: 'Sites',
                        className: 'btn btn-primary btn-sm'
                    },
                    {
                        extend: 'pdf',
                        text: 'Export to PDF',
                        title: 'Sites',
                        className: 'btn btn-primary btn-sm',
                        orientation: 'landscape',
                        pageSize: 'letter',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        },
                        customize: function(doc) {
                            doc.content[1].table.widths = Array(doc.content[1].table.body[0].length).fill('*');
                            doc.pageMargins = [20, 20, 20, 20];
                            doc.styles.tableHeader.fontSize = 10;
                            doc.styles.tableBodyEven.fontSize = 9;
                            doc.styles.tableBodyOdd.fontSize = 9;
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
                    $('#loadingOverlay').addClass('hidden');
                }
            });
        });
    </script>
</body>
</html>