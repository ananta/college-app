<?php
    include('components/header.php');
    include("config/config.php");
    ob_start();
    $errors = array();
    $message = array();  

    if (!isset($_GET["teacherID"])) {
        header("Location: ".$mainPage."teachers.php");
    }
    $teacherID = $_GET["teacherID"];
    $sql = "SELECT * FROM teacher WHERE username = '$teacherID'";
    $result = mysqli_query($db,$sql);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    $count = mysqli_num_rows($result);	
    
    if(!$count == 1) {
        header("Location: ".$mainPage."pages/general/error.php");
    }
    $teacherName = $row['first_name'] . " " . $row['last_name'];
    $teacherEmail = $row['email'];
    $teacherUsername = $row['username'];
    $teacherPhone = $row['phone'];
    $teacherProfileImg = $row['profile_img'];
    // if (!isset($_SESSION["batch_given"])){
    //     header("Location: batch_login.php");
    // }
?>
 <div class="row" style="background-color: #fff;">
        <div class="column" style="width:30%">
            <div class="card">
                <img src=' <?php $imgPath = ($teacherProfileImg) ? ($mainPage."uploads/users/profile-picture/".$teacherProfileImg) : $mainPage."res/avatar.png"; echo $imgPath; ?>' alt="Avatar Image" >  
            </div>
        </div>
        <div class="column">
        <div class="card" style="padding: 30;">
                <h1 class="cardTitle" style="text-align: left;"><?php echo $teacherName; ?></h1>
                <h4 class="cardUsername" style="text-align: left;" >Username: @<?php echo $teacherUsername; ?></h4>
                <h4 class="cardEmail" style="text-align: left;">Email: <?php echo $teacherEmail ;?></h4>
                <?php 
                if(isset($_SESSION["batch_given"]) || isset($_SESSION["login_user"])){
                    echo '
                        <h4 class="cardEmail" style="text-align: left;">Phone: '.$teacherPhone.'</h4>
                    ';
                }else{
                    echo '
                    <a href="http://localhost/gcesServer/batch_login.php">
                        <button class="button buttonGreen">Login For More Info</button>
                    </a>
                    ';
                }
                ?>
        </div>
        </div>
    </div>
    <div class="row">
        <div class="column">
                <div class="minorCardHeader" style="">
                    <h1>Events</h1>
                </div>
                <?php
                    if($_SERVER["REQUEST_METHOD"] == "GET"){
                        $sql = "SELECT * FROM event WHERE added_by = '".$teacherID."';";
                        $result = mysqli_query($db,$sql);
                        if(mysqli_num_rows($result) == 0){
                            echo '
                            <div style=" text-align:center;">
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
        <div class="column">
        <div class="minorCardHeader" style="">
                <h1>Notices</h1>
            </div>
        <?php
            if($_SERVER["REQUEST_METHOD"] == "GET"){
                $sql = "SELECT * FROM notice WHERE added_by = '".$teacherID."';";
                $result = mysqli_query($db,$sql);
                if(mysqli_num_rows($result) == 0){
                    echo '
                    <div style="text-align:center;">
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
                            </div>
                                <h1>'.$row['notice_title'].'</h1>
                                <h4>'.$row['notice_description'].'</h4>
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


