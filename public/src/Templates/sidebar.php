<!-- Sidebar -->
<div class="sidebar d-flex flex-column flex-shrink-0 p-3 bg-light" id="sidebar">
  <a href="?page=home" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
    <span class="fs-4">Menu</span>
  </a>
  <hr>
  <ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
      <a class="nav-link align-middle link-dark"
        <?php echo $page === 'campsites' ? 'active' : ''; ?> href="?page=campsites">
        <i class="fs-4 bi bi-list"></i> <span class="ms-1 d-none d-sm-inline">Activities</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link align-middle link-dark"
        <?php echo $page === 'addcampsite' ? 'active' : ''; ?> href="?page=addcampsite">
        <i class="fs-4 bi bi-plus-circle"></i> <span class="ms-1 d-none d-sm-inline">Add an Activity</span>
      </a>
    </li>


    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle  link-dark" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fs-4 bi-book"></i><span class="ms-1 d-none d-sm-inline">Goat Book</span>
      </a>
      <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/Book/GoatBook_2000_OCR.pdf" target="_blank">2000</a></li>
        <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/Book/GoatBook_1995_OCR.pdf" target="_blank">1995</a></li>
        <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/Book/Tahosa_Lodge_383.pdf" target="_blank">Tahosa Lodge 383 History</a></li>
      </ul>
    </li>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle  link-dark" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fs-4 bi bi-link"></i><span class="ms-1 d-none d-sm-inline">Scouting References</span>
      </a>
      <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="https://filestore.scouting.org/filestore/HealthSafety/pdf/680-685.pdf" target="_blank">Age Appropriate Guidelines</a></li>
        <li><a class="dropdown-item" href="https://www.scouting.org/programs/cub-scouts/activities/cub-scout-camping/" target="_blank">Camping and Outdoor Activities</a></li>
        <li><a class="dropdown-item" href="https://www.scouting.org/health-and-safety/gss/gss03/" target="_blank">Safe Scouting: Camping</a></li>
        <li><a class="dropdown-item" href="https://www.scouting.org/trail-to-adventure-blog/cub-scout-camping-program-and-policy-updates/" target="_blank">Cub Scout Camping Program</a></li>
      </ul>
    </li> <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
      <li class=" nav-item">
        <a class="nav-link link-dark" href="?page=logout"><i class="fs-4 bi bi-person"></i>Logout</a>
      </li>
    <?php else: ?>
      <li class="nav-item">
        <a class="nav-link  link-dark <?php echo $page === 'login' ? 'active' : ''; ?>" href="?page=login"><i class="fs-4 bi bi-person"></i>Login</a>
      </li>
    <?php endif; ?>
    <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fs-4 bi-backpack4"></i><span class="ms-1 d-none d-sm-inline text-danger">Admin</span>
        </a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="?page=viewusers">View Users</a></li>
          <li><a class="dropdown-item" href="?page=viewlog">View Error Log</a></li>
          <li><a class="dropdown-item" href="?page=addarea">Add Area</a></li>
          <li><a class="dropdown-item" href="?page=addacitivity">Add Activity</a></li>
          <li><a class="dropdown-item" href="?page=auditsite">Audit Site</a></li>
        </ul>
      </li>
    <?php endif; ?>
  </ul>
  <button class="btn btn-outline-secondary mt-3 d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar" aria-expanded="false" aria-controls="sidebar">
    Close Sidebar
  </button>

  <!-- Footer with GitHub repository commit date -->
  <div class="mt-auto text-muted small">
    <?php
    $cache_file = 'last_updated.txt';
    $cache_duration = 24 * 60 * 60; // 24 hours in seconds
    $commit_date = null;
    $http_code = 0; // Initialize to track HTTP status

    // Check if cache exists and is recent
    if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $cache_duration) {
      $commit_date = file_get_contents($cache_file);
    } else {
      // GitHub API settings
      $owner = "rhall290472";
      $repo = "goatbook.site";
      $api_url = "https://api.github.com/repos/$owner/$repo/commits?per_page=1";
      $token = defined('GITHUB_TOKEN') ? GITHUB_TOKEN : ''; // Load token from config.php

      $ch = curl_init($api_url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_USERAGENT, "PHP-App/1.0");
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Accept: application/vnd.github.v3+json"
      ]);
      if (!empty($token)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
          "Accept: application/vnd.github.v3+json",
          "Authorization: token $token"
        ]);
      }
      $response = curl_exec($ch);
      $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);

      if ($http_code == 200 && $response) {
        $commits = json_decode($response, true);
        if (!empty($commits)) {
          $commit_date = $commits[0]['commit']['committer']['date'];
          // Save to cache
          file_put_contents($cache_file, $commit_date);
        }
      } else {
        // Log error for debugging
        error_log("GitHub API error: HTTP $http_code, Response: $response, URL: $api_url, Token used: " . (empty($token) ? 'none' : 'provided'));
      }
    }

    // Display the date
    if ($commit_date) {
      $formatted_date = date("F j, Y", strtotime($commit_date));
      echo "Last updated: " . htmlspecialchars($formatted_date);
    } else {
      if ($http_code == 403) {
        echo "Last updated: Unknown (token lacks permissions or organization restrictions)";
      } elseif ($http_code == 401) {
        echo "Last updated: Unknown (invalid or missing token)";
      } elseif ($http_code == 404) {
        echo "Last updated: Unknown (repository not found)";
      } else {
        echo "Last updated: Unknown (API error)";
      }
    }
    ?>

    <?php echo "Copyright &copy; " . date('Y') . " " . $_SERVER['HTTP_HOST']; ?>

  </div>
</div>