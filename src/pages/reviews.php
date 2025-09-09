<?php
include(BASE_PATH . '/src/classes/cGOAT.php');
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

//*****************************************************************************//
//*****************************************************************************//
// Site ID needs to exist, this is used to determine which reviews are for which page.
if (isset($_GET['site_idx'])) {
  if (isset($_POST['name'], $_POST['rating'], $_POST['content'], $_POST['skill'])) {
    // Insert a new review (user submitted review form)
    //$stmt = $pdo->prepare('INSERT INTO reviews (site_idx, name, unit, content, rating, submit_date) VALUES (?,?,?,?,?,NOW())');
    $stmt = $pdo->prepare('INSERT INTO reviews (site_idx, name, unit, content, rating, skill_level, submit_date) VALUES (:site, :name, :unit, :content, :rating, :skill, NOW())');
    $stmt->bindParam(':site', $_GET['site_idx'], PDO::PARAM_INT);
    $stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR, 255);
    $stmt->bindParam(':unit', $_POST['unit'], PDO::PARAM_STR, 255);
    $stmt->bindParam(':rating', $_POST['rating'], PDO::PARAM_INT);
    $stmt->bindParam(':content', $_POST['content'], PDO::PARAM_STR, 255);
    $stmt->bindParam(':skill', $_POST['skill'], PDO::PARAM_INT);


    //$stmt->execute([$_GET['site_idx'], $_POST['name'], $_POST['unit'], $_POST['content'], $_POST['rating']]);
    $stmt->execute();
    //$stmt->debugDumpParams();
    // End the ouput below, no need to execute the code after that.
    exit('Your review has been submitted!');
  }
  // If the limit vaiables exist add the LIMIT clause to the SQL statement
  $limit = isset($_GET['current_pagination_page'], $_GET['reviews_per_pagination_page']) ? 'LIMIT :current_pagination_page,:reviews_per_pagination_page' : '';
  // By default order by the submit data (newest)
  $sort_by = 'ORDER BY submit_date DESC';
  if (isset($_GET['sort_by'])) {
    // User has changed the sort by, update the sort by variable
    $sort_by = $_GET['sort_by'] == 'newest' ? 'ORDER BY submit_date DESC' : $sort_by;
    $sort_by = $_GET['sort_by'] == 'oldest' ? 'ORDER BY submit_date ASC' : $sort_by;
    $sort_by = $_GET['sort_by'] == 'rating_highest' ? 'ORDER BY rating DESC' : $sort_by;
    $sort_by = $_GET['sort_by'] == 'rating_lowest' ? 'ORDER BY rating ASC' : $sort_by;
  }
  // Prepare statement that will secure our SQL
  $stmt = $pdo->prepare('SELECT * FROM reviews WHERE site_idx = :site_idx ' . $sort_by . ' ' . $limit);
  if ($limit) {
    // Determine which page the user is on and bind the value in to our SQL statement
    $stmt->bindValue(':current_pagination_page', ((int)$_GET['current_pagination_page'] - 1) * (int)$_GET['reviews_per_pagination_page'], PDO::PARAM_INT);
    // How many reviews will show on each pagination page
    $stmt->bindValue(':reviews_per_pagination_page', (int)$_GET['reviews_per_pagination_page'], PDO::PARAM_INT);
  }
  $stmt->bindValue(':site_idx', (int)$_GET['site_idx'], PDO::PARAM_INT);
  $stmt->execute();
  $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
  // Get the overall rating and total amount of reviews
  $stmt = $pdo->prepare('SELECT AVG(rating) AS overall_rating, COUNT(*) AS total_reviews FROM reviews WHERE site_idx = ?');
  $stmt->execute([$_GET['site_idx']]);
  $reviews_info = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
  exit('Please provide the page ID.');
}
?>

<div class="overall_rating">
  <span class="num"><?= number_format($reviews_info['overall_rating'], 1) ?></span>
  <span class="stars"><?= str_repeat('&#9733;', round($reviews_info['overall_rating'])) ?></span>
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

<div class="write_review">
  <form>
    <input name="name" type="text" placeholder="Your Name" required>
    <input name="unit" type="text" placeholder="Your Unit(i.e. Troop 0317-BT" required>
    <input name="rating" type="number" min="1" max="5" placeholder="Rating (1-5)" required>
    <label>Recommend Scout Skill Level
      <select class='form-control' id='skill' name='skill'>
        <option value="0"> </option>
        <?php
        echo "<option value='1'>Novice</option>";
        echo "<option value='2'>Advanced Beginner</option>";
        echo "<option value='3'>Competent</option>";
        echo "<option value='4'>Proficient</option>";
        echo "<option value='5'>Expert</option>";
        ?>
      </select>
    </label>
    <textarea name="content" placeholder="Write your review here..." required></textarea>
    <button type="submit">Submit Review</button>
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
      <a href="#" data-pagination_page="<?= $_GET['current_pagination_page'] - 1 ?>" data-records_per_page="<?= $_GET['reviews_per_pagination_page'] ?>">
        Prev
      </a>
    <?php endif; ?>
    <div>Page <?= $_GET['current_pagination_page'] ?></div>
    <?php if ($_GET['current_pagination_page'] * $_GET['reviews_per_pagination_page'] < $reviews_info['total_reviews']): ?>
      <a href="#" data-pagination_page="<?= $_GET['current_pagination_page'] + 1 ?>" data-records_per_page="<?= $_GET['reviews_per_pagination_page'] ?>">
        Next
      </a>
    <?php endif; ?>
  </div>
<?php endif; ?>