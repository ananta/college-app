<?php
include('utils/session.php');
include('components/header.php');
include('config/config.php');
$errors = array();
$messages = array();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $event_title = $_POST["event_title"];
    $event_venue = $_POST["event_venue"];
    $event_date = $_POST["event_date"];
    $event_description = $_POST["event_description"];
    $event_bannerLocation = "";

    $file = $_FILES['event_banner'];
    $fileName = $_FILES['event_banner']['name'];
    $fileTmpName = $_FILES['event_banner']['tmp_name'];
    $fileSize = $_FILES['event_banner']['size'];
    $fileError = $_FILES['event_banner']['error'];
    $fileType = $_FILES['event_banner']['type'];

    if($fileSize != 0){
        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));
    
        $allowed = array('jpg','jpeg','png','pdf');
        if(in_array($fileActualExt, $allowed)){
            if($fileError === 0 ){
                if($fileSize < 10000000){
                    $fileNameNew = uniqid('', true).".".$fileActualExt;
                    $fileDestination = 'uploads/events/'. $fileNameNew;
                    if(move_uploaded_file($fileTmpName, $fileDestination)){
                        if(empty($event_title)) { array_push($errors, "Event Title Required"); };
                        if(empty($event_venue)) { array_push($errors, "Event Venue Required"); };
                        $event_bannerLocation = $fileDestination;
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
            array_push($errors, "File With ".$fileActualExt." extension is not supported");
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