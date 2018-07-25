<?php
include('../../utils/session.php');
include('../../components/header.php');
include('../../config/config.php');
include('../../utils/verifier.php');

$errors = array();
$messages = array();
$imageName ='';
$newImageRequired= false;
$teacherID = $_GET["editTeacher"];
if(!$verified){
    // echo "Verified".$verified;
    header("Location: ".$mainPage."pages/general/error.php");
}

if($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER["REQUEST_METHOD"] == "POST"){
    $sql = "SELECT * FROM teacher WHERE id = '".$_GET["editTeacher"]."';";
    $result = mysqli_query($db,$sql);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    if(mysqli_num_rows($result) == 0){
        array_push($errors, "Oops Something Went Wrong");
    }else {
        $imageName = $row["profile_img"];
        $editFORM = '
            <form method="post" action="" id="eventsform" enctype="multipart/form-data">
                <h1>Edit Teachers</h1>
                <input type="text" name="first_name" placeholder="First Name" value="'.$row["first_name"].'"/>
                <input type="text" name="last_name" placeholder="Last Name" value="'.$row["last_name"].'"/>
                <input type="text" name="email" placeholder="Email" value="'.$row["email"].'"/>
                <input type="text" name="phone" placeholder="Phone" value="'.$row["phone"].'"/>
                <input type="file" name="profile_img" placeholder="Image"/>
                <input type="submit"class="button buttonBlue" name="update" value="Update"/>
                <input type="submit"class="button" style="background-color:red;" name="delete" value="Delete"/>
            </form>
        ';                
    }
}

if(isset($_POST['update'])){
    $first_name = filter_var($_POST["first_name"], FILTER_SANITIZE_MAGIC_QUOTES);
    $last_name = filter_var($_POST["last_name"], FILTER_SANITIZE_MAGIC_QUOTES);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_MAGIC_QUOTES);
    // $password = filter_var($_POST["password"], FILTER_SANITIZE_MAGIC_QUOTES);
    $phone = filter_var($_POST["phone"], FILTER_SANITIZE_MAGIC_QUOTES);
    $profileImg = "";
    $file = $_FILES['profile_img'];
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
                    $file_destination = '/opt/lampp/htdocs/gcesServer/uploads/users/profile-picture/'. $file_nameNew;
                    if(is_uploaded_file($file_temp_name)){
                        if(move_uploaded_file($file_temp_name, $file_destination)){
                            if(empty($first_name)) { array_push($errors, "First Name Required"); };
                            if(empty($last_name)) { array_push($errors, "Last Name Required"); };
                            $profileImg = $file_nameNew;
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
            $finalImage = $profileImg;
            array_push($messages," NEW ".$finalImage);
        }else{
            $finalImage = $imageName;
            array_push($messages," OLD ".$imageName);
        }
        $query = 'UPDATE teacher SET first_name="'.$first_name.'",last_name="'.$last_name.'",email="'.$email.'",phone="'.$phone.'",profile_img="'.$finalImage.'" WHERE id='.$teacherID.';';
        $data = mysqli_query($db, $query);
        if($data){
            $messages[] = "Updated Teacher ".$first_name;
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
        <img style="width:600px; height:auto;"src='<?php $imgPath = ($imageName) ? ($mainPage."uploads/users/profile-picture/".$imageName) : $mainPage."res/avatar.png"; echo $imgPath; ?>' alt="Avatar Image" style="width:100%">  
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