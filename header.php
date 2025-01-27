    <header id="header" class="header sticky-top">

      <!-- Responsive navbar-->
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container px-lg-5  d-print-none">
          <a class="navbar-brand" href="#!">A "Wikipedia" Guide to Outdoor Activities for Troops</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
              <li class="nav-item"><a class="nav-link" aria-current="page" href="./index.php">Home</a></li>
              <li class="nav-item"><a class="nav-link" href="./About.php">About</a></li>
              <li class="nav-item"><a class="nav-link" href='mailto:webmaster@goatbook.site'>Contact</a></li>
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