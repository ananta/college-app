<?php
  session_start();
  ob_start();
  include('components/header.php');
  if (!isset($_SESSION["login_user"]))
   {
      header("location: login.php");
   }else{
      header("location: home.php");
   }
?>