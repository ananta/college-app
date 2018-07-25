<?php
include('../../utils/session.php');
include('../../components/header.php');
include('../../config/config.php');
$errors = array();
$messages = array();  

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $b_id =$_POST["b_id"];
    $b_email =$_POST["b_email"];
    $b_title =$_POST["b_title"];
    $b_keyword =$_POST["b_keyword"];
    $file = $_FILES["batchImg"];


    $profile_picture_location="";
    if(empty($b_id)){ array_push($errors, "Batch ID Required");};
    if(empty($b_email)){ array_push($errors, "Batch Email Required");};
    if(empty($b_title)){ array_push($errors, "Batch Title Required");};
    if(empty($b_keyword)){ array_push($errors, "Batch Keyword Required");};
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
                    if(move_uploaded_file($file_temp_name, $mainLocation."uploads/batch/landing-picture/".$file_nameNew)){
                          $batch_picture_location = $file_nameNew;
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
        $batch_check_query = "SELECT * FROM batch WHERE batch_id='$b_id'  LIMIT 1";
        $result = mysqli_query($db, $batch_check_query);
        $batch = mysqli_fetch_assoc($result);
        
        if ($batch) {
          if ($batch['batch_id'] === $b_id) {
            array_push($errors, "Batch ID already exists! Please Use Different Batch ID");
          }
        }else{
            $query = "INSERT INTO batch ( batch_id, batch_email, batch_title, batch_keyword, batch_img) VALUES ('".$b_id."','". $b_email."','". $b_title."','". $b_keyword."','".$batch_picture_location."')";
            $data = mysqli_query($db, $query);
            if($data){
                $messages[] = "Added Batch ".$b_title;
            }else{
                $errors[] = "ERROR ". $query;
            }
        }
    }
}
?>
    <div class="heroic-header">
        <h1 class="heroic-title">Manage Batch</h1>
    </div>
    <div class="row">
        <div class="column">
            <div class="minorCardHeader">
                <h1>Add Batch</h1>
            </div>
            <form method="post" action="" id="batchform" enctype="multipart/form-data">
                <?php include('../../components/errors.php')?>
                <?php include('../../components/message.php')?>    
                <input type="text" name="b_id" placeholder="Batch ID"/>
                <input type="email" name="b_email" placeholder="Batch Email"/>
                <input type="text" name="b_title" placeholder="Batch Title"/>
                <input type="text" name="b_keyword" placeholder="Batch Keyword"/>
                <input type="file" name="batchImg" placeholder="Class Photo"/>
                <input type="submit"class="button buttonBlue" name="AddBatch" value="Submit"/>
            </form>
        </div>
        <div class="column">
            <div class="minorCardHeader" style="">
                <h1>Available Batches</h1>
            </div>
        <?php
 if($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER["REQUEST_METHOD"] == "POST"){
    $sql = "SELECT * FROM batch";
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
                    <img style="" src="'.(($row['batch_img'])?($mainPage.'uploads/batch/landing-picture/'.$row['batch_img']):($mainPage.'res/avatar.png')).'" alt="Batch Banner">  
                    </div>
                        <h1>'.$row['batch_title'].'</h1>
                        <h4>'."@".$row['batch_email'].'</h4>
                        <h4>| '.$row['batch_id'].'</h4>
                    </div>
                    <div class="controllableListButtons">
                        <a href="'.$mainPage.'pages/edit/edit_batchs.php?editBatch='.$row['batch_id'].'">
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
