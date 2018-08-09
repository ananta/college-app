<?php
    include('components/header.php');
    include("config/config.php");
    ob_start();
    $errors = array();
    $message = array();  

   
?>

 <div class="row" style="background-color: #fff;">
        <div class="column" style="width:30%">
            <div class="card">
                <img src='res/avatar.png' alt="Avatar Image" >  
            </div>
        </div>
        <div class="column">
        <div class="card" style="padding: 30;">
        </div>
        </div>
    </div>
    <div class="row">
        <div class="column">
            <div class="minorCardHeader" style="">
                <h1>Resources</h1>
            </div>
        </div>
        <div class="column">
            <div class="minorCardHeader" style="">
                    <h1>Results</h1>
            </div>
        </div>
    </div>


