<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="?page=home"><?php echo PAGE_TITLE; ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link <?php echo $page === 'home' ? 'active' : ''; ?>" href="?page=home">
            <i class="fs-4 bi bi-house"></i> Home
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?php echo $page === 'campsites' ? 'active' : ''; ?>" href="?page=campsites">
            <i class="fs-4 bi bi-list"></i> Activities
          </a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fs-4 bi-book"></i> Goat Book</span>
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/Book/GoatBook_2000_OCR.pdf" target="_blank">2000</a></li>
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/Book/GoatBook_1995_OCR.pdf" target="_blank">1995</a></li>
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/Book/Tahosa_Lodge_383.pdf" target="_blank">Tahosa Lodge 383 History</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fs-4 bi bi-link"></i> Scouting References</span>
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="https://filestore.scouting.org/filestore/HealthSafety/pdf/680-685.pdf" target="_blank">Age Appropriate Guidelines</a></li>
            <li><a class="dropdown-item" href="https://www.scouting.org/programs/cub-scouts/activities/cub-scout-camping/" target="_blank">Camping and Outdoor Activities</a></li>
            <li><a class="dropdown-item" href="https://www.scouting.org/health-and-safety/gss/gss03/" target="_blank">Safe Scouting: Camping</a></li>
            <li><a class="dropdown-item" href="https://www.scouting.org/trail-to-adventure-blog/cub-scout-camping-program-and-policy-updates/" target="_blank">Cub Scout Camping Program</a></li>
          </ul>
        </li> 
        <li class="nav-item">
          <a class="nav-link <?php echo $page === 'about' ? 'active' : ''; ?>" href="?page=about">
            <i class="fs-4 bi bi-file-person"></i>About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo $page === 'contact' ? 'active' : ''; ?>" href="?page=contact">
            <i class="fs-4 bi bi-person-lines-fill"></i>Contact</a>
        </li>
        
        <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
          <li class=" nav-item">
            <a class="nav-link <?php echo $page === 'login' ? 'active' : ''; ?>" href="?page=logout">
              <i class="fs-4 bi bi-person"></i>Logout</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="?page=login">
              <i class="fs-4 bi bi-person"></i>Login</a>
          </li>
        <?php endif; ?>
        <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fs-4 bi-backpack4"></i> <span class="text-danger">Admin</span>
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="?page=viewusers">View Users</a></li>
              <li><a class="dropdown-item" href="?page=viewlog">View Error Log</a></li>
            </ul>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>