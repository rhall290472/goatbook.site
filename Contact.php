<?php
if (!session_id()) {
  session_start();

  include('cGOAT.php');
  $cGOAT = cGOAT::getInstance();

  require 'assets/vendor/php-email-form/Exception.php';
  require 'assets/vendor/php-email-form/PHPMailer.php';
  require 'assets/vendor/php-email-form/SMTP.php';

}
?>
<!-- <script src="assets/vendor/php-email-form/validate.js"></script> -->

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include('head.php'); ?>
</head>

<body>
  <?php
  include_once('header.php');
  ?>

  <div class="container section-title" data-aos="fade-up">
    <h2>Contact</h2>
    <p>The GOAT Site webmaster</p>
  </div><!-- End Section Title -->

  <div class="container">

    <div class="row gy-4">


      <div class="col-lg-8">
        <form action="forms/info.php" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
          <div class="row gy-4">

            <div class="col-md-6">
              <input type="text" name="name" class="form-control" placeholder="Your Name" required="">
            </div>

            <div class="col-md-6 ">
              <input type="email" class="form-control" name="email" placeholder="Your Email" required="">
            </div>

            <div class="col-md-12">
              <input type="text" class="form-control" name="subject" placeholder="Subject" required="">
            </div>

            <div class="col-md-12">
              <textarea class="form-control" name="message" rows="6" placeholder="Message" required="" style="height:100%;"></textarea>
            </div>

            <div class="col-md-12 text-center">
              <!-- <div class="loading">Loading</div>
              <div class="error-message"></div>
              <div class="sent-message">Your message has been sent. Thank you!</div> -->
              <div class="loading"></div>
              <div class="error-message"></div>
              <div class="sent-message"></div>

              <button type="submit">Send Message</button>
            </div>

          </div>
        </form>
      </div><!-- End info Form -->
    </div>
  </div>


  <?php include('Footer.php'); ?>



</body>

</html>