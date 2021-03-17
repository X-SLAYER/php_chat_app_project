<?php 
if (isset($_SESSION['id']) && isset($_SESSION['email'])) {
	
}else{
  if (session_destroy()) {
    header('Location: signup.php');
    exit();
  }
}