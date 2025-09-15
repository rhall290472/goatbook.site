<?php
// Secure session start
if (session_status() === PHP_SESSION_NONE) {
  session_start([
    'cookie_httponly' => true,
    'use_strict_mode' => true,
    'cookie_secure' => isset($_SERVER['HTTPS'])
  ]);
  // Generate CSRF token if not set
  if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
}

$cGOAT = cGOAT::getInstance();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Ensure Bootstrap is included -->
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
  <script src="https://cdn.tiny.cloud/1/go7c0mdpiffej81ji1n8edfu4mubr4v4fnrz6dc5qzjhian8/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      tinymce.init({
        selector: 'textarea#Notes',
        plugins: 'lists link image table code',
        toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        menubar: false,
        height: 300,
        content_style: 'body { font-family: Arial, sans-serif; font-size: 14px }',
        images_upload_url: '/src/Handlers/upload_image.php', // Server-side script to handle uploads
        images_upload_handler: async function(blobInfo, progress) {
          let formData = new FormData();
          formData.append('file', blobInfo.blob(), blobInfo.filename());
          formData.append('site_id', '<?php echo isset($Site['IDX']) ? htmlspecialchars($Site['IDX']) : ''; ?>');
          formData.append('csrf_token', '<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>');

          try {
            let response = await fetch('/src/Handlers/upload_image.php', {
              method: 'POST',
              body: formData
            });
            let result = await response.json();
            if (result.success) {
              return result.location; // URL of the uploaded image
            } else {
              throw new Error(result.error || 'Image upload failed');
            }
          } catch (error) {
            throw new Error('Image upload failed: ' + error.message);
          }
        }
      });
    });
  </script>
</head>

<body>
  <?php
  if (isset($_POST['SubmitForm'])) {
    // Remove the Cancel check
    // Verify CSRF token
    if(isset($_POST['SubmitForm']) && $_POST['SubmitForm'] === 'Cancel') {
      header("Location: index.php?page=home");
      exit;
    }
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
      $_SESSION['feedback'] = ['type' => 'danger', 'message' => 'Invalid CSRF token'];
      header("Location: index.php?page=home");
      exit;
    }

    // Validate and sanitize form data
    $FormData = [
      'area' => filter_var($cGOAT->GetFormData('element_1_1'), FILTER_SANITIZE_STRING),
      'name' => filter_var($cGOAT->GetFormData('element_1_2'), FILTER_SANITIZE_STRING),
      'type1' => filter_var($cGOAT->GetFormData('element_1_3'), FILTER_VALIDATE_INT) ?: 0,
      'type2' => filter_var($cGOAT->GetFormData('element_1_4'), FILTER_VALIDATE_INT) ?: 0,
      'map' => filter_var($cGOAT->GetFormData('element_2_1'), FILTER_SANITIZE_URL),
      'facilities' => filter_var($cGOAT->GetFormData('element_2_2'), FILTER_SANITIZE_STRING),
      'embedmap' => filter_var($cGOAT->GetFormData('element_3_1'), FILTER_SANITIZE_STRING),
      'directions' => $cGOAT->GetFormData('Notes') // TinyMCE handles HTML; sanitize on display
    ];

    // Basic validation
    if (empty($FormData['name']) || empty($FormData['area'])) {
      $_SESSION['feedback'] = ['type' => 'danger', 'message' => 'Name and Area are required fields'];
      header("Location: index.php?page=addcampsite");
      exit;
    }

    // Insert into database
    $result = $cGOAT->InsertSite($FormData);
    if ($result) {
      $cGOAT->function_alert("Campsite added successfully!");
      $_SESSION['feedback'] = ['type' => 'sucess', 'message' => 'FCampsite added successfully!'];
      header('Location: index.php?page=campsite&Siteid=' . $result);

      //$cGOAT->GotoURL('./index.php?page=campsite&Siteid=' . $result); // Assume InsertSite returns new ID
    } else {
      $_SESSION['feedback'] = ['type' => 'danger', 'message' => 'Failed to add campsite. Please try again'];
      header("Location: index.php?page=home");
      exit;

      //$cGOAT->function_alert("Failed to add campsite. Please try again.");
    }
  }

  // Assuming $cGOAT is already initialized (e.g., $cGOAT = cGOAT::getInstance();)

  // Prepare the query to get the highest IDX
  $sql = "SELECT MAX(IDX) AS last_idx FROM `site`";
  $stmt = mysqli_prepare($cGOAT->getDbConn(), $sql);

  if ($stmt) {
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $last_idx = $row['last_idx'] ?? null;
    mysqli_stmt_close($stmt);

    if ($last_idx !== null) {
      //echo "The last Site['IDX'] value is: " . $last_idx;
      // Use $last_idx as needed (e.g., assign to $Site['IDX'])
      $Site['IDX'] = $last_idx;
    } else {
      $cGOAT->function_alert("No campsites found in the database.");
    }
  } else {
    $_SESSION['feedback'] = ['type' => 'danger', 'message' => 'Database error: Unable to prepare query'];
    header("Location: index.php?page=home");
    exit;

    $cGOAT->function_alert("Database error: Unable to prepare query.");
  }

  ?>

  <div class="row flex-wrap">
    <?php if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) { ?>
      <center>
        <p class="py-3">You must have an account with the GOAT website and be logged in to add a campsite. Or you may send the information via Contact, and we will enter it for you.</p>
        <a class="btn btn-primary btn-sm" href="?page=login">Log In</a>
      </center>
    <?php } else { ?>
      <div class="form-coach px-5" style="background-color: var(--scouting-lighttan);">
        <p style="text-align:left"><b>Add Campsite Information</b></p>
        <form action="index.php?page=addcampsite" id="campsite-form" method="post">
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
          <div class="form-row">
            <div class="col-3">
              <label for="element_1_1">Area</label>
              <?php $cGOAT->DisplayArea(0, "element_1_1"); ?>
            </div>
            <div class="col-2">
              <label for="element_1_2">Name</label>
              <input type="text" name="element_1_2" class="form-control" />
            </div>
            <div class="col-3">
              <label for="element_1_3">Primary Activity</label>
              <?php $cGOAT->DisplayActivityType(0, "element_1_3"); ?>
            </div>
            <div class="col-3">
              <label for="element_1_4">Secondary Activity</label>
              <?php $cGOAT->DisplayActivityType(0, "element_1_4"); ?>
            </div>
          </div>
          <div class="form-row">
            <div class="col-4">
              <label for="element_2_1">Google Map Shared Location</label>
              <input type="text" name="element_2_1" class="form-control" />
            </div>
            <div class="col-4">
              <label for="element_2_2">Facilities</label>
              <input type="text" name="element_2_2" class="form-control" />
            </div>
          </div>
          <div class="form-row">
            <div class="col-12">
              <label for="element_3_1">Google Embed Map</label>
              <input type="text" name="element_3_1" class="form-control" />
            </div>
          </div>
          <div class="form-row">
            <div class="col-12">
              <label for="Notes">Notes/How to Get There</label>
              <textarea class="form-control tinymce" name="Notes" id="Notes" rows="10"></textarea>
            </div>
          </div>
          <div class="form-row">
            <div class="col-10 py-5">
              <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
              <input id="saveForm3" class="btn btn-primary btn-sm" type="submit" name="SubmitForm" value="Save" />
              <input id="saveForm4" class="btn btn-primary btn-sm" type="submit" name="SubmitForm" value="Cancel" />
            </div>
          </div>
        </form>
      </div>
    <?php } ?>
  </div>
</body>

</html>