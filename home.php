<?php
    include('utils/session.php');
    include('components/header.php');
?>

    <div class="row">
        <div class="column">
            <div class="card">
                <img src="res/avatar.png" alt="Avatar Image" style="width:100%">  
                <h1 class="cardTitle"><?php echo $_SESSION['name'];?></h1>
                <h4 class="cardUsername">@<?php echo $_SESSION['login_user']?></h4>
                <h4 class="cardEmail"><?php echo $_SESSION['email']?></h4>
                <button class="button buttonGreen">Edit Profile</button>
            </div>
        </div>
        <div class="column">
            <div class="homeGridMenu">
                <?php 
                if($_SESSION["login_user"] == "admin"){
                    echo '<div class="moduleItem"><a href="pages/admin/admin_teachers.php">Teachers</a></div>'; 
                };  
                ?>                
                <div class="moduleItem"><a href="pages/admin/admin_events.php">Events</a></div>
                <div class="moduleItem"><a href="pages/admin/admin_notices.php">Notices</a></div>
                <div class="moduleItem"><a href="pages/admin/admin_result.php">Result</a></div>
                <div class="moduleItem"><a href="pages/admin/admin_resources.php">Resources</a></div>
            </div>
        </div>
    
    </div>
<?php 
    include('components/footer.php');
?>