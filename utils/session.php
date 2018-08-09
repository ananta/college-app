<?php
  if (!isset($_SESSION["login_user"]) && !isset($_SESSION["given_batch"])){
    header("Location: ".$mainPage."login.php");
  }else{

  }
?>