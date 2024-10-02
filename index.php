<?php
if (!session_id()) {
  session_start();
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

  ?>
  <div class="container-fluid">
    <div class="row flex-nowrap">
      <div class="col-auto col-md-3 col-xl-auto px-sm-2 px-0 bg-dark">
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
            </li>
            <?php if (isset($_SESSION["type"]) && $_SESSION["type"] == "Admin") { ?>
              <li class="nav-item">
                <a href="#submenu4" data-bs-toggle="collapse" class="nav-link px-0 align-middle ">
                  <i class="fs-4 bi-book"></i> <span class="ms-1 d-none d-sm-inline">Admin</span></a>
                <ul class="collapse nav flex-column ms-1" id="submenu4" data-bs-parent="#menu">
                  <li class="w-100">
                    <a href="./ViewUsers.php" class="nav-link px-0"> <span class="d-none d-sm-inline">View Users</span></a>
                  </li>
                  <li>
                    <a href="./ViewErrros.php" class="nav-link px-0"> <span class="d-none d-sm-inline">View Error Log</span></a>
                  </li>
                  <li>
                    <a href="./AuditSite.php"  class="nav-link px-0"> <span class="d-none d-sm-inline">View Audit Log</span></a>
                  </li>
                </ul>
              </li>
            <?php } ?>
          </ul>
        </div>
      </div>
      <div class="col py-3">
        <h3>Guide to Outdoor Activities for Troops</h3>
        <p class="lead">
          This guide has been prepared for Scouts and Scouters in order to share campsites, hiking trails, and other activities that have been successfully tried by other units. This site is
          designed to be used by both new Scouters and the experienced Scouter; hopefully providing new experiences and locations to better enjoy the Colorado outdoors. The site has been designed
          to be printed on standard 8 1/2x11 paper, print outs of particular pages can be made and taken on your adventure. The GOAT Site has been grouped into chapters covering a geographic
          region or area of the state such as the Guanella Pass Area. Chapters have one or more maps merged into the text.</p>
        <ul class="list-unstyled">
          <li>
            <h5>Acknowledgement</h5>The Starting point of this web site is based on the 2000 GOAT Book which was create by then Denver Area Council, Order of the Arrow, Tahosa Loge which
            promotes Scout camping using several different methods. One of these methods is through the G.O.A.T. Book, which provides Scouts, Scouters and campers in general a guide to campsites
            (and activities) in Colorado.
          </li>
        </ul>
      </div>
    </div>
  </div>

  <?php include('Footer.php'); ?>

</body>

</html>