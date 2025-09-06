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
  <!-- Include TinyMCE from CDN -->
  <!-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script> -->
  <script src="https://cdn.tiny.cloud/1/go7c0mdpiffej81ji1n8edfu4mubr4v4fnrz6dc5qzjhian8/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
  <script>
    // Initialize TinyMCE on page load
    document.addEventListener('DOMContentLoaded', function() {
      tinymce.init({
        selector: 'textarea#Notes',
        plugins: 'lists link image table code',
        toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        menubar: false,
        height: 300,
        content_style: 'body { font-family: Arial, sans-serif; font-size: 14px }'
      });
    });
  </script>
</head>

<body>
  <?php
  include_once('header.php');

  //#####################################################################
  //
  // Check to see if user has Submitted the form. If so, save the data.
  //
  //#####################################################################
  if (isset($_POST['SubmitForm'])) {
    if ($_POST['SubmitForm'] == "Cancel") {
      $cGOAT::GotoURL('./index.php');
      exit;
    }

    // Save New data from the user form
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
        <p>You must have an account with the GOAT website and be logged on to add a campsite.</p>
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
              <input type="text" name="element_1_2" class="form-control" />
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
              <input type="text" name="element_2_1" class="form-control" />
            </div>
            <div class="col-4">
              <label for=element_2_2>Facilities</label>
              <input type="text" name="element_2_2" class="form-control" />
            </div>
          </div>

          <div class="form-row">
            <div class="col-12">
              <label for=element_3_1>Google Embed Map</label>
              <input type="text" name="element_3_1" class="form-control" />
            </div>
          </div>
          <div class="form-row">
            <div class="col-12">
              <label for=Notes>Notes/How to get there</label>
              <textarea class="form-control" name="Notes" id="Notes" rows="10" style="height:100%;"></textarea>
            </div>
          </div>
          <div class="form-row">
            <div class="col-10 py-5">
              <?php $ID = -1; ?>
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

  <?php include('Footer.php'); ?>

</body>

</html>