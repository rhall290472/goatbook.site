<?php
if (!session_id()) {
  session_start();

  include('cGOAT.php');
  $cGOAT = cGOAT::getInstance();
}
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include('head.php'); ?>
</head>

<body>
  <?php
  include_once('header.php');


  //#####################################################
  //
  // Check to see if user as Submitted the form. If so, save the data..
  //
  //#####################################################
  if (isset($_POST['SubmitForm'])) {
    if ($_POST['SubmitForm'] == "Cancel") {
      $cGOAT->GoToURL('./index.php');
      exit;
    }

    // Get the old data to compare with later.
    $SiteOld = $_POST['Siteid'];
    $sql = "SELECT * from `site` WHERE `IDX`='" . $SiteOld . "'";
    $ResultOld = $cGOAT->doQuery($sql);
    if ($ResultOld)
      $SiteOldData = $ResultOld->fetch_assoc();

    // Save New data..From the user form
    $FormData = array();
    $FormData['IDX'] = $_POST['Siteid'];
    $FormData['area'] =  $cGOAT->GetFormData('element_1_1');
    $FormData['name'] =  $cGOAT->GetFormData('element_1_2') == "" ? 0 : ucwords(strtolower($cGOAT->GetFormData('element_1_2')));
    $FormData['type1'] =  $cGOAT->GetFormData('element_1_3') == "" ? 0 : $cGOAT->GetFormData('element_1_3');
    $FormData['type2'] =  $cGOAT->GetFormData('element_1_4') == "" ? 0 : $cGOAT->GetFormData('element_1_4');;
    $FormData['map'] =  $cGOAT->GetFormData('element_2_1');
    $FormData['facilities'] =  $cGOAT->GetFormData('element_2_2');
    $FormData['IsDeleted'] =  $cGOAT->GetFormData('element_2_3');
    $FormData['embedmap'] =  $cGOAT->GetFormData('element_3_1');
    $FormData['directions'] = addslashes($cGOAT->GetFormData('Notes'));

    if ($cGOAT->UpdateSite($FormData)) {
      // Record has been updated in database now create a audit trail
      $cGOAT->CreateAudit($SiteOldData, $FormData, 'SiteOld');
      //$cGOAT->GoToURL('./index.php');
      $cGOAT->GoToURL("./DisplayCampSite.php?Siteid=" . $FormData['IDX']);
    }
  }




  // Check which type of camp view they wish to view
  if (isset($_GET['Siteid'])) {
    // create a table of all of the campsites selected
    $site = $_GET['Siteid'];
    $sql = "SELECT * FROM `site` WHERE IDX = '" . $site . "'";

    $Result = $cGOAT->doQuery($sql);
    if (!$Result) {
      $cGOAT->function_alert("Error");
      $cGOAT->GotoURL('./index.php');
    } else {
      $Site = $Result->fetch_assoc();
    }
  }



  if (isset($Site)) {
  ?>
    <div class="row flex-wrap">
      <?php
      if ((isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]) !== true) {
      ?>
        <center>
          <p>You must have a account with the GOAT website and be logged on to edit a campsite.</p>
          <a class="btn btn-primary btn-sm" href="./logon.php">Log On</a>
        </center>
      <?php
      } else {
        // Display a form to take the new camp site.
      ?>
        <div class="form-coach px-5" style="background-color: var(--scouting-lighttan);">
          <p style="text-align:Left"><b>Edit Camp Site Information</b></p>
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" id="coach-form" method="post">

            <div class="form-row">
              <div class="col-3">
                <label for=element_1_1>Area</label>
                <?php $cGOAT->DisplayArea($Site['area'], "element_1_1"); ?>
              </div>
              <div class="col-2">
                <label for=element_1_2>Name</label>
                <input type="text" name="element_1_2" class="form-control" <?php echo "value='" . ucwords($Site['name']) . "'";    ?> />
              </div>
              <div class="col-3">
                <label for=element_1_3>Primary Activity</label>
                <?php $cGOAT->DisplayActivityType($Site['type1'], "element_1_3"); ?>
              </div>
              <div class="col-3">
                <label for=element_1_4>Secondary Activity</label>
                <?php $cGOAT->DisplayActivityType($Site['type2'], "element_1_4"); ?>
              </div>
            </div>

            <div class="form-row">
              <div class="col-4">
                <label for=element_2_1>More Information Link</label>
                <input type="text" name="element_2_1" class="form-control" <?php if (strlen($Site['map']) > 0) echo "value='" . $Site['map'] . "'"; ?> />
              </div>
              <div class="col-4">
                <label for=element_2_2>Facilities</label>
                <input type="text" name="element_2_2" class="form-control" <?php if (strlen($Site['facilities']) > 0) echo "value='" . $Site['facilities'] . "'"; ?> />
              </div>
              <div class="form-check">
                <label class="form-check-label" for="flexCheckDefault">Deleted</label><br>
                <input class="form-check-input" type="checkbox" name="element_2_4" type="hidden" value='0' />
                <input class="form-check-input" type="checkbox" name="element_2_3" value='1' <?php if ($Site['IsDeleted'] == 1) echo "checked=checked"; ?>>
              </div>
            </div>

            <div class="form-row">
              <div class="col-12">
                <label for=element_3_1>Google Embed Map</label>
                <input type="text" name="element_3_1" class="form-control" <?php if (strlen($Site['embedmap']) > 0) echo "value='" . $Site['embedmap'] . "'";  ?> />
              </div>
            </div>
            <div class="form-row">
              <div class="col-12">
                <label for=Notes>Notes/How to get there</label>
                <textarea class="form-control" name="Notes" rows="10" style="height:100%;"><?php if (strlen($Site['directions']) > 0) echo $Site['directions']; ?></textarea>
              </div>
            </div>
            <div class="form-row">
              <div class="col-10 py-5">
                <?php $ID = -1; ?>
                <?php echo '<input type="hidden" name="Siteid" value="' . $Site['IDX'] . '"/>';
                ?>
                <input id="saveForm3" class="btn btn-primary btn-sm" type="submit" name="SubmitForm" value="Save" />
                <input id="saveForm4" class="btn btn-primary btn-sm" type="submit" name="SubmitForm" value="Cancel" />
              </div>
            </div>
          </form>
        </div>

    <?php
      }
    }
    ?>
    </div>




    <?php include('Footer.php'); ?>

</body>

</html>