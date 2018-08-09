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
    // sendEmail("noticeEmail", "anantabastola",$list);
    $tmp_title= $_POST["notice_title"];
    $notice_title = filter_var($tmp_title, FILTER_SANITIZE_MAGIC_QUOTES);
    $notice_description = filter_var($_POST["notice_description"], FILTER_SANITIZE_MAGIC_QUOTES);

    if(empty($notice_title)) { array_push($errors, "Notice Title Required"); };
    if(empty($notice_description)) { array_push($errors, "Notice Description Required"); };

    if(count($errors) == 0){
        $current_user = $_SESSION["login_user"];
        $query = "INSERT INTO notice ( notice_title, notice_description, added_by ) VALUES ('$notice_title','$notice_description','$current_user')";
        $data = mysqli_query($db, $query);
        if($data){ 
        header("Location: ".$mainPage."pages/admin/admin_notices.php?message=".htmlspecialchars("Added Notice ".$notice_title));
        }else{
            $errors[] = "ERROR ". $query;
        }
    }
}
?>
    <div class="heroic-header">
        <h1 class="heroic-title">Manage Notices</h1>
    </div>
    <div class="row">
        <div class="column">
            <div class="minorCardHeader">
                <h1>Add Notices</h1>
            </div>
            <form method="post" action="" id="noticeform" enctype="multipart/form-data">
                <?php include('../../components/errors.php')?>
                <?php include('../../components/message.php')?>    
                <input type="text" name="notice_title" placeholder="Title"/>
                    <div>
                        <textarea rows="6" cols="20" name="notice_description" form="noticeform" placeholder="Notice's Description"></textarea>
                    </div>
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
                
                <input type="submit"class="button buttonBlue" name="Submit" value="Submit"/>
            </form>
        </div>
        <div class="column">
            <div class="minorCardHeader" style="">
                <h1>Your Notices</h1>
            </div>
        <?php
            if($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER["REQUEST_METHOD"] == "POST"){
                $sql = "SELECT * FROM notice WHERE added_by = '".$_SESSION["login_user"]."';";
                $result = mysqli_query($db,$sql);
                if(mysqli_num_rows($result) == 0){
                    echo '
                    <div style="line-height:300px; text-align:center; height:300px; ">
                        <h1 style="color: gray;">No Notices Found :D</h1>
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
                                        </div>
                                            <h1>'.$row['notice_title'].'</h1>
                                            <h4>'.$row['notice_description'].'</h4>
                                        </div>
                                        <div class="controllableListButtons">
                                            <a href="'.$mainPage.'pages/general/delete.php?deleteNotice='.$row['notice_id'].'">
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
