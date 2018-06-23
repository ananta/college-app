<?php
    include('components/header.php');
    include("config/config.php");
    ob_start();
    $errors = array();
    $message = array();  
?>
    <div class="heroic-header">
        <h1 class="heroic-title">Events</h1>
    </div>
    <div class="events-container">
    
    

<?php
 if($_SERVER["REQUEST_METHOD"] == "GET"){
    $sql = "SELECT * FROM event";
    $result = mysqli_query($db,$sql);
    
    while($row = mysqli_fetch_array($result)){
        echo '
        <div class="eventCard">
            <h2>'.$row["event_title"].'</h2>
            <h5>'.$row["event_venue"]. $row["event_date"].'</h5>
            <div class="eventImg" style="height:200px;">
                <img style="width:100%; height:200px;"src="'. ($row["event_image"] ? $row["event_image"] : "res/default_event.png") .'" alt="image">
            </div>
            <p>'.$row["event_description"].'</p>
        </div>'
            ;
    }
}
?>
</div>
