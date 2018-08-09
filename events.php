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
            $date_time = explode(" ",$row["event_date"]);
            $time = $date_time[1];
            $date = $date_time[0];
            $time = explode(":",$time);
            echo '
            <div style="margin:20px;">
            <div class="eventCard">
                <h2 class="eventTitle">'.$row["event_title"].'</h2>
                <div class="eventDescription">
                    <p class="eventDetails">'.$row["event_description"].'</p>
                </div>
                <div style="display:inline-block; padding:8px;">
                    <h5 class="eventSubTitle" ><img src="res/location_icon.png" style="width:12px; "> '.$row["event_venue"].' | <img src="res/clock_icon.png" style="width:12px; margin-right:5px;">'.$date.' | <img src="res/clock_icon.png" style="width:12px; margin-right: 5px">'.$time[0].':'.$time[1].'</h5>
                </div>
                <div class="eventImg">
                    <a href="uploads/events/'. ($row["event_image"] ? $row["event_image"] : "default.png") .'">
                    <img style="width:100%; height:50%"src="uploads/events/'. ($row["event_image"] ? $row["event_image"] : "default.png") .'" alt="image">
                    </a>
                </div>
                <div style="height:2px; background-color:gray;"></div>
                <div class="eventFooter">
                    <p style="color:gray; float:left;">Added By: <a href="'.$mainPage.'view_teacher.php?teacherID='.$row["added_by"].'">@'.$row["added_by"].'</a></p>
                    <p style="color:gray; float:right;" >Created On : '.$row["event_created"].'</p>
                    <p style="clear:both;"></p>
                </div>
                <div></div>
            </div>
            </div>'

                ;
        }
    }
}
?>
</div>
