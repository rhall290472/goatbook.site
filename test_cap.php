<!DOCTYPE html>
<html>

<head>
</head>

<body>
  <?php
  ## reCAPTCHA V3 key define ##
  #client-side
  define('RECAPTCHA_SITE_KEY', '6Lf78lMqAAAAAFjrzUyu7I0UVidpbZ0uBoquOJ5H'); // define here reCAPTCHA_site_key
  #server-side
  define('RECAPTCHA_SECRET_KEY', '6Lf78lMqAAAAAOuwJCZ62SMDR976cOiBm5etXhEB'); // define here reCAPTCHA_secret_key

  class Captcha {
      public function getCaptcha($SecretKey) {
      $Resposta = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . RECAPTCHA_SECRET_KEY . "&response={$SecretKey}");
      $Retorno = json_decode($Resposta);
      return $Retorno;  
      }
  }


  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //echo "<pre>"; print_r($_REQUEST); echo "</pre>";
    $ObjCaptcha = new Captcha();
    $Retorno = $ObjCaptcha->getCaptcha($_POST['g-recaptcha-response']);
    //echo "<pre>"; print_r($Retorno); echo "</pre>";
    if ($Retorno->success) {
    echo '<p style="color: #0a860a;">CAPTCHA was completed successfully!</p>';
    } else {
    $error_codes = 'error-codes';
    if (isset($Retorno->$error_codes) && in_array("timeout-or-duplicate", $Retorno->$error_codes)) {
    $captcha_msg = "The verification expired due to timeout, please try again.";
    } else {
    $captcha_msg = "Check to make sure your keys match the registered domain and are in the correct locations.<br> You may also want to doublecheck your code for typos or syntax errors.";
    }
    echo '<p style="color: #f80808;">reCAPTCHA error: ' . $captcha_msg . '</p>';
    }
    }
    ?>
    
    <div class="contact_form">
    <div class="" style="margin-top:0px;margin-bottom:15px;">
    <div class="" style="width:50%">
    <form id="Form1" name="Form1" action="" method="POST">
    <label for="fname">First Name*</label><br>
    <input type="text" name="fname" id="fname" required autofocus><br><br>
    
    <label for="lname">Last Name*</label><br>
    <input type="text" name="lname" id="lname" required><br><br>
    
    <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response"><br>
    <input type="submit">
    </form>
    </div>
    </div>
    </div>
    
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo RECAPTCHA_SITE_KEY; ?>"></script>
    <script>
    grecaptcha.ready(function () {
    grecaptcha.execute('<?php echo RECAPTCHA_SITE_KEY; ?>', {action: 'homepage'}).then(function (token) {
    document.getElementById('g-recaptcha-response').value = token;
    });
    });
    </script>
    </body>
    </html>