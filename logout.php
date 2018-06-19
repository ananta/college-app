<?php
session_start();
ob_start();
$_SESSION = array();
header("Location: home.php");
?>