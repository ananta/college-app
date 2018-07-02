<?php
include('../../utils/session.php');
include('../../components/header.php');
include('../../config/config.php');
include('../../utils/verifier.php');
$errors = array();
$messages = array();



if(!$verified){
    header("Location: ".$mainPage."pages/general/error.php");
}
// echo $_GET["editEvent"]; 



?>
<div class="row">
    <?php include("../../components/errors.php")?>
    <?php include("../../components/message.php")?>  
    <div style="background-color:red" class="column">

    </div>
    <div style="background-color:red" class="column">

            <?php
                if($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER["REQUEST_METHOD"] == "POST"){
                    $sql = "SELECT * FROM event WHERE event_id = '".$_GET["editEvent"]."';";
                    $result = mysqli_query($db,$sql);
                    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
                    if(mysqli_num_rows($result) == 0){
                        array_push($errors, "Oops Something Went Wrong");
                    }else {
                        
                        echo'
                        <form method="post" action="" id="eventsform" enctype="multipart/form-data">
                            <h1>Edit Events</h1>
                            <input type="text" name="event_title" placeholder="Title" value="'.$row["event_title"].'"/>
                            <input type="text" name="event_venue" placeholder="Venue" value="'.$row["event_venue"].'"/>
                            <h3 class="formLabel">Date Time</h3>
                            <input type="datetime-local" name="event_date" value="'.date("Y-m-d\TH:i:s", strtotime($row["event_date"])).'"placeholder="Event Date"/>
                            <div>
                                <textarea rows="6" cols="20" name="event_description" form="eventsform" placeholder="Events Description">'.$row["event_description"].'</textarea>
                            </div>
                            <input type="file" name="event_banner" placeholder="Image"/>
                            <input type="submit"class="button buttonBlue" name="Login" value="Update"/>
                        </form>
                        ';                
                    }
                }
            ?>
    </div>
</div>
<?php

include('../../components/footer.php');
?>