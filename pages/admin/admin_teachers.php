<?php
include('../../utils/session.php');
include('../../components/header.php');
include('../../config/config.php');
$errors = array();
$messages = array();  

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $firstName =$_POST["fname"];
    $lastName =$_POST["lname"];
    $username =$_POST["username"];
    $password =$_POST["password"];
    $confirm_password =$_POST["confirm_password"];
    $email =$_POST["email"];
    $phone = $_POST["phone"];
    $file = $_FILES["profileImg"];


    $profile_picture_location="";
    if(empty($username)){ array_push($errors, "Username Required");};
    if(empty($firstName)){ array_push($errors, "First Name Required");};
    if(empty($lastName)){ array_push($errors, "Last Name Required");};
    if(empty($password)){ array_push($errors, "Password Required");};
    if(empty($phone)){ array_push($errors, "Phone Number Required");};
    if(empty($email)){ array_push($errors, "Email Required");};
    if($password !== $confirm_password){ array_push($errors, "Password Mismatched"); };
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
                    if(move_uploaded_file($file_temp_name, $mainLocation."uploads/users/profile-picture/".$file_nameNew)){
                          $profile_picture_location = $file_nameNew;
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
        $user_check_query = "SELECT * FROM teacher WHERE username='$username' OR email='$email' LIMIT 1";
        $result = mysqli_query($db, $user_check_query);
        $user = mysqli_fetch_assoc($result);
        
        if ($user) {
          if ($user['username'] === $username) {
            array_push($errors, "Username already exists");
          }
          if ($user['email'] === $email) {
            array_push($errors, "Email already exists");
          }
        }else{
            $password = md5($password);
            $query = "INSERT INTO teacher ( username, password, first_name, last_name, email, phone, profile_img) VALUES ('".$username."','". $password."','". $firstName."','". $lastName."','".$email."','".$phone."','".$profile_picture_location."')";
            $data = mysqli_query($db, $query);
            if($data){
                $messages[] = "Added User ".$firstName." ".$lastName;
            }else{
                $errors[] = "ERROR ". $query;
            }
        }
    }
}
?>
    <div class="heroic-header">
        <h1 class="heroic-title">Manage Teachers</h1>
    </div>
    <div class="row">
        <div class="column">
            <div class="minorCardHeader">
                <h1>Add Teachers</h1>
            </div>
            <form method="post" action="" id="profileform" enctype="multipart/form-data">
                <?php include('../../components/errors.php')?>
                <?php include('../../components/message.php')?>    
                <input type="text" name="fname" placeholder="First Name"/>
                <input type="text" name="lname" placeholder="Last Name"/>
                <input type="text" name="username" placeholder="Username"/>
                <input type="text" name="password" placeholder="Password"/>
                <input type="text" name="confirm_password" placeholder="Confirm Password"/>
                <input type="email" name="email" placeholder="Email"/>
                <input type="number" name="phone" placeholder="Phone Number"/>
                <input type="file" name="profileImg" placeholder="Image"/>
                <input type="submit"class="button buttonBlue" name="Login" value="Submit"/>
            </form>
        </div>
        <div class="column">
            <div class="minorCardHeader" style="">
                <h1>Available Teachers</h1>
            </div>
        <?php
 if($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER["REQUEST_METHOD"] == "POST"){
    $sql = "SELECT * FROM teacher";
    $result = mysqli_query($db,$sql);
    echo '<div class="centeredContainer">
            <div class="controllableList">
                <ul>
            ';
    while($row = mysqli_fetch_array($result)){
        
        echo '
                <li>
                <div style="display:inline;">
                    <div style="background-color:#235F87; padding:5px;">
                    <img style="" src="'.(($row['profile_img'])?($mainPage.'uploads/users/profile-picture/'.$row['profile_img']):($mainPage.'res/avatar.png')).'" alt="Avatar Image">  
                    </div>
                        <h1>'.$row['first_name']." ". $row['last_name'].'</h1>
                        <h4>'."@".$row['username'].'</h4>
                        <h4>| '.$row['email'].'</h4>
                    </div>
                    <div class="controllableListButtons">
                        <a href="'.$mainPage.'pages/edit/edit_teachers.php?editTeacher='.$row['id'].'">
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
?>
        </div>
    </div>
    <?php 
    include('../../components/snackbar.php');
    include('../../components/footer.php');
    if(isset($_GET["message"])){
        echo "<script type='text/javascript'>showMessage('success', '".$_GET["message"]."')</script>";
    }
    if(isset($_GET["error"])){
        echo "<script type='text/javascript'>showMessage('error', '".$_GET["error"]."')</script>";
    }
?>
