<?php
// Secure session start
if (session_status() === PHP_SESSION_NONE) {
  session_start([
    'cookie_httponly' => true,
    'use_strict_mode' => true,
    'cookie_secure' => isset($_SERVER['HTTPS'])
  ]);
}

$cGOAT = cGOAT::getInstance();
/*
!==============================================================================!
!\                                                                            /!
!\\                                                                          //!
! \##########################################################################/ !
!  #         This is Proprietary Software of Richard Hall                   #  !
!  ##########################################################################  !
!  ##########################################################################  !
!  #                                                                        #  !
!  #                                                                        #  !
!  #   Copyright 2017-2024 - Richard Hall                                   #  !
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

/* Check if the user is already logged in, if yes then redirect him to welcome page */
if (!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)) {
  header("HTTP/1.0 403 Forbidden");
  exit;
}

$logfile = BASE_PATH .'/logs/php_errors.log';   // ← change this!

if (!file_exists($logfile)) {
    die("Log file not found.");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>PHP Error Log</title>
  <style>
     body {
      /* font-family: Consolas, Monaco, 'Courier New', monospace; */
      background: #0d1117;
      color: #c9d1d9;
      padding: 20px;
      line-height: 1.5;
    } 
    .log-container {
      max-width: 1400px;
      margin: 0 auto;
    } 
    .line {
      margin: 6px 0;
      padding: 4px 8px;
      border-radius: 4px;
      background: #0f0f0f;
      word-break: break-all;
    } 
    .date { color: #8b949e; }
    .fatal   { background: #4a1c1c; border-left: 5px solid #f85149; }
    .error   { background: #3d2a1f; border-left: 5px solid #f0883e; }
    .warning { background: #332f1e; border-left: 5px solid #d29922; }
    .notice  { background: #1f2a38; border-left: 5px solid #58a6ff; }
    .deprecated { color: #8b949e; font-style: italic; }
    .stack   { color: #79c0ff; margin-left: 30px; }
  </style>
</head>
<body>
  <div class="log-container">
    <h1>php_error.log</h1>
    <p>Last modified: <?= date('Y-m-d H:i:s', filemtime($logfile)) ?></p>

    <?php
    $lines = file($logfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $lines = array_reverse($lines); // newest first

    foreach ($lines as $line) {
        $class = 'line';
        
        if (stripos($line, 'fatal') !== false)   $class .= ' fatal';
        elseif (stripos($line, 'error') !== false)   $class .= ' error';
        elseif (stripos($line, 'warning') !== false) $class .= ' warning';
        elseif (stripos($line, 'notice') !== false)  $class .= ' notice';
        elseif (stripos($line, 'deprecated') !== false) $class .= ' deprecated';

        // Very basic highlighting of [date] part
        $line = preg_replace(
            '/^\[([^\]]+)\]/',
            '<span class="date">[$1]</span>',
            htmlspecialchars($line, ENT_QUOTES | ENT_HTML5)
        );

        echo "<div class=\"$class\">$line</div>\n";
    }
    ?>
  </div>
</body>
</html>