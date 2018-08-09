<?php
  include('components/header.php');
  include("config/config.php");
  $errors = array();
  $message = array();

  if (isset($_SESSION["login_user"])){
    header("Location: home.php");
  }

  if($_SERVER["REQUEST_METHOD"] == "POST"){

    $username =  mysqli_real_escape_string($db,$_POST['username']);
    $password =  mysqli_real_escape_string($db,$_POST['password']);
    $password = md5($password);
    $sql = "SELECT * FROM teacher WHERE username = '$username' and password = '$password'";
    $result = mysqli_query($db,$sql);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    $count = mysqli_num_rows($result);	
    
      if($count == 1) {
         $_SESSION['login_user'] = $username;
         $_SESSION['name'] = $row['first_name'] .' '. $row['last_name'];
         $_SESSION['id'] = $row['id'];
         $_SESSION['profileImg'] = $row['profile_img'];
         $_SESSION['email'] = $row['email'];
        if($_SESSION['login_user'] == "admin"){
          $_SESSION['isAdmin'] = true;
        }
         header("Location: home.php");
         $_SESSION['message'] = "Success ".$_SESSION['login_user'];
         exit;
      }else {
        header("Location: login.php");
         $error = "Your Login Name or Password is invalid";
         $_SESSION['message'] = $error;
         exit;
      }
   
   }
?>
  <body>
   
    <form method="post" action="">
    <div  class ="group">
    <img style="margin-left:auto; margin-right:auto; height:120px; width:120px; display:block;"src="res/logo_main.png" alt="GCES LOGO">
    </div>
    <?php include('components/errors.php') ?>
      <div class="group">
        <input name="username" type="text" placeholder="Username">
      </div>
      <div class="group">
        <input  name="password" type="password" placeholder="Password">
      </div>
      <input type="submit" class="button buttonBlue" name="Login" value="Submit"/>
      <?php
              if(isset($_SESSION['message'])){ 
                echo"<div class='group'><label style='font-size:10px;color:red;' >". $_SESSION['message']."</label></div>";};
      ?>      
    </form>
    <?php
      include('components/footer.php');
    ?>
    <script  src="js/index.js"></script>
  </body>
</html>
