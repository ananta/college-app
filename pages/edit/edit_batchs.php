<?php
include('../../utils/session.php');
include('../../components/header.php');
include('../../config/config.php');
include('../../utils/verifier.php');

$errors = array();
$messages = array();
$imageName ='';
$newImageRequired= false;
$batchID = $_GET["editBatch"];
if(!$verified){
    // echo "Verified".$verified;
    header("Location: ".$mainPage."pages/general/error.php");
}

if($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER["REQUEST_METHOD"] == "POST"){
    $sql = "SELECT * FROM batch WHERE batch_id = '".$_GET["editBatch"]."';";
    $result = mysqli_query($db,$sql);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    if(mysqli_num_rows($result) == 0){
        array_push($errors, "Oops Something Went Wrong");
    }else {
        $imageName = $row["batch_img"];
        $editFORM = '
            <form method="post" action="" id="batchsform" enctype="multipart/form-data">
                <h1>Edit Teachers</h1>
                <input type="text" name="batch_id" placeholder="Batch ID" value="'.$row["batch_id"].'"/>
                <input type="text" name="batch_email" placeholder="Batch Email" value="'.$row["batch_email"].'"/>
                <input type="text" name="batch_title" placeholder="Batch Title" value="'.$row["batch_title"].'"/>
                <input type="text" name="batch_keyword" placeholder="Batch Keyword" value="'.$row["batch_keyword"].'"/>
                <input type="file" name="batch_img" placeholder="Image"/>
                <input type="submit"class="button buttonBlue" name="update" value="Update"/>
                <input type="submit"class="button" style="background-color:red;" name="delete" value="Delete"/>
            </form>
        ';                
    }
}

if(isset($_POST['update'])){
    $old_batch_id = $_GET["editBatch"];
    $batch_id = filter_var($_POST["batch_id"], FILTER_SANITIZE_MAGIC_QUOTES);
    $batch_email = filter_var($_POST["batch_email"], FILTER_SANITIZE_MAGIC_QUOTES);
    $batch_title = filter_var($_POST["batch_title"], FILTER_SANITIZE_MAGIC_QUOTES);
    $batch_keyword = filter_var($_POST["batch_keyword"], FILTER_SANITIZE_MAGIC_QUOTES);
    $profileImg = "";
    $file = $_FILES['batch_img'];
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
                    $file_destination = '/opt/lampp/htdocs/gcesServer/uploads/batch/landing-picture/'. $file_nameNew;
                    if(is_uploaded_file($file_temp_name)){
                        if(move_uploaded_file($file_temp_name, $file_destination)){
                            if(empty($batch_id)) { array_push($errors, "Batch ID is Required"); };
                            if(empty($batch_email)) { array_push($errors, "Batch Email is Required"); };
                            if(empty($batch_title)) { array_push($errors, "Batch Title is Required"); };
                            if(empty($batch_keyword)) { array_push($errors, "Batch Keyword is Required"); };
                            $landingImg = $file_nameNew;
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
            $finalImage = $landingImg;
            array_push($messages," NEW ".$finalImage);
        }else{
            $finalImage = $imageName;
            array_push($messages," OLD ".$imageName);
        }
        $query = 'UPDATE batch SET batch_id="'.$batch_id.'",batch_email="'.$batch_email.'",batch_title="'.$batch_title.'",batch_keyword="'.$batch_keyword.'",batch_img="'.$finalImage.'" WHERE batch_id="'.$old_batch_id.'";';
        $data = mysqli_query($db, $query);
        if($data){
            $messages[] = "Updated Batch ".$batch_title;
        }else{
            $errors[] = "ERROR ". $query;
        }
    }
}
if(isset($_POST['delete'])){
     $query = 'DELETE FROM teacher WHERE id='.$teacherID;
     $data = mysqli_query($db, $query);
     if ($data) {
        header("Location: ".$mainPage."pages/admin/admin_teachers.php?message=".htmlspecialchars("Sucessfully Deleted"));
    } else {
        array_push($errors,"Unable to Delete Teacher");
    }
}
?>

<div class="row">
    <?php include("../../components/errors.php")?>
    <?php include("../../components/message.php")?>  
    <div class="column">
        <img style="width:600px; height:auto;"src='<?php $imgPath = ($imageName) ? ($mainPage."uploads/batch/landing-picture/".$imageName) : $mainPage."res/avatar.png"; echo $imgPath; ?>' alt="Avatar Image" style="width:100%">  
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