<?php

    include('components/header.php');
    include('utils/session.php');
?>

    <div class="row">
        <div class="column">
            <div class="card">
                <img src=' <?php $imgPath = ($_SESSION['profileImg']) ? ($mainPage."uploads/users/profile-picture/".$_SESSION['profileImg']) : $mainPage."res/avatar.png"; echo $imgPath; ?>' alt="Avatar Image" >  
                <h1 class="cardTitle"><?php echo $_SESSION['name'];?></h1>
                <h4 class="cardUsername">@<?php echo $_SESSION['login_user']?></h4>
                <h4 class="cardEmail"><?php echo $_SESSION['email']?></h4>
                <a href="http://localhost/gcesServer/pages/admin/admin_edit_profile.php">
                    <button class="button buttonGreen">Edit Profile</button>
                </a>
            </div>
        </div>
        <div class="column">
            <div class="homeGridMenu">
                <?php 
                if($_SESSION["login_user"] == "admin"){
                    echo '<div class="moduleItem"><a href="pages/admin/admin_teachers.php">Teachers</a></div>'; 
                    echo '<div class="moduleItem"><a href="pages/admin/admin_batch.php">Batch</a></div>';
                };  
                ?>                
                <div class="moduleItem"><a href="pages/admin/admin_events.php">Events</a></div>
                <div class="moduleItem"><a href="pages/admin/admin_notices.php">Notices</a></div>
                <div class="moduleItem"><a href="pages/admin/admin_results.php">Result</a></div>
                <div class="moduleItem"><a href="pages/admin/admin_resources.php">Resources</a></div>
            </div>
        </div>
    
    </div>
<?php 
    include('components/footer.php');
?>
