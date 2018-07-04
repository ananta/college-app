<?php
include('utils/session.php');
include('components/header.php');
include('config/config.php');
$errors = array();
$messages = array();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $tmp_title= $_POST["event_title"];
    $event_title = filter_var($tmp_title, FILTER_SANITIZE_MAGIC_QUOTES);
    $event_venue = filter_var($_POST["event_venue"], FILTER_SANITIZE_MAGIC_QUOTES);
    $event_date = $_POST["event_date"];
    $event_description = filter_var($_POST["event_description"], FILTER_SANITIZE_MAGIC_QUOTES);
    $event_bannerLocation = "";
    $file = $_FILES['event_banner'];
    $file_name = $_FILES['event_banner']['name'];
    $file_temp_name = $_FILES['event_banner']['tmp_name'];
    $file_size = $_FILES['event_banner']['size'];
    $file_error = $_FILES['event_banner']['error'];
    $file_type = $_FILES['event_banner']['type'];

    if($file_size != 0){
        $file_ext = explode('.', $file_name);
        $file_actual_ext = strtolower(end($file_ext));
    
        $allowed = array('jpg','jpeg','png','pdf');
        if(in_array($file_actual_ext, $allowed)){
            if($file_error === 0 ){
                if($file_size < 10000000){
                    $file_nameNew = uniqid('', true).".".$file_actual_ext;
                    $file_destination = 'uploads/events/'. $file_nameNew;
                    if(move_uploaded_file($file_temp_name, $file_destination)){
                        if(empty($event_title)) { array_push($errors, "Event Title Required"); };
                        if(empty($event_venue)) { array_push($errors, "Event Venue Required"); };
                        $event_bannerLocation = $file_destination;
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
            $messages[] = "Added Event ".$event_title;
        }else{
            $errors[] = "ERROR ". $query;
        }
    }
}
?>

<form method="post" action="" id="eventsform" enctype="multipart/form-data">
    <h1>Add Events</h1>
    <?php include('components/errors.php')?>
    <?php include('components/message.php')?>    
    <input type="text" name="event_title" placeholder="Title"/>
    <input type="text" name="event_venue" placeholder="Venue"/>
    <h3 class="formLabel">Date Time</h3>
    <input type="datetime-local" name="event_date" placeholder="Event Date"/>
    <div>
        <textarea rows="6" cols="20" name="event_description" form="eventsform" placeholder="Event's Description"></textarea>
    </div>
    <input type="file" name="event_banner" placeholder="Image"/>
    <input type="submit"class="button buttonBlue" name="Login" value="Submit"/>
</form>


<?php
include('components/footer.php');
?>