<?php
    include('components/header.php');
    include("config/config.php");
    ob_start();
    $errors = array();
    $message = array();  

    if (!isset($_SESSION["batch_given"])){
        header("Location: batch_login.php");
    }
?>
    <div class="heroic-header">
        <h1 class="heroic-title"><img class="heroic-img" src="res/calendar_icon.png">Resources</h1>
    </div>
    <div class="events-container">

<?php
 if($_SERVER["REQUEST_METHOD"] == "GET"){
    $sql = "SELECT * FROM resource where batch_id = '".$_SESSION['batch_given']."' ORDER BY resource_created DESC;";
    $result = mysqli_query($db,$sql);
    if(mysqli_num_rows($result) == 0){
        echo '
        <div style="line-height:300px; text-align:center; height:300px; ">
            <h1 style="color: gray;">No Resources Found :D</h1>
        </div>
        ';
    }else {
        while($row = mysqli_fetch_array($result)){
            $file_url = $mainPage."/uploads/resources/".$row["resource_location"];

            echo '
            <div class="eventCard">
                <h2 class="eventTitle">'.$row["resource_title"].'</h2>
                <p class="eventDetails">'.$row["resource_description"].'</p>
                <div style="height:2px; background-color:gray;"></div>
                <a href="'.$file_url.'">
                    <button class="button" style="background-color:green;">Download</button>
                </a>
                <div class="eventFooter">
                    <p style="color:gray; float:left;">Added By: @'.$row["added_by"].'</p>
                    <p style="color:gray; float:right;" >Created On : '.$row["resource_created"].'</p>
                </div>
                <div></div>
            </div>'
                ;
        }
    }
}
?>
</div>
