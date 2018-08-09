<?php
    include('components/header.php');
    include("config/config.php");
    
    ob_start();
    $errors = array();
    $message = array();  

   
?>

<link rel="stylesheet" href="<?php echo $mainPage?>css/landing.css?load=<?php echo time();?>" type="text/css">

<div class="float">
    <a class= "info" href="landing.php"> GANDAKI COLLEGE OF ENGINEERING AND SCIENCE</a>
    <a class="contact" href="contact.php">Contact</p>
    <a class="contact" href="about.php">About Us</a>

</div>


<p class="head1"> Contact Us At</p>

<p class="desc">Gandaki College of Engineering and Science<br>
 Lamachaur, Pokhara<br>
Phone: 061-440866<br>
Email: gces@gces.edu.np<br>

</p>