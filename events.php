<?php
    include('components/header.php');
    include("config/config.php");
    ob_start();
    $errors = array();
    $message = array();  
?>
    <div class="heroic-header">
        <h1 class="heroic-title"><img class="heroic-img" src="res/calendar_icon.png">Events</h1>
    </div>
    <div class="events-container">

<?php
 if($_SERVER["REQUEST_METHOD"] == "GET"){
    $sql = "SELECT * FROM event";
    $result = mysqli_query($db,$sql);
    if(mysqli_num_rows($result) == 0){
        echo '
        <div style="line-height:300px; text-align:center; height:300px; ">
            <h1 style="color: gray;">No Events Found :D</h1>
        </div>
        ';
    }else {
        while($row = mysqli_fetch_array($result)){
            echo '
            <div class="eventCard">
                <h2 class="eventTitle">'.$row["event_title"].'</h2>
                <h5 class="eventSubTitle"><img src="res/location_icon.png" style="width:15px;"> '.$row["event_venue"].'</h5>
                <h5 class="eventTime"><img src="res/clock_icon.png" style="width:15px;"> '. $row["event_date"].'</h5>
                <div class="eventImg">
                    <img style="width:100%; height:200px;"src="'. ($row["event_image"] ? $row["event_image"] : "res/default_event.png") .'" alt="image">
                </div>
                <p class="eventDetails">'.$row["event_description"].'</p>
                <div style="height:2px; background-color:gray;"></div>
                <div class="eventFooter">
                    <p style="color:gray; float:left;">Added By: @'.$row["added_by"].'</p>
                    <p style="color:gray; float:right;" >Created On : '.$row["event_created"].'</p>
                </div>
                <div></div>
            </div>'
                ;
        }
    }
}
?>
</div>
