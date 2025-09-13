<?php
/*
!==============================================================================!
!\                                                                            /!
!\\                                                                          //!
! \##########################################################################/ !
!  #         This is Proprietary Software of Richard Hall                   #  !
!  ##########################################################################  !
!  #                                                                        #  !
!  #                                                                        #  !
!  #   Copyright 2024 - Richard Hall                                        #  ! 
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

// Initialize variables for form
$username = isset($_POST['username']) ? htmlspecialchars(trim($_POST['username'])) : "";
$username_err = $password_err = $login_err = "";
?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <h2 class="text-center">Login</h2>
      <p class="text-center">Please fill in your credentials to login.</p>

      <form method="post" action="index.php?page=login">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" name="username" id="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($username); ?>">
          <?php if (!empty($username_err)): ?>
            <div class="invalid-feedback"><?php echo htmlspecialchars($username_err); ?></div>
          <?php endif; ?>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" name="password" id="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
          <?php if (!empty($password_err)): ?>
            <div class="invalid-feedback"><?php echo htmlspecialchars($password_err); ?></div>
          <?php endif; ?>
        </div>
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? bin2hex(random_bytes(32))); ?>">
        <div class="mb-3 text-center">
          <input type="submit" class="btn btn-primary" value="Login">
        </div>
        <p class="text-center">Don't have an account? <a href="index.php?page=register">Sign up now</a>.</p>
      </form>
    </div>
  </div>
</div>