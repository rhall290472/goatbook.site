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

// Environment configuration
define('ENV', 'development'); // Set to 'production' on live server

// Enable error reporting in development only
if (defined('ENV') && ENV === 'development') {
  ini_set('display_errors', 1);
  ini_set('log_errors', 1);
  error_reporting(E_ALL);
} else {
  ini_set('display_errors', 0);
  ini_set('log_errors', 1);
}

// Dynamically set SITE_URL based on environment
$is_localhost = isset($_SERVER['SERVER_NAME']) && in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1']);
$protocol = 'https'; // Always HTTPS
$host = $is_localhost ? ($_SERVER['HTTP_HOST'] ?? 'localhost') : 'goatbook.site';
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
define('SMTP_USERNAME', 'rhall290472@gmail.com');
define('SMTP_PASSWORD', 'vicx cxho rywh ylok'); // Use .env in production

$pageHome = SITE_URL . '/public/index.php';
$pageContact = SITE_URL . '/src/contact.php';

if ($is_localhost) {
  define('DB_HOST', 'localhost');
  define('DB_USER', 'mbcuser');
  define('DB_PASS', 'ZCSCA?yrW7}L');
  define('DB_NAME', 'meritbadges');
} else {
  define('DB_HOST', 'rhall29047217205.ipagemysql.com');
  define('DB_USER', 'mbcuser');
  define('DB_PASS', 'ZCSCA?yrW7}L');
  define('DB_NAME', 'meritbadges');
}

// Template loader function
if (!function_exists('load_template')) {
  function load_template($file, $vars = [])
  {
    $path = BASE_PATH . $file;
    if (file_exists($path)) {
      extract($vars);
      require_once $path;
    } else {
      error_log("Template $file is missing.");
      if (defined('ENV') && ENV === 'development') {
        echo 'Template ' . $path . ' is missing.</br>';
        die('Template $file is missing.');
      } else {
        die('An error occurred. Please try again later.');
      }
    }
  }
}

// Class loader function
if (!function_exists('load_class')) {
  function load_class($file)
  {
    $path = $file;
    if (file_exists($path)) {
      require_once $path;
    } else {
      error_log("Class $file is missing.");
      if (defined('ENV') && ENV === 'development') {
        echo 'Template ' . $path . ' is missing.</br>';
        die('Class $file is missing.');
      } else {
        die('An error occurred. Please try again later.');
      }
    }
  }
}
