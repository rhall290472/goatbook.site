<?php
if (!session_id()) {
  session_start();

  include('cGOAT.php');
  $cGOAT = cGOAT::getInstance();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include('head.php'); ?>
</head>

<body>
  <?php
  include_once('header.php');
  //include_once('navbar.php');


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
      <div class="col-auto col-md-3 col-xl-auto px-sm-2 px-0 bg-dark d-print-none">
        <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
          <a href="/" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class="fs-5 d-none d-sm-inline">Menu</span>
          </a>
          <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
            <li class="nav-item">
              <a href="./index.php" class="nav-link align-middle px-0">
                <i class="fs-4 bi-house"></i> <span class="ms-1 d-none d-sm-inline">Home</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="./CampSites.php" class="nav-link align-middle px-0">
                <i class="fs-4 bi-eye"></i> <span class="ms-1 d-none d-sm-inline">Activites</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="./AddCampSite.php" class="nav-link align-middle px-0">
                <i class="fs-4 bi-bookmark-check"></i> <span class="ms-1 d-none d-sm-inline">Add a Activites</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="#submenu3" data-bs-toggle="collapse" class="nav-link px-0 align-middle ">
                <i class="fs-4 bi-book"></i> <span class="ms-1 d-none d-sm-inline">The GOAT Book</span></a>
              <ul class="collapse nav flex-column ms-1" id="submenu3" data-bs-parent="#menu">
                <li class="w-100">
                  <a href="./Book/GoatBook_2000_OCR.pdf" target="_blank" class="nav-link px-0"> <span class="d-none d-sm-inline">2000</span></a>
                </li>
                <li>
                  <a href="./Book/GoatBook_1995_OCR.pdf" target="_blank" class="nav-link px-0"> <span class="d-none d-sm-inline">1995</span></a>
                </li>
              </ul>
          </ul>
        </div>
      </div>
      <div class="col py-3">
        <!-- Page content Here -->
        <div class="container px-3">
          <div class="row gx-lg-3">

            <div class="col-lg-10 col-xxl-10 mb-3">
              <h1><?php echo isset($Site['area']) ? $cGOAT->GetAreaText($Site['area']) : "" ?></h1>
              <h2><?php
                  echo $Site['name'] . " - ";
                  echo $cGOAT->GetActivityText($Site['type1']);
                  if ($Site['type2'] != 0) {
                    echo " / " . $cGOAT->GetActivityText($Site['type2']);
                  }
                  ?></h2>

              <p><?php echo "Facilities: " . $Site['facilities']; ?></p>
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