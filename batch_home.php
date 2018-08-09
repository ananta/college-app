<?php
    include('components/header.php');
    include("config/config.php");
    ob_start();
    $errors = array();
    $message = array();  

    if (!isset($_SESSION["batch_given"])) {
        header("Location: ".$mainPage."batch_login.php");
    }
    $batchID = $_SESSION["batch_given"];
    $sql = "SELECT * FROM batch WHERE batch_id = '$batchID'";
    $result = mysqli_query($db,$sql);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    $count = mysqli_num_rows($result);	
    if(!$count == 1) {
        header("Location: ".$mainPage."pages/general/error.php");
    }
    $batchName = $row['batch_title'];
    $batchEmail = $row['batch_email'];
    $batchProfileImg = $row['batch_img'];
?>

 <div class="row" style="background-color: #fff;">
        <div class="column" style="width:30%">
            <div class="card">
                <img src=' <?php $imgPath = ($batchProfileImg) ? ($mainPage."uploads/batch/landing-picture/".$batchProfileImg) : $mainPage."res/avatar.png"; echo $imgPath; ?>' alt="Avatar Image" >  
            </div>
        </div>
        <div class="column">
        <div class="card" style="padding: 30;">
                <h1 class="cardTitle" style="text-align: left;"><?php echo $batchName; ?></h1>
                <h4 class="cardUsername" style="text-align: left;" >Batch ID: @<?php echo $batchID; ?></h4>
                <h4 class="cardEmail" style="text-align: left;">Batch Email: <?php echo $batchEmail ;?></h4>
        </div>
        </div>
    </div>
    <div class="row">
        <div class="column">
                <div class="minorCardHeader" style="">
                    <h1>Resources</h1>
                </div>
                <?php
                    if($_SERVER["REQUEST_METHOD"] == "GET"){
                        $sql = "SELECT * FROM resource WHERE batch_id = '".$batchID."';";
                        $result = mysqli_query($db,$sql);
                        if(mysqli_num_rows($result) == 0){
                            echo '
                            <div style=" text-align:center;">
                                <h1 style="color: gray;">No Resource Found :D</h1>
                            </div>
                            ';
                        }else {
                            echo '<div class="centeredContainer">
                            <div class="controllableList">
                                <ul>
                            ';
                            while($row = mysqli_fetch_array($result)) { 
                                $file_url = $mainPage."/uploads/resources/".$row["resource_location"];
                                echo '
                                        <li>
                                            <div style="display:inline;">
                                                    <h1>'.$row['resource_title'].'</h1>
                                                    <h4>'.$row['resource_description'].'</h4>
                                                </div>
                                                <div class="controllableListButtons">
                                                    <a href="'.$file_url.'">
                                                        <button class="button" style="background-color:green;">Download</button>
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
        <div class="column">
        <div class="minorCardHeader" style="">
                <h1>Results</h1>
            </div>
        <?php
            if($_SERVER["REQUEST_METHOD"] == "GET"){
                $sql = "SELECT * FROM result WHERE batch_id = '".$batchID."';";
                $result = mysqli_query($db,$sql);
                if(mysqli_num_rows($result) == 0){
                    echo '
                    <div style="text-align:center;">
                        <h1 style="color: gray;">No Results Found :D</h1>
                    </div>
                    ';
                }else {
                    echo '<div class="centeredContainer">
                    <div class="controllableList">
                        <ul>
                    ';
                    while($row = mysqli_fetch_array($result)) { 
                        $file_url = $mainPage."/uploads/results/".$row["result_location"];
                        echo '
                        <li>
                        <div style="display:inline;">
                            <div style="background-color:#235F87; padding:5px;">
                            </div>
                                <h1>'.$row['result_title'].'</h1>
                                <h4>'.$row['result_description'].'</h4>
                            </div>
                            <div class="controllableListButtons">
                            <a href="'.$file_url.'">
                                <button class="button" style="background-color:green;">Download</button>
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


