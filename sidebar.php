<?php
// Load configuration
if (file_exists(__DIR__ . '/config/config.php')) {
  require_once __DIR__ . '/config/config.php';
} else {
  echo __DIR__;
  die('An error occurred. Please try again later.');
}
?>
<div class="col-auto col-md-3 col-xl-auto px-sm-2 px-0 bg-dark">
  <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
    <a href="/" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
      <span class="fs-5 d-none d-sm-inline">Menu</span>
    </a>
    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
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
      <li class="nav-item">
        <a href="#submenu4" data-bs-toggle="collapse" class="nav-link px-0 align-middle ">
          <i class="fs-4 bi-book"></i> <span class="ms-1 d-none d-sm-inline">Scouting References</span></a>
        <ul class="collapse nav flex-column ms-1" id="submenu4" data-bs-parent="#menu">
          <li class="w-100">
            <a href="https://filestore.scouting.org/filestore/HealthSafety/pdf/680-685.pdf" target="_blank" class="nav-link px-0"> <span class="d-none d-sm-inline">Age Appropiate Gudelines</span></a>
          </li>
          <li>
            <a href="https://www.scouting.org/programs/cub-scouts/activities/cub-scout-camping/" target="_blank" class="nav-link px-0"> <span class="d-none d-sm-inline">Camping and Outdoor Activities</span></a>
          </li>
          <li>
            <a href="https://www.scouting.org/health-and-safety/gss/gss03/" target="_blank" class="nav-link px-0"> <span class="d-none d-sm-inline">Guide to Safe Scouting: Camping</span></a>
          </li>
          <li>
            <a href="https://www.scouting.org/trail-to-adventure-blog/cub-scout-camping-program-and-policy-updates/" target="_blank" class="nav-link px-0"> <span class="d-none d-sm-inline">Cub Scout Camping Program and Policy: 2024 Updates</span></a>
          </li>
        </ul>
      </li>
      <?php if (isset($_SESSION["type"]) && $_SESSION["type"] == "Admin") { ?>
        <li class="nav-item">
          <a href="#submenu5" data-bs-toggle="collapse" class="nav-link px-0 align-middle ">
            <i class="fs-4 bi-book"></i> <span class="ms-1 d-none d-sm-inline">Admin</span></a>
          <ul class="collapse nav flex-column ms-1" id="submenu5" data-bs-parent="#menu">
            <li class="w-100">
              <a href="./ViewUsers.php" class="nav-link px-0"> <span class="d-none d-sm-inline">View Users</span></a>
            </li>
            <li>
              <a href="./ViewErrors.php" class="nav-link px-0"> <span class="d-none d-sm-inline">View Error Log</span></a>
            </li>
          </ul>
        </li>
      <?php } ?>
    </ul>
  </div>

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
      $repo = "centennial";
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

    <?php echo "</br>Copyright &copy; " . date('Y') . " " . $_SERVER['HTTP_HOST']; ?>

  </div>
</div>