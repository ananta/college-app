<?php
    include('components/header.php');
    include("config/config.php");
    ob_start();
    $errors = array();
    $message = array();  
?>
    <div class="heroic-header">
        <h1 class="heroic-title">Teachers</h1>
    </div>

<?php
 if($_SERVER["REQUEST_METHOD"] == "GET"){
    $sql = "SELECT * FROM teacher";
    $result = mysqli_query($db,$sql);
    echo '<div class="grid-container">
            ';
    while($row = mysqli_fetch_array($result)){
        echo '<div class ="grid-item">
                <div class="teachersCard">
                    <img style=" width:100%;border-top-left-radius:10px;border-top-right-radius:10px;" src="res/avatar.png" alt="Avatar Image" style="width:100%">  
                    <h1 class="cardTitle">'.$row['first_name']." ". $row['last_name'].'</h1>
                    <h4 class="cardUsername">'."@".$row['username'].'</h4>
                    <h4 class="cardEmail">'.$row['email'].'</h4>
                    <button class="button buttonGreen">Contact</button>
                </div>
            </div>
        ';
    }
        echo '</div>';
}
?>
