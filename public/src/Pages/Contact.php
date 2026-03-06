<?php

/**
 * Contact.php - Renders a secure contact form with CSRF protection and session-based feedback.
 * Dependencies: PHP 7.4+, Bootstrap 5.3.3, AOS (optional for animations).
 * Form submits to index.php?page=sendemail for processing.
 */
// Secure session start
if (session_status() === PHP_SESSION_NONE) {
  session_start([
    'cookie_httponly' => true,
    'use_strict_mode' => true,
    'cookie_secure' => isset($_SERVER['HTTPS'])
  ]);
}

require_once BASE_PATH .'/vendor/autoload.php';  // if using Composer autoload

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH . '/public');
$dotenv->load();

$siteKey   = $_ENV['RECAPTCHA_SITE_KEY']   ?? '';  // fallback empty
$secretKey = $_ENV['RECAPTCHA_SECRET_KEY'] ?? '';


// Generate CSRF token if not set
try {
  if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
} catch (Exception $e) {
  // Log error and set a fallback token or redirect to an error page
  error_log('CSRF token generation failed: ' . $e->getMessage() . " " . __FILE__ . " " . __LINE__);
  $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
}
// Check for feedback in session
$message = '';
if (isset($_SESSION['feedback'])) {
  $status = $_SESSION['feedback']['status'];
  $feedback = $_SESSION['feedback']['message'];
  $message = "<div class=\"alert alert-$status\">$feedback</div>";
  unset($_SESSION['feedback']); // Clear feedback after display
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us</title>
  <script src="https://www.google.com/recaptcha/api.js?render=6Lf2HoIsAAAAAIgggIZ3mt11vT0HpznBUuLvNs9V"></script>
</head>

<body>
  <div class="container-fluid">
    <div class="row flex-nowrap">
      <div class="col py-3">
        <div class="container px-md-3">
          <div class="p-3 p-md-3 bg-light rounded-2 text-center">
            <div class="m-3 m-lg-3">
              <div class="col-lg-9 mx-auto">
                <h2>Contact</h2>
                <p>If you have any questions or comments, please complete the form below and we will get back to you.</p>
                <?php if ($message): ?>
                  <?php echo $message; ?>
                <?php endif; ?>
                <form action="index.php?page=sendemail" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
                  <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                  <input type="hidden" name="g-recaptcha-response" id="recaptchaResponse">
                  <input type="hidden" name="form_start_time" value="<?php echo time(); ?>">
                  <div class="row gy-4">
                    <div class="col-md-6">
                      <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                    </div>
                    <div class="col-md-6">
                      <input type="email" name="email" class="form-control" placeholder="Your Email" required>
                    </div>
                    <div class="col-md-12">
                      <input type="text" name="subject" class="form-control" placeholder="Subject" required>
                    </div>
                    <div class="col-md-12">
                      <textarea class="form-control" style="height:auto" name="message" rows="10" placeholder="Message" required></textarea>
                    </div>
                    <div class="col-md-12 text-center">
                      <div class="loading" style="display: none">Sending...</div>
                      <button type="submit" class="btn btn-primary btn-sm">Send Message</button>
                    </div>
                  </div>
                  <!-- Honeypot field for bots -->
                  <div style="position:absolute; left:-9999px;">
                    <input type="text" name="website_url" value="" tabindex="-1" autocomplete="off">
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Vendor JS Files -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.querySelector('.php-email-form');
      const loading = form?.querySelector('.loading');
      if (form && loading) {
        form.addEventListener('submit', function() {
          loading.style.display = 'block';
        });
      }
    });

    document.querySelector('.php-email-form').addEventListener('submit', function(e) {
    e.preventDefault();
    grecaptcha.ready(function() {
        grecaptcha.execute('6Lf2HoIsAAAAAIgggIZ3mt11vT0HpznBUuLvNs9V', {action: 'contact_form'}).then(function(token) {
            document.getElementById('recaptchaResponse').value = token;
            // now really submit the form
            e.target.submit();
        });
    });
  });
  </script>

  
</body>

</html>