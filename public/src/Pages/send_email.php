<?php
/**
 * send_email.php - Secure contact form processor using PHPMailer + Gmail SMTP
 */

session_start([
    'cookie_httponly' => true,
    'use_strict_mode' => true,
    'cookie_secure'   => isset($_SERVER['HTTPS'])
]);

require_once __DIR__ . '/vendor/autoload.php';  // if using Composer autoload

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$siteKey   = $_ENV['RECAPTCHA_SITE_KEY']   ?? '';  // fallback empty
$secretKey = $_ENV['RECAPTCHA_SECRET_KEY'] ?? '';


// Load config (defines SMTP_HOST, SMTP_USER, SMTP_PASS, SMTP_PORT, CONTACT_EMAIL)
$pathToConfig = dirname(__DIR__, 3) . '/config/config.php'; // Adjust if folder structure differs
if (file_exists($pathToConfig)) {
    require_once $pathToConfig;
} else {
    error_log("Config not found: $pathToConfig");
    die('Server configuration error. Please contact admin.');
}

// Load PHPMailer (Composer preferred)
$autoloadPath = BASE_PATH . '/vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require $autoloadPath;
} else {
    // Fallback: manual includes (if no Composer)
    require BASE_PATH . '/public/assets/vendor/PHPMailer/src/PHPMailer.php';
    require BASE_PATH . '/public/assets/vendor/PHPMailer/src/SMTP.php';
    require BASE_PATH . '/public/assets/vendor/PHPMailer/src/Exception.php';
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['feedback'] = ['type' => 'danger', 'message' => 'Invalid request.'];
    header("Location: index.php?page=contact");
    exit;
}

// CSRF check
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
    $_SESSION['feedback'] = ['type' => 'danger', 'message' => 'Security check failed. Please try again.'];
    header("Location: index.php?page=contact");
    exit;
}

// Sanitize inputs
$name    = trim(preg_replace('/[\x00-\x1F\x7F]/u', '', $_POST['name'] ?? ''));     // remove control chars
$subject = trim(preg_replace('/[\x00-\x1F\x7F]/u', '', $_POST['subject'] ?? ''));
$message = trim(preg_replace('/[\x00-\x1F\x7F]/u', '', $_POST['message'] ?? ''));

// For email — use proper validation instead of sanitization
$email   = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // handle invalid email
}
if (empty($name) || empty($email) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['feedback'] = ['type' => 'danger', 'message' => 'Please fill all fields correctly.'];
    header("Location: index.php?page=contact");
    exit;
}

$secret = '6Lf2HoIsAAAAAC9xlOtNKMaCr4Qt_HLe1hEnngTz';
$response = $_POST['g-recaptcha-response'];
$verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
$captcha_success = json_decode($verify);

if ($captcha_success->success == false || $captcha_success->score < 0.4) {  // 0.0 = bot, 1.0 = human — tune threshold
    $_SESSION['feedback'] = ['status' => 'danger', 'message' => 'Verification failed. Please try again.'];
    header("Location: index.php?page=contact");
    exit;
}
// Honeypot check (should be empty)
if (!empty($_POST['website_url'])) {
    // bot → silently reject or log
    die();   // or set error and redirect
}

if (isset($_POST['form_start_time'])) {
    $time_taken = time() - (int)$_POST['form_start_time'];
    if ($time_taken < 6) {   // adjust 6–12 seconds
        // bot → reject
      die();
    }
}


$mail = new PHPMailer(true);

try {
    // Enable debug for testing (remove or set to 0 in production)
    $mail->SMTPDebug = 2;               // 2 = verbose output (shows SMTP conversation)
    $mail->Debugoutput = 'html';        // Nice formatting in browser during test

    // Server settings
    $mail->isSMTP();
    $mail->Host       = SMTP_HOST;      // 'smtp.gmail.com'
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_USER;      // richard.hall@centennialdistrict.co
    $mail->Password   = SMTP_PASS;      // MUST be Gmail App Password (16 chars, no spaces)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = (int) SMTP_PORT; // 587

    // Recipients
    $mail->setFrom(SMTP_USER, 'GOAT Book Contact Form'); // Use your Gmail to avoid blocks
    $mail->addReplyTo($email, $name);                    // Replies go to visitor
    $mail->addAddress(CONTACT_EMAIL);                    // You receive it here

    // Content
    $mail->isHTML(true);
    $mail->Subject = htmlspecialchars($subject) . ' - goatbook.site Contact';
    $mail->Body    = '
        <h3>New Message from Contact Form</h3>
        <p><strong>Name:</strong> ' . htmlspecialchars($name) . '</p>
        <p><strong>Email:</strong> ' . htmlspecialchars($email) . '</p>
        <p><strong>Subject:</strong> ' . htmlspecialchars($subject) . '</p>
        <hr>
        <p><strong>Message:</strong><br>' . nl2br(htmlspecialchars($message)) . '</p>
    ';
    $mail->AltBody = "Name: $name\nEmail: $email\nSubject: $subject\n\n$message";

    $mail->send();

    $_SESSION['feedback'] = [
        'type'    => 'success',
        'message' => 'Thank you! Your message has been sent successfully.'
    ];
    header("Location: index.php?page=contact"); // Redirect back to form (better UX)

} catch (Exception $e) {
    error_log("PHPMailer Error: " . $mail->ErrorInfo . " | File: " . __FILE__);
    $_SESSION['feedback'] = [
        'type'    => 'danger',
        'message' => 'Sorry, we couldn\'t send your message. Error: ' . htmlspecialchars($mail->ErrorInfo)
    ];
    header("Location: index.php?page=contact");
}

exit;