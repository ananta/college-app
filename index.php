<?php
  session_start();
  ob_start();
  include('components/header.php');
  if (!isset($_SESSION["login_user"]))
   {
      header("location: pages/login.php");
   }else{
      header("location: pages/home.php");
   }
?>