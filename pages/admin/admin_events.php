<?php
include('../../components/header.php');
include('../../utils/session.php');
include('../../utils/send_email.php');
include('../../config/config.php');
$errors = array();
$messages = array();  

function getBatchInfo($db) {
    $list = array();
    $batchQuery = "select * from batch";
    $result = mysqli_query($db,$batchQuery);
    if(!$result) return null;
    while($row = mysqli_fetch_assoc($result)){
        array_push($list, $row);
    }
    return $list;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $list =$_POST["notifyBatches"];
    // sendEmail("eventEmail", "anantabastola",$list);
    $tmp_title= $_POST["event_title"];
    $event_title = filter_var($tmp_title, FILTER_SANITIZE_MAGIC_QUOTES);
    $event_venue = filter_var($_POST["event_venue"], FILTER_SANITIZE_MAGIC_QUOTES);
    $event_date = $_POST["event_date"];
    $event_description = filter_var($_POST["event_description"], FILTER_SANITIZE_MAGIC_QUOTES);
    $event_bannerLocation = "";
    $file = $_FILES['event_banner'];
    $file_name = $file['name'];
    $file_temp_name = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];
    $file_type = $file['type'];
    if($file_size != 0){
        $file_ext = explode('.', $file_name);
        $file_actual_ext = strtolower(end($file_ext));
        $allowed = array('jpg','jpeg','png','pdf');
        if(in_array($file_actual_ext, $allowed)){
            if($file_error === 0 ){
                if($file_size < 10000000){
                    $file_nameNew = uniqid('', true).".".$file_actual_ext;
                    // $file_destination =  $file_nameNew;
                    if(move_uploaded_file($file_temp_name, $mainLocation."uploads/events/".$file_nameNew)){
                        if(empty($event_title)) { array_push($errors, "Event Title Required"); };
                        if(empty($event_venue)) { array_push($errors, "Event Venue Required"); };
                        $event_bannerLocation = $file_nameNew;
                    } else {
                        array_push($errors, "Something went wrong");
                    }
                } else {
                    array_push($errors, "Your File is Too Big");
                }
            }else{
                array_push($errors, "Something Went Wrong!");
            }
        }else{
            array_push($errors, "File With ".$file_actual_ext." extension is not supported");
        }
    }

    if(count($errors) == 0){
        // Every Thing is Fine
        $current_user = $_SESSION["login_user"];
        $query = "INSERT INTO event ( event_title, event_venue, event_date, event_description, event_image, added_by ) VALUES ('$event_title','$event_venue','$event_date','$event_description','$event_bannerLocation','$current_user')";
        $data = mysqli_query($db, $query);
        if($data){ 
        header("Location: ".$mainPage."pages/admin/admin_events.php?message=".htmlspecialchars("Added Event ".$event_title));
        // $messages[] = "Added Event ".$event_title;
        }else{
            $errors[] = "ERROR ". $query;
        }
    }
}
?>
    <div class="heroic-header">
        <h1 class="heroic-title">Manage Events</h1>
    </div>
    <div class="row">
        <div class="column">
            <div class="minorCardHeader">
                <h1>Add Events</h1>
            </div>
            <form method="post" action="" id="eventsform" enctype="multipart/form-data">
                <?php include('../../components/errors.php')?>
                <?php include('../../components/message.php')?>    
                <input type="text" name="event_title" placeholder="Title"/>
                <input type="text" name="event_venue" placeholder="Venue"/>
                    <h3 class="formLabel">Date Time</h3>
                    <input type="datetime-local" name="event_date" placeholder="Event Date"/>
                    <div>
                        <textarea rows="6" cols="20" name="event_description" form="eventsform" placeholder="Event's Description"></textarea>
                    </div>
                <input type="file" name="event_banner" placeholder="Image"/>
                <h3 class="formLabel">Send Email Notification</h3>
                <div style="margin-top:30px; margin-left:30px">
                <select name="notifyBatches[]" multiple>
                    <?php
                        $batchlist = getBatchInfo($db);
                        foreach( $batchlist as $batch ){
                            echo "<option value='".$batch["batch_email"]."'>".$batch["batch_title"]."</option>";
                        }
                    ?>
                </select>
                </div>
                
                <input type="submit"class="button buttonBlue" name="Login" value="Submit"/>
            </form>
        </div>
        <div class="column">
            <div class="minorCardHeader" style="">
                <h1>Your Events</h1>
            </div>
        <?php
            if($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER["REQUEST_METHOD"] == "POST"){
                $sql = "SELECT * FROM event WHERE added_by = '".$_SESSION["login_user"]."';";
                $result = mysqli_query($db,$sql);
                if(mysqli_num_rows($result) == 0){
                    echo '
                    <div style="line-height:300px; text-align:center; height:300px; ">
                        <h1 style="color: gray;">No Events Found :D</h1>
                    </div>
                    ';
                }else {
                    echo '<div class="centeredContainer">
                    <div class="controllableList">
                        <ul>
                    ';
                    while($row = mysqli_fetch_array($result)) { 
                        echo '
                                <li>
                                    <div style="display:inline;">
                                        <div style="background-color:#235F87; padding:5px;">
                                        <img style="" src="'.$mainPage."uploads/events/".($row["event_image"] ? $row["event_image"] : "default.png").'" alt="Avatar Image">  
                                        </div>
                                            <h1>'.$row['event_title'].'</h1>
                                            <h4>'.$row['event_description'].'</h4>
                                        </div>
                                        <div class="controllableListButtons">
                                            <a href="'.$mainPage.'pages/edit/edit_events.php?editEvent='.$row['event_id'].'">
                                                <button class="button" style="background-color:green;">Edit</button>
                                            </a>
                                    </div>
                                </li>
                            ';
                    }
                    echo '
                                </ul>
                            </div>
                        </div>';
                    }
                }
?>

        </div>

</div>
<?php 
    include('../../components/snackbar.php');
    // include('../../components/footer.php');
    if(isset($_GET["message"])){

        echo "<script type='text/javascript'>showMessage('success', '".$_GET["message"]."')</script>";
    }
?>
