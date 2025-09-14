<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Secure session start
if (session_status() === PHP_SESSION_NONE) {
  session_start([
    'cookie_httponly' => true,
    'use_strict_mode' => true,
    'cookie_secure' => isset($_SERVER['HTTPS'])
  ]);
}

// Validate CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
  $_SESSION['feedback'] = [
    'type' => 'error',
    'message' => 'index.php?status=error&message=' . urlencode('Invalid CSRF token')
  ];
  header('Location: index.php?status=error&message=' . urlencode('Invalid CSRF token'));
  exit;
}

// Load PHPMailer
require BASE_PATH . '/public/assets/vendor/autoload.php';
/**
 * Loads the configuration file.
 * Terminates execution if the config file is missing.
 */
if (file_exists(__DIR__ . '/../../../config/config.php')) {
  require_once __DIR__ . '/../../../config/config.php';
} else {
  echo __DIR__ . '/../../config/config.php</br>';
  error_log("Unable to find file config.php " . __DIR__ . "/../config/config.php @ " . __FILE__ . ' ' . __LINE__);
  die('Unable to find required file. Please try again later.');
}

$mail = new PHPMailer(true);

try {
  // Server settings
  $mail->isSMTP();
  $mail->Host = $mailConfig['host'];
  $mail->SMTPAuth = true;
  $mail->Username = $mailConfig['username'];
  $mail->Password = $mailConfig['password'];
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port = 587;

  // Recipients
  $mail->setFrom($mailConfig['username'], $_POST['name']); // Use your SMTP username to avoid spoofing issues
  $mail->addReplyTo($_POST['email'], $_POST['name']); // Allow replies to user’s email
  $mail->addAddress($mailConfig['recipient']);

  // Content
  $mail->isHTML(true);
  $mail->Subject = htmlspecialchars($_POST['subject']) . " - goatbook.site";
  $mail->Body = '<p><strong>From:</strong> ' . htmlspecialchars($_POST['name']) . ' (' . htmlspecialchars($_POST['email']) . ')</p>' .
                '<p><strong>Subject:</strong> ' . htmlspecialchars($_POST['subject']) . '</p>' .
                '<p><strong>Message:</strong><br>' . nl2br(htmlspecialchars($_POST['message'])) . '</p>';
  $mail->AltBody = "From: {$_POST['name']} ({$_POST['email']})\nSubject: {$_POST['subject']}\n\n{$_POST['message']}";

  $mail->send();
  $_SESSION['feedback'] = [
    'type' => 'success',
    'message' => 'Your message has been sent. Thank you!'
  ];
  header("Location: ../../index.php?page=home");
} catch (Exception $e) {
  $_SESSION['feedback'] = [
    'type' => 'error',
    'message' => 'Failed to send email: ' . htmlspecialchars($e->getMessage())
  ];
  header("Location: index.php?page=home");
}
exit;
?>