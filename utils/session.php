<?php
  session_start();
  ob_start();

  if (!isset($_SESSION["login_user"])){
    header("Location: login.php");
  }else{

  }

?>