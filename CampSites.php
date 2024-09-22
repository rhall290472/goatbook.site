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

  $cGOAT->SelectCampSite();

  // Check which type of camp view they wish to view
  if (isset($_POST['SubmitArea']) && isset($_POST['Area'])) {
    $_SESSION["campselectionArea"]=$_POST['Area'];
    unset($_SESSION["campselectionActivity"]);
    // create a table of all of the campsites selected
    $area = $_POST['Area'];
    // If no area select display default data
    if($area == 0)
      $sql = "SELECT * FROM `site` WHERE (`IsDeleted` IS NULL OR `IsDeleted` <> '1') ORDER BY name ASC";
    else
      $sql = "SELECT * FROM `site` WHERE area = '" . $area . "' AND (`IsDeleted` IS NULL OR `IsDeleted` <> '1') ORDER BY area ASC, name ASC";

  } else if (isset($_POST['SubmitActivityType'])) {
    $_SESSION["campselectionActivity"]=$_POST['Type'];
    unset($_SESSION["campselectionArea"]);
    // create a table by select activity
    $type = $_POST['Type'];
    if($type == 0)
      $sql = "SELECT * FROM `site` WHERE (`IsDeleted` IS NULL OR `IsDeleted` <> '1') ORDER BY name ASC";
    else
      $sql = "SELECT * FROM `site` WHERE (`type1` = '" . $type . "' OR `type2` = '" . $type . "') AND (`IsDeleted` IS NULL OR `IsDeleted` <> '1') ORDER BY area ASC, name ASC";
  } else if (isset($_SESSION["campselectionArea"])){
    $area =$_SESSION["campselectionArea"];
    if($area == 0)
      $sql = "SELECT * FROM `site` WHERE (`IsDeleted` IS NULL OR `IsDeleted` <> '1') ORDER BY name ASC";
    else
      $sql = "SELECT * FROM `site` WHERE area = '" . $area . "' AND (`IsDeleted` IS NULL OR `IsDeleted` <> '1') ORDER BY name ASC";
  }else if (isset($_SESSION["campselectionActivity"])){
    $type = $_SESSION["campselectionActivity"];
    $sql = "SELECT * FROM `site` WHERE (`type1` = '" . $type . "' OR `type2` = '" . $type . "') AND (`IsDeleted` IS NULL OR `IsDeleted` <> '1') ORDER BY area ASC, name ASC";
  }else {
    // This will be the default view, all the sites sorted by area
    $sql = "SELECT * FROM `site` WHERE (`IsDeleted` IS NULL OR `IsDeleted` <> '1') ORDER BY `name` ASC";
  }

  if (isset($sql)) {
  ?>
   
    



    <div class="px-3">
      <table class="fixed_header table table-striped">
        <thead>
          <tr>
            <th>Area</th>
            <th>Name</th>
            <th>Primary Activity</th>
            <th>Secondary Activity</th>
            <th>rating</th>
            <th>Last Reviewed</th>
            <th>Scout Skill Level</th>
            <th>facilities</th>
          </tr>
        </thead>
      <?php
      if (!$CampSite = $cGOAT->doQuery($sql)) {
        $msg = "Error: doQuery()";
        $cGOAT->function_alert($msg);
        $cGOAT::GotoURL('./index.php');
      }

      echo "<tbody>";
      while ($row = $CampSite->fetch_assoc()) {
        echo "<tr><td>" .
          $cGOAT->GetAreaText($row["area"]) . "</td><td>" .
          "<a href=./DisplayCampSite.php?Siteid=" . $row['IDX'] . ">" . $row["name"] . "</a> </td><td>" .
          $cGOAT->GetActivityText($row["type1"]) . "</td><td>" .
          $cGOAT->GetActivityText($row["type2"]) . "</td><td>" .
          $cGOAT->GetRating($row["IDX"])."</td><td>" .
          $cGOAT-> GetLastReviewd($row["IDX"])."</td><td>" .
          $cGOAT-> GetSkillLevel($row["IDX"])."</td><td>" .
          $row["facilities"] . "</td></tr>";
      }
      echo "</tbody>";
      echo "</table>";
      echo "<b>For a total of " . mysqli_num_rows($CampSite) . "</b>";
    }
      ?>
    </div>




    <?php include('Footer.php'); ?>

</body>

</html>