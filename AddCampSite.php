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


  //#####################################################################
  //
  // Check to see if user as Submitted the form. If so, save the data..
  //
  //#####################################################################
  if (isset($_POST['SubmitForm'])) {
    if ($_POST['SubmitForm'] == "Cancel") {
      $cGOAT::GotoURL('./index.php');
      exit;
    }

    // Save New data..From the user form
    $FormData = array();
    $FormData['area'] = $cGOAT->GetFormData('element_1_1');
    $FormData['name'] = $cGOAT->GetFormData('element_1_2');
    $FormData['type1'] = $cGOAT->GetFormData('element_1_3');
    $FormData['type2'] = $cGOAT->GetFormData('element_1_4');

    $FormData['map'] = $cGOAT->GetFormData('element_2_1');
    $FormData['facilities'] = $cGOAT->GetFormData('element_2_2');

    $FormData['embedmap'] = $cGOAT->GetFormData('element_3_1');

    $FormData['directions'] = addslashes($cGOAT->GetFormData('Notes'));

    $cGOAT->InsertSite($FormData);
  }
  ?>



  <div class="row flex-wrap">
    <?php
    if ((isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]) !== true) {
    ?>
      <center>
        <p>You must have a account with the GOAT website and be logged on to add a campsite.</p>
        <a class="btn btn-primary btn-sm" href="./logon.php">Log On</a>
      </center>
    <?php
    } else {
      // Display a form to take the new camp site.
    ?>
      <div class="form-coach px-5" style="background-color: var(--scouting-lighttan);">
        <p style="text-align:Left"><b>Add Camp Site Information</b></p>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" id="coach-form" method="post">

          <div class="form-row">
            <div class="col-3">
              <label for=element_1_1>Area</label>
              <?php $cGOAT->DisplayArea(0, "element_1_1"); ?>
            </div>
            <div class="col-2">
              <label for=element_1_2>Name</label>
              <input type="text" name="element_1_2" class="form-control" <?php //if (strlen($Street) > 0) echo "value='" . $Street . "'"; 
                                                                          ?> />
            </div>
            <div class="col-3">
              <label for=element_1_3>Primary Activity</label>
              <?php $cGOAT->DisplayActivityType(0, "element_1_3"); ?>
            </div>
            <div class="col-3">
              <label for=element_1_4>Secondary Activity</label>
              <?php $cGOAT->DisplayActivityType(0, "element_1_4"); ?>
            </div>
          </div>

          <div class="form-row">
            <div class="col-4">
              <label for=element_2_1>Google Map Shared location</label>
              <input type="text" name="element_2_1" class="form-control" <?php //if (strlen($rowCoach['Email_Address']) > 0) echo "value=" . $rowCoach['Email_Address'];  
                                                                          ?> />
            </div>
            <div class="col-4">
              <label for=element_2_2>Facilities</label>
              <input type="text" name="element_2_2" class="form-control" <?php //if (strlen($rowCoach['Email_Address']) > 0) echo "value=" . $rowCoach['Email_Address'];  
                                                                          ?> />
            </div>
          </div>

          <div class="form-row">
            <div class="col-12">
              <label for=element_3_1>Google Embed Map</label>
              <input type="text" name="element_3_1" class="form-control" <?php //if (strlen($rowCoach['Email_Address']) > 0) echo "value=" . $rowCoach['Email_Address'];  
                                                                          ?> />
            </div>
          </div>
          <div class="form-row">
            <div class="col-12">
              <label for=Notes>Notes/How to get there</label>
              <textarea class="form-control" name="Notes" rows="10" style="height:100%;"><?php //if (strlen($rowCoach['Notes']) > 0) echo $rowCoach['Notes']; 
                                                                                          ?></textarea>
            </div>
          </div>
          <div class="form-row">
            <div class="col-10 py-5">
              <?php $ID = -1; ?>
              <?php //echo '<input type="hidden" name="Coachesid" value="' . $rowCoach['Coachesid'] . '"/>'; 
              ?>
              <input id="saveForm3" class="btn btn-primary btn-sm" type="submit" name="SubmitForm" value="Save" />
              <input id="saveForm4" class="btn btn-primary btn-sm" type="submit" name="SubmitForm" value="Cancel" />
            </div>
          </div>
        </form>
      </div>

    <?php
    }
    ?>
  </div>
  </div>



  <?php include('Footer.php'); ?>

</body>

</html>