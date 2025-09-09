    <header id="header" class="header sticky-top">

      <!-- Responsive navbar-->
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container px-lg-5  d-print-none">
          <a class="navbar-brand" href="#!">A Guide to Outdoor Activities for Troops</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
              <li class="nav-item"><a class="nav-link" aria-current="page" href="./index.php">Home</a></li>

              <li class="nav-item"><a class="nav-link" aria-current="page" href="./CampSites.php">Activities</a></li>

              <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#submenu3" role="button" aria-expanded="false" aria-controls="submenu3" class="nav-link px-0 align-middle">
                  <span class="ms-1 d-none d-sm-inline">The GOAT Book</span>
                </a>
                <ul class="collapse nav flex-column ms-1" id="submenu3" data-bs-parent="#menu">
                  <li class="w-100">
                    <a href="./Book/GoatBook_2000_OCR.pdf" target="_blank" class="nav-link px-0"><span class="d-none d-sm-inline">2000</span></a>
                  </li>
                  <li>
                    <a href="./Book/GoatBook_1995_OCR.pdf" target="_blank" class="nav-link px-0"><span class="d-none d-sm-inline">1995</span></a>
                  </li>
                  <li>
                    <a href="./Book/Tahosa Lodge 383 History Booklet 1948-1988.pdf" target="_blank" class="nav-link px-0"><span class="d-none d-sm-inline">Tahosa Lodge 383 History</span></a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#submenu4" role="button" aria-expanded="false" aria-controls="submenu4" class="nav-link px-0 align-middle">
                  <span class="ms-1 d-none d-sm-inline">Scouting References</span>
                </a>
                <ul class="collapse nav flex-column ms-1" id="submenu4" data-bs-parent="#menu">
                  <li class="w-100">
                    <a href="https://filestore.scouting.org/filestore/HealthSafety/pdf/680-685.pdf" target="_blank" class="nav-link px-0"><span class="d-none d-sm-inline">Age Appropriate Guidelines</span></a>
                  </li>
                  <li>
                    <a href="https://www.scouting.org/programs/cub-scouts/activities/cub-scout-camping/" target="_blank" class="nav-link px-0"><span class="d-none d-sm-inline">Camping and Outdoor Activities</span></a>
                  </li>
                  <li>
                    <a href="https://www.scouting.org/health-and-safety/gss/gss03/" target="_blank" class="nav-link px-0"><span class="d-none d-sm-inline">Guide to Safe Scouting: Camping</span></a>
                  </li>
                  <li>
                    <a href="https://www.scouting.org/trail-to-adventure-blog/cub-scout-camping-program-and-policy-updates/" target="_blank" class="nav-link px-0"><span class="d-none d-sm-inline">Cub Scout Camping Program and Policy: 2024 Updates</span></a>
                  </li>
                </ul>
              </li>

              <li class="nav-item"><a class="nav-link" href="./About.php">About</a></li>
              <li class="nav-item"><a class="nav-link" href='./Contact.php'>Contact</a></li>

              <!-- SO, the state of the user being logged in or not. -->
              <?php
              if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
                echo '<li class="nav-item"><a class="nav-link" href="./logoff.php">Log off</a></li>';
              } else {
                echo '<li class="nav-item"><a class="nav-link" href="./logon.php">Log on</a></li>';
              }
              ?>
            </ul>
          </div>
        </div>
      </nav>
    </header>