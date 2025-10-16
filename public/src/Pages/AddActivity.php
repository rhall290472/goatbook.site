<?php
// Secure session start
if (session_status() === PHP_SESSION_NONE) {
  session_start([
    'cookie_httponly' => true,
    'use_strict_mode' => true,
    'cookie_secure' => isset($_SERVER['HTTPS'])
  ]);
}

//include('cGOAT.php');
$cGOAT = cGOAT::getInstance();

/* Check if the user is already logged in, if yes then redirect him to welcome page */
if (!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)) {
  header("HTTP/1.0 403 Forbidden");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<body>
  <?php
  //#####################################################################
  //
  // Check to see if user as Submitted the form. If so, save the data..
  //
  //#####################################################################
  if (isset($_POST['SubmitForm'])) {
    if ($_POST['SubmitForm'] == "Cancel") {
      header("Location: index.php?page=home");
      exit;
    }

    // Save New data..From the user form
    $FormData = array();
    $FormData['type2'] = $cGOAT->GetFormData('element_1_1');

    if ($cGOAT->InsertActity($FormData))
      $_SESSION['feedback'] = ['type' => 'success', 'message' => 'New Activity Added.'];
    else
      $_SESSION['feedback'] = ['type' => 'danger', 'message' => 'Add New Activity Failed.'];

    header("Location: index.php?page=home");
    exit;
  }
  ?>



  <div class="row flex-wrap">
    <div class="form-coach px-5" style="background-color: var(--scouting-lighttan);">
      <p style="text-align:Left"><b>Add New Activity</b></p>
      <form action="index.php?page=addacitivity" id="coach-form" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? bin2hex(random_bytes(32))); ?>">
        <div class="form-row">
          <div class="col-3">
            <label for=element_1_1>New Activity</label>
            <input type="text" name="element_1_1" class="form-control" required />
          </div>
          <div class="col-3">
            <label for=element_1_2>Current Activities</label>
            <?php $cGOAT->DisplayActivityType(0, "element_1_2"); ?>
          </div>
        </div>
        <div class="form-row">
          <div class="col-10 py-3">
            <?php $ID = -1; ?>
            <?php //echo '<input type="hidden" name="Coachesid" value="' . $rowCoach['Coachesid'] . '"/>'; 
            ?>
            <input id="saveForm3" class="btn btn-primary btn-sm" type="submit" name="SubmitForm" value="Save" />
            <input id="saveForm4" class="btn btn-primary btn-sm" type="submit" name="SubmitForm" value="Cancel" />
          </div>
        </div>
      </form>
    </div>
  </div>
</body>

</html>