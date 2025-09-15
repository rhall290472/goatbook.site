<?php
// Secure session start
if (session_status() === PHP_SESSION_NONE) {
  session_start([
    'cookie_httponly' => true,
    'use_strict_mode' => true,
    'cookie_secure' => isset($_SERVER['HTTPS'])
  ]);
}

/*
!==============================================================================!
!\                                                                            /!
!\\                                                                          //!
! \##########################################################################/ !
!  #         This is Proprietary Software of Richard Hall                   #  !
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
! /##########################################################################\ !
!//                                                                          \\!
!/                                                                            \!
!==============================================================================!
*/

/**
 * Handles user logout for the GOAT book website.
 *
 * Clears all session variables, destroys the session, and redirects to the home page.
 * Assumes a session has been started by the calling script (e.g., index.php).
 *
 * @author Richard Hall
 * @copyright 2017-2024 Richard Hall
 * @package GOAT
 */

/**
 * Starts a secure session if not already started.
 */
if (session_status() === PHP_SESSION_NONE) {
  session_start([
    'cookie_httponly' => true,
    'use_strict_mode' => true,
    'cookie_secure' => isset($_SERVER['HTTPS'])
  ]);
}

/**
 * Clears all session variables.
 */
$_SESSION = array();

/**
 * Destroys the current session.
 */
session_destroy();

/**
 * Redirects to the home page after logout.
 */
header("Location: index.php?page=home");
exit;
