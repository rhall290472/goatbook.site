<?php
// File: config.php

/**
 * File: config.php
 * Description: Centralized configuration settings for Centennial District Advancement
 * Author: Richard Hall
 * License: Proprietary Software, Copyright 2024 Richard Hall
 */

defined('IN_APP') or define('IN_APP', true);
// Base path, only set once
defined('BASE_PATH') or define('BASE_PATH', dirname(__DIR__));

define('GITHUB_TOKEN', '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');


// Dynamically set SITE_URL based on environment
$is_localhost = isset($_SERVER['SERVER_NAME']) && in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1']);
$protocol = $is_localhost ? 'http' : 'https'; // Always HTTPS
$host = $is_localhost ? ($_SERVER['HTTP_HOST'] ?? 'goatbook.site.local') : 'goatbook.site';
$port = ($is_localhost && isset($_SERVER['SERVER_PORT']) && !in_array($_SERVER['SERVER_PORT'], ['80', '443'])) ? ':' . $_SERVER['SERVER_PORT'] : '';
if ($is_localhost) {
  $base_path = '/goatbook.site/public';
} else {
  $base_path = '';
}
define('SITE_URL', $protocol . '://' . $host . $port . $base_path);
define('ASSETS_URL', SITE_URL . '/assets');


// Site metadata
define('PAGE_TITLE', 'Guide to Outdoor Activities for Troops');
define('PAGE_DESCRIPTION', 'GOAT Book');

// Contact email
define('CONTACT_EMAIL', 'richard.hall@centennialdistrict.co');

// SMTP settings
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USER', 'richard.hall@centennialdistrict.co');
define('SMTP_PASS', 'wmksqamucgzvlsil'); 
define('SMTP_PORT', '587');

$pageHome = SITE_URL . '/public/index.php';
$pageContact = SITE_URL . '/src/contact.php';

// Environment configuration
define('ENV', 'development'); // Set to 'production' on live server

// Enable error reporting in development only
if (defined('ENV') && ENV === 'development') {
  ini_set('display_errors', 1);
  ini_set('log_errors', 1);
//  ini_set('error_log', SHARED_PATH . '/shared/logs/php_errors.log');
//  $pgLog = SHARED_PATH . '/shared/logs';
  error_reporting(E_ALL);
} else {
  ini_set('display_errors', 0);
  ini_set('log_errors', 1);
//  ini_set('error_log', 'https://shared.centennialdistrict.co/logs/error.log');
}


if ($is_localhost) {
  define('DB_HOST', 'localhost');
  define('DB_USER', 'root');
  define('DB_PASS', '');
  define('DB_NAME', 'goat');
} else {
  define('DB_HOST', 'rhall29047217205.ipagemysql.com');
  define('DB_USER', 'goatbookuser');
  define('DB_PASS', '8-@Gnyqndt');
  define('DB_NAME', 'goat');
}

// Template loader function
if (!function_exists('load_template')) {
  function load_template(string $templatePath, array $vars = []): void
  {
    $fullPath = BASE_PATH . $templatePath;

    if (!file_exists($fullPath)) {
      $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
      $caller = $trace[0] ?? ['file' => 'unknown', 'line' => 0];
      $message = "Template not found: {$fullPath}\nCalled from: {$caller['file']}:{$caller['line']}";
      error_log($message);

      if (defined('ENV') && ENV === 'development') {
        die($message);
      }
      die('Template error. Please contact support.');
    }

    // Make passed variables available in the template
    extract($vars, EXTR_SKIP);   // ← This is the key line

    require $fullPath;            // or require_once if you prefer
  }
}

// Class loader function
if (!function_exists('load_class')) {
  function load_class(string $classFile): void
  {
    if (file_exists($classFile)) {
      require_once $classFile;
      return;
    }

    // Get caller information
    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    $caller = $trace[0] ?? ['file' => 'unknown', 'line' => 0];

    $message = "Cannot load class file: {$classFile}\n"
      . "Called from: {$caller['file']}:{$caller['line']}";

    error_log($message);

    if (defined('ENV') && ENV === 'development') {
      header('Content-Type: text/plain; charset=utf-8');
      die($message);
    }

    die('An internal error occurred. Please try again later.');
  }
}

// Helper function 
function get_csrf_token(): string
{
  // If session is still not active → big problem (log + fallback)
  if (session_status() !== PHP_SESSION_ACTIVE) {
    // You can throw exception in development
    // or return some fallback token (not ideal)
    error_log("CRITICAL: Could not start session for CSRF token");
    return bin2hex(random_bytes(16)); // degraded mode – but at least no crash
  }

  if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }

  return $_SESSION['csrf_token'];
}
