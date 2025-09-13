<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start([
    'cookie_httponly' => true,
    'use_strict_mode' => true,
    'cookie_secure' => isset($_SERVER['HTTPS'])
  ]);
}

require_once __DIR__ . '/../../../config/config.php';
include_once __DIR__ . '/../Classes/cGOAT.php';
$cGOAT = cGOAT::getInstance();

// Validate CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
  header('Content-Type: application/json');
  echo json_encode(['success' => false, 'error' => 'Invalid CSRF token']);
  exit;
}

// Validate site_id
$site_id = isset($_POST['site_id']) ? (int)$_POST['site_id'] : 0;
if ($site_id <= 0) {
  header('Content-Type: application/json');
  echo json_encode(['success' => false, 'error' => 'Invalid site ID']);
  exit;
}

// Check if a file was uploaded
if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
  header('Content-Type: application/json');
  echo json_encode(['success' => false, 'error' => 'No file uploaded or upload error']);
  exit;//
}

// Validate file type and size
$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
$max_size = 5 * 1024 * 1024; // 5MB
$file = $_FILES['file'];

if (!in_array($file['type'], $allowed_types)) {
  header('Content-Type: application/json');
  echo json_encode(['success' => false, 'error' => 'Invalid file type. Only JPEG, PNG, and GIF are allowed']);
  exit;
}

if ($file['size'] > $max_size) {
  header('Content-Type: application/json');
  echo json_encode(['success' => false, 'error' => 'File size exceeds 5MB limit']);
  exit;
}

// Generate a unique filename
$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = uniqid('img_') . '.' . $ext;
echo __DIR__.'</br.';
$upload_dir = __DIR__ . '/../uploads/campsite_images/';
$upload_path = $upload_dir . $filename;
$public_path = '/uploads/campsite_images/' . $filename;

// Ensure upload directory exists
if (!is_dir($upload_dir)) {
  mkdir($upload_dir, 0755, true);
}

// Move the uploaded file
if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
  header('Content-Type: application/json');
  echo json_encode(['success' => false, 'error' => 'Failed to save file']);
  exit;
}

// Save image metadata to the database
try {
  $sql = "INSERT INTO `campsite_images` (`site_id`, `file_path`, `file_name`, `uploaded_by`) VALUES (?, ?, ?, ?)";
  $stmt = mysqli_prepare($cGOAT->getDbConn(), $sql);
  $uploaded_by = isset($_SESSION['username']) ? $_SESSION['username'] : 'unknown';
  mysqli_stmt_bind_param($stmt, 'isss', $site_id, $public_path, $file['name'], $uploaded_by);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  // Return the URL of the uploaded image
  header('Content-Type: application/json');
  echo json_encode(['success' => true, 'location' => $public_path]);
} catch (Exception $e) {
  // Delete the uploaded file if database insertion fails
  unlink($upload_path);
  header('Content-Type: application/json');
  echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
exit;