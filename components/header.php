<?php
session_start();
	ob_start();
	$currentPage = substr($_SERVER['REQUEST_URI'],12);
	$mainPage= 'http://'.$_SERVER['SERVER_NAME']."/gcesServer/";
	$mainLocation = $_SERVER['DOCUMENT_ROOT']."/gcesServer/";
	$verified = false;
?>

<!DOCTYPE html>
<html lang="en" >
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin Login</title>
	<link rel="stylesheet" href="<?php echo $mainPage?>css/style.css?load=<?php echo time();?>" type="text/css">

</head>
<body>
<header>
	
	<?php 
		echo '
		<div class="sidenav">
			<div class="logoImg">
			<a href="'.$mainPage.'landing.php">
				<img src="'.$mainPage.'res/gces_logo.png" />
			</a>
			</div>
			<a class='.($currentPage == "teachers.php" ? "activeLink" : "inactiveLink").' href="'.$mainPage.'teachers.php">Teachers</a>
			<a class='.($currentPage == "events.php" ? "activeLink" : "inactiveLink").' href="'.$mainPage.'events.php">Events</a>
			<a class='.($currentPage == "notices.php" ? "activeLink" : "inactiveLink").' href="'.$mainPage.'notices.php">Notices</a>
		';
	if (!isset($_SESSION["login_user"]) && !isset($_SESSION["batch_given"])){
		echo '
		<hr>
		<a class="normalLink"href="'.$mainPage.'login.php">T. Login</a>
		<a class="normalLink"href="'.$mainPage.'batch_login.php">B. Login</a>
		</div>
		';
	  }else if (isset($_SESSION["login_user"])){
		echo '
			<hr>
			<a class='.($currentPage == "home.php" ? "activeLink" : "inactiveLink").' href="'.$mainPage.'home.php">Admin Panel</a>	
			<a class="dangerLink"href="'.$mainPage.'logout.php">Logout</a>
		</div>
		'; 
	  }else if (isset($_SESSION["batch_given"])){
		echo '
		<hr>
			<a class='.($currentPage == "batch_home.php" ? "activeLink" : "inactiveLink").' href="'.$mainPage.'batch_home.php">Home</a>
			<a class='.($currentPage == "resources.php" ? "activeLink" : "inactiveLink").' href="'.$mainPage.'resources.php">Resources</a>
			<a class='.($currentPage == "results.php" ? "activeLink" : "inactiveLink").' href="'.$mainPage.'results.php">Results</a>
			<a class="dangerLink"href="'.$mainPage.'logout.php">Logout</a>
	</div>';
	  }
	?>
	
</header>
<div class="main">    