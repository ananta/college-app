<?php
include('../../utils/session.php');
include('../../components/header.php');
include('../../config/config.php');
include('../../utils/verifier.php');

$errors = array();
$messages = array();
$imageName ='';
$newImageRequired= false;
$eventID = $_GET["editEvent"];
if(!$verified){
    header("Location: ".$mainPage."pages/general/error.php");
}

if($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER["REQUEST_METHOD"] == "POST"){
    $sql = "SELECT * FROM event WHERE event_id = '".$_GET["editEvent"]."';";
    $result = mysqli_query($db,$sql);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    if(mysqli_num_rows($result) == 0){
        array_push($errors, "Oops Something Went Wrong");
    }else {
        $imageName = $row["event_image"];
        $editFORM = '
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
                <input type="submit"class="button buttonBlue" name="update" value="Update"/>
                <input type="submit"class="button" style="background-color:red;" name="delete" value="Delete"/>
            </form>
        ';                
    }
}

if(isset($_POST['update'])){
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
                    $file_destination = '/opt/lampp/htdocs/gcesServer/uploads/events/'. $file_nameNew;
                    if(is_uploaded_file($file_temp_name)){
                        if(move_uploaded_file($file_temp_name, $file_destination)){
                            if(empty($event_title)) { array_push($errors, "Event Title Required"); };
                            if(empty($event_venue)) { array_push($errors, "Event Venue Required"); };
                            $event_bannerLocation = $file_nameNew;
                            $newImageRequired = true;
                        } else {
                            array_push($errors, "TEMPORARY +> ".$file_temp_name);
                            array_push($errors, "DESTINATION +> ".$file_destination);
                            array_push($errors, "Something went wrong");
                        }
                    }else{
                        $newImageRequired = false;
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
        $finalImage='';
        if(empty($imageName) || $newImageRequired){
            $finalImage = $event_bannerLocation;
            array_push($messages," NEW ".$finalImage);
        }else{
            $finalImage = $imageName;
            array_push($messages," OLD ".$imageName);
        }
        $query = 'UPDATE event SET event_title="'.$event_title.'",event_venue="'.$event_venue.'",event_date="'.$event_date.'",event_description="'.$event_description.'",event_image="'.$finalImage.'" WHERE event_id='.$eventID.';';
        $data = mysqli_query($db, $query);
        if($data){
            $messages[] = "Updated Event ".$event_title;
        }else{
            $errors[] = "ERROR ". $query;
        }
    }
}
if(isset($_POST['delete'])){
     $query = 'DELETE FROM event WHERE event_id='.$eventID;
     $data = mysqli_query($db, $query);
     if ($data) {
        header("Location: ".$mainPage."pages/admin/admin_events.php?message=".htmlspecialchars("Sucessfully Deleted"));
    } else {
        array_push($errors,"Unable to Delete the Event");
    }
}
?>

<div class="row">
    <?php include("../../components/errors.php")?>
    <?php include("../../components/message.php")?>  
    <div class="column">
        <img style="width:600px; height:auto;"src='<?php $imgPath = $imageName ? $mainPage."/uploads/events/".$imageName : $mainPage."/uploads/events/default.png"; echo $imgPath; ?>' alt="Avatar Image" style="width:100%">  
    </div>
    <div class="column">
            <?php
                echo $editFORM;
            ?>
    </div>
</div>

<?php
include('../../components/footer.php');
?>