<?php
session_start();

include '../inc/db.php';
include '../inc/functions.php';
$json = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = p_s($_POST['email']);
  $password = p_s($_POST['password']);


  if (!empty($email) && !empty($password)) {

    if (checkIfEmailExist($email)) {

      $sql = "SELECT * FROM users WHERE email = '$email'";
      $run = mysqli_query($conn, $sql);
      while ($rows = mysqli_fetch_assoc($run)) {
        $_SESSION['id'] = $rows['id'];
        $_SESSION['name'] = $rows['name'];
        $_SESSION['email'] = $rows['email'];
        $_SESSION['bio'] = $rows['bio'];
        $_SESSION['lastactive'] = $rows['lastactive'];
        header('Location: ../index.php?logged');
        exit();
      }
    } else {
      header('Location: ../signup.php?er2');
      exit();
    }
  } else {
    header('Location: ../signup.php?er3');
    exit();
  }
}
