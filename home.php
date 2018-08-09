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
                    echo '<div class="moduleItem"><a href="pages/admin/admin_teachers.php">
                    <img class= "moduleItem" src="icons/teachers.png" alt="Teachers">
                    </a></div>'; 
                    echo '<div class="moduleItem"><a href="pages/admin/admin_batch.php">
                    <img class= "moduleItem" src="icons/batches.png" alt="Batch">
                    </a>
                    </div>';
                 
                
                };  
                ?> 

                <div class="moduleItem"><a href="events.php">
                    <img class= "moduleItem" src="icons/event.png" alt="Events"></a>

                </div>
                <div class="moduleItem"><a href="pages/admin/admin_notices.php">
                <img class= "moduleItem" src="icons/rem.png" alt="Notices"></a>
                </div>
                <div class="moduleItem"><a href="pages/admin/admin_result.php">
                <img class= "moduleItem" src="icons/result.png" alt="Result"></a>
                </div>
                <div class="moduleItem"><a href="pages/admin/admin_resources.php">
                <img class= "moduleItem" src="icons/resource.png" alt="Resources"></a>

              
            </div>
        </div>
    
    </div>
<?php 
    if(isset($_SESSION["isAdmin"])){
        if($_SESSION["isAdmin"]){
            echo "Admin";
        }else {
            echo "This was not ment to be happened";
        }
    }else{
        echo "Not Admin";   
    }
    include('footer.php');
    
?>
