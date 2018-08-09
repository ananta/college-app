<?php
include('../../components/header.php');
include('../../utils/session.php');
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
    $tmp_title= $_POST["resource_title"];
    $resource_title = filter_var($tmp_title, FILTER_SANITIZE_MAGIC_QUOTES);
    $resource_description = filter_var($_POST["resource_description"], FILTER_SANITIZE_MAGIC_QUOTES);
    $resource_location = "";
    $resource_batch = $_POST["notifyBatches"];
    // sendEmail("eventEmail", "anantabastola",$resource_batch);
    $file = $_FILES['resource_object'];
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
                    if(move_uploaded_file($file_temp_name, $mainLocation."uploads/resources/".$file_nameNew)){
                        if(empty($resource_title)) { array_push($errors, "Resource Title Required"); };
                        if(empty($resource_description)) { array_push($errors, "Resource  Description Required"); };
                        $resource_location = $file_nameNew;
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
    }else{
        array_push($errors, "Something Went Wrong");
    }

    if(count($errors) == 0){
        $current_user = $_SESSION["login_user"];
        $query = "INSERT INTO resource ( resource_title, resource_description, resource_location, added_by, batch_id ) VALUES ('$resource_title','$resource_description','$resource_location','$current_user','$resource_batch')";
        $data = mysqli_query($db, $query);
        if($data){ 
        header("Location: ".$mainPage."pages/admin/admin_resources.php?message=".htmlspecialchars("Added Event ".$resource_title));
        }else{
            $errors[] = "ERROR ". $query;
        }
    }
}
?>

    <div class="heroic-header">
        <h1 class="heroic-title">Manage Resources</h1>
    </div>
    <div class="row">
        <div class="column">
            <div class="minorCardHeader">
                <h1>Add Resources</h1>
            </div>
            <form method="post" action="" id="resourcesForm" enctype="multipart/form-data">
                <?php include('../../components/errors.php')?>
                <?php include('../../components/message.php')?>    
                <input type="text" name="resource_title" placeholder="Title"/>
                    <div>
                        <textarea rows="6" cols="20" name="resource_description" form="resourcesForm" placeholder="Resource Description"></textarea>
                    </div>
                <input type="file" name="resource_object" placeholder="Image"/>
                <h3 class="formLabel">Select Batch </h3>
                <div style="margin-top:30px; margin-left:30px">
                <select name="notifyBatches" >
                    <?php
                        $batchlist = getBatchInfo($db);
                        foreach( $batchlist as $batch ){
                            echo "<option value='".$batch["batch_id"]."'>".$batch["batch_title"]."</option>";
                        }
                    ?>
                </select>
                </div>
                <input type="submit"class="button buttonBlue" name="Login" value="Submit"/>
            </form>
        </div>
        <div class="column">
            <div class="minorCardHeader" style="">
                <h1>Your Resources</h1>
            </div>
        <?php
            if($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER["REQUEST_METHOD"] == "POST"){
                $sql = "SELECT * FROM resource WHERE added_by = '".$_SESSION["login_user"]."';";
                $result = mysqli_query($db,$sql);
                if(mysqli_num_rows($result) == 0){
                    echo '
                    <div style="line-height:300px; text-align:center; height:300px; ">
                        <h1 style="color: gray;">No Resources Found :D</h1>
                    </div>
                    ';
                }else{
                    echo '<div class="centeredContainer">
                    <div class="controllableList">
                        <ul>
                    ';
                    while($row = mysqli_fetch_array($result)) { 
                        echo '
                                <li>
                                    <div style="display:inline;">
                                        <div style="background-color:#235F87; padding:5px;">
                                        </div>
                                            <h1>'.$row['resource_title'].'</h1>
                                            <h4>'.$row['resource_description'].'</h4>
                                        </div>
                                        <div class="controllableListButtons">
                                        <a href="'.$mainPage.'pages/general/delete.php?deleteResource='.$row['resource_id'].'">
                                            <button class="button" style="background-color:red;">Delete</button>
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
    include('../../components/footer.php');
    if(isset($_GET["message"])){
        echo "<script type='text/javascript'>showMessage('success', '".$_GET["message"]."')</script>";
    }
?>
