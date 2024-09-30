<?php
if (!session_id()) {
  session_start();
}
include('cGOAT.php');
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


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include('./head.php'); ?>
</head>
<?php include_once('header.php'); ?>

<body class="body" style="padding:20px">
  <!-- Responsive navbar -->
  <!-- <nav class="navbar navbar-expand-lg navbar-dark bg-dark"> -->
  <!-- <div class="container px-lg-5"> -->
  <!-- <a class="navbar-brand" href="#!">Edit-Add Users</a> -->
  <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button> -->
  <!-- <div class="collapse navbar-collapse" id="navbarSupportedContent"> -->
  <!-- <ul class="navbar-nav ms-auto mb-2 mb-lg-0"> -->
  <!-- <li class="nav-item"><a class="nav-link active" aria-current="page" href="./admin_index.php">Back</a></li> -->
  <!-- </ul> -->
  <!-- </div> -->
  <!-- </div> -->
  <!-- </nav> -->


  <!-- Header-->
  <header class="py-5">
    <div class="container px-lg-5">
      <div class="p-4 p-lg-5 bg-light rounded-3 text-center">
        <div class="m-4 m-lg-5">
          <h1 class="display-5 fw-bold">Users for the GoatBook</h1>
          <p class="fs-4">Below is a list of Users</p>
          <a class=" btn btn-primary btn-lg" href="./AddUsers.php">Add User</a>
        </div>
      </div>
    </div>
  </header>

  <section class="py-5">
    <?php
    // Get current events in database
    $sql = "SELECT * FROM users";
    $result = $cGOAT->doQuery($sql);
    if ($result) {
      // Display the events 
    ?>
      <table class="table table-striped">
        <tr>
          <th>id</th>
          <th>Username</th>
          <th>Email</th>
          <th>Password</th>
          <th>Enabled</th>
          <th>Last login</th>
          <th>Role</th>
          <th>is_deleted</th>
          <th>Created</th>
          <!-- <th>Updated</th> -->
        </tr>
        <?php
        while ($row = $result->fetch_assoc()) {
          echo "<tr><td>" .
            "<a href='../forms/EditUsers.php?IDX=" . $row["id"] . "'>" . $row["id"] . "</a>" . "</td><td>" .
            $row["username"] . "</td><td>" .
            $row["email"] . "</td><td>" .
            $row["password"] . "</td><td>" .
            $row["enabled"] . "</td><td>" .
            $row["LastLogin"] . "</td><td>" .
            $row["Type"] . "</td><td>" .
            $row["is_deleted"] . "</td><td>" .
            $row["created_at"] . "</td><tr>";
            // $row["updated_by"] . "</td></tr>";
        }
        ?>
      </table>
    <?php

    }
    ?>
  </section>
  <?php include("./Footer.php"); ?>
</body>

</html>