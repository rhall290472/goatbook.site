<?php
// Secure session start
if (session_status() === PHP_SESSION_NONE) {
  session_start([
    'cookie_httponly' => true,
    'use_strict_mode' => true,
    'cookie_secure' => isset($_SERVER['HTTPS'])
  ]);
}

// Load configuration
if (file_exists(__DIR__ . '/../../../config/config.php')) {
  require_once __DIR__ . '/../../../config/config.php';
} else {
  echo __DIR__ .'</br>';
  error_log("Unable to find file config.php @ ". __FILE__ . ' ' . __LINE__);
  http_response_code(500);
  die('An error occurred. Please try again later.');
}

include(BASE_PATH . '/public/src/Classes/cGOAT.php');
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

$pdo = $cGOAT->getPDOConn();
// Below function will convert datetime to time elapsed string.
function time_elapsed_string($datetime, $full = false)
{
  $now = new DateTime;
  $ago = new DateTime($datetime);
  $diff = $now->diff($ago);
  $w = floor($diff->d / 7);
  $diff->d -= $w * 7;
  $string = ['y' => 'year', 'm' => 'month', 'w' => 'week', 'd' => 'day', 'h' => 'hour', 'i' => 'minute', 's' => 'second'];
  foreach ($string as $k => &$v) {
    if ($k == 'w' && $w) {
      $v = $w . ' week' . ($w > 1 ? 's' : '');
    } else if (isset($diff->$k) && $diff->$k) {
      $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
    } else {
      unset($string[$k]);
    }
  }
  if (!$full) $string = array_slice($string, 0, 1);
  return $string ? implode(', ', $string) . ' ago' : 'just now';
}

// Handle review submission
if (isset($_GET['site_idx']) && isset($_POST['name'], $_POST['rating'], $_POST['content'], $_POST['skill'])) {
  try {
    $stmt = $pdo->prepare('INSERT INTO reviews (site_idx, name, unit, content, rating, skill_level, submit_date) VALUES (:site, :name, :unit, :content, :rating, :skill, NOW())');
    $stmt->bindParam(':site', $_GET['site_idx'], PDO::PARAM_INT);
    $stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR, 255);
    $stmt->bindParam(':unit', $_POST['unit'], PDO::PARAM_STR, 255);
    $stmt->bindParam(':rating', $_POST['rating'], PDO::PARAM_INT);
    $stmt->bindParam(':content', $_POST['content'], PDO::PARAM_STR, 255);
    $stmt->bindParam(':skill', $_POST['skill'], PDO::PARAM_INT);
    $stmt->execute();
    http_response_code(200);
    exit('Your review has been submitted!');
  } catch (PDOException $e) {
    error_log("Review submission error: " . $e->getMessage());
    http_response_code(500);
    exit('Failed to submit review. Please try again later.');
  }
}

// Validate site_idx
if (!isset($_GET['site_idx']) || !is_numeric($_GET['site_idx'])) {
  http_response_code(400);
  exit('Please provide a valid page ID.');
}

// Fetch reviews
try {
  $limit = isset($_GET['current_pagination_page'], $_GET['reviews_per_pagination_page']) ? 'LIMIT :current_pagination_page,:reviews_per_pagination_page' : '';
  $sort_by = 'ORDER BY submit_date DESC';
  if (isset($_GET['sort_by'])) {
    $sort_by = $_GET['sort_by'] == 'newest' ? 'ORDER BY submit_date DESC' : $sort_by;
    $sort_by = $_GET['sort_by'] == 'oldest' ? 'ORDER BY submit_date ASC' : $sort_by;
    $sort_by = $_GET['sort_by'] == 'rating_highest' ? 'ORDER BY rating DESC' : $sort_by;
    $sort_by = $_GET['sort_by'] == 'rating_lowest' ? 'ORDER BY rating ASC' : $sort_by;
  }
  $stmt = $pdo->prepare('SELECT * FROM reviews WHERE site_idx = :site_idx ' . $sort_by . ' ' . $limit);
  if ($limit) {
    $stmt->bindValue(':current_pagination_page', ((int)$_GET['current_pagination_page'] - 1) * (int)$_GET['reviews_per_pagination_page'], PDO::PARAM_INT);
    $stmt->bindValue(':reviews_per_pagination_page', (int)$_GET['reviews_per_pagination_page'], PDO::PARAM_INT);
  }
  $stmt->bindValue(':site_idx', (int)$_GET['site_idx'], PDO::PARAM_INT);
  $stmt->execute();
  $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Get overall rating and total reviews
  $stmt = $pdo->prepare('SELECT AVG(rating) AS overall_rating, COUNT(*) AS total_reviews FROM reviews WHERE site_idx = ?');
  $stmt->execute([$_GET['site_idx']]);
  $reviews_info = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  error_log("Review fetch error: " . $e->getMessage());
  http_response_code(500);
  exit('Failed to fetch reviews. Please try again later.');
}
?>

<style>
  .write_review {
    display: none;
  }

  .stars {
    color: gold;
  }

  .review {
    margin: 1em 0;
    padding: 1em;
    border: 1px solid #ddd;
    border-radius: 5px;
  }

  .con {
    margin: 1em 0;
  }

  .write_review .form-control {
    width: 100%;
  }
</style>

<div class="overall_rating">
  <span class="num"><?= number_format($reviews_info['overall_rating'] ?? 0.0, 1) ?></span>
  <span class="stars"><?= str_repeat('&#9733;', round($reviews_info['overall_rating'] ?? 0.0)) ?></span>
  <span class="total"><?= $reviews_info['total_reviews'] ?> reviews</span>
</div>

<div class="con">
  <a href="#" class="write_review_btn">Write Review</a>
  <span></span>
  <label for="sort_by">Sort By</label>
  <select class="sort_by" id="sort_by">
    <option value="newest" <?= isset($_GET['sort_by']) && $_GET['sort_by'] == 'newest' ? ' selected' : '' ?>>Newest</option>
    <option value="oldest" <?= isset($_GET['sort_by']) && $_GET['sort_by'] == 'oldest' ? ' selected' : '' ?>>Oldest</option>
    <option value="rating_highest" <?= isset($_GET['sort_by']) && $_GET['sort_by'] == 'rating_highest' ? ' selected' : '' ?>>Rating - High to Low</option>
    <option value="rating_lowest" <?= isset($_GET['sort_by']) && $_GET['sort_by'] == 'rating_lowest' ? ' selected' : '' ?>>Rating - Low to High</option>
  </select>
</div>

<div class="write_review" style="display: none;">
  <form>
    <div class="mb-3">
      <label for="name" class="form-label">Your Name</label>
      <input name="name" id="name" type="text" class="form-control" placeholder="Your Name" required>
    </div>
    <div class="mb-3">
      <label for="unit" class="form-label">Your Unit (e.g., Troop 0317-BT)</label>
      <input name="unit" id="unit" type="text" class="form-control" placeholder="Your Unit (e.g., Troop 0317-BT)" required>
    </div>
    <div class="mb-3">
      <label for="rating" class="form-label">Rating (1-5)</label>
      <input name="rating" id="rating" type="number" min="1" max="5" class="form-control" placeholder="Rating (1-5)" required>
    </div>
    <div class="mb-3">
      <label for="skill" class="form-label">Recommended Scout Skill Level</label>
      <select class="form-control" id="skill" name="skill" required>
        <option value="0" disabled selected>Select Skill Level</option>
        <option value="1">Novice</option>
        <option value="2">Advanced Beginner</option>
        <option value="3">Competent</option>
        <option value="4">Proficient</option>
        <option value="5">Expert</option>
      </select>
    </div>
    <div class="mb-3">
      <label for="content" class="form-label">Your Review</label>
      <textarea name="content" id="content" class="form-control" placeholder="Write your review here..." required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Submit Review</button>
  </form>
</div>

<?php foreach ($reviews as $review): ?>
  <div class="review">
    <h3 class="name"><?= htmlspecialchars($review['name'], ENT_QUOTES) ?></h3>
    <div>
      <span class="rating"><?= str_repeat('&#9733;', $review['rating']) ?></span>
      <span class="date"><?= time_elapsed_string($review['submit_date']) ?></span>
    </div>
    <p class="content"><?= htmlspecialchars($review['content'], ENT_QUOTES) ?></p>
  </div>
<?php endforeach; ?>

<?php if ($limit): ?>
  <div class="pagination">
    <?php if (isset($_GET['current_pagination_page']) && $_GET['current_pagination_page'] > 1): ?>
      <a href="#" data-pagination_page="<?= $_GET['current_pagination_page'] - 1 ?>" data-records_per_page="<?= $_GET['reviews_per_pagination_page'] ?>">Prev</a>
    <?php endif; ?>
    <div>Page <?= $_GET['current_pagination_page'] ?></div>
    <?php if ($_GET['current_pagination_page'] * $_GET['reviews_per_pagination_page'] < $reviews_info['total_reviews']): ?>
      <a href="#" data-pagination_page="<?= $_GET['current_pagination_page'] + 1 ?>" data-records_per_page="<?= $_GET['reviews_per_pagination_page'] ?>">Next</a>
    <?php endif; ?>
  </div>
<?php endif; ?>