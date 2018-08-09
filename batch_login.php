<?php
  include('components/header.php');
  include("config/config.php");
  $errors = array();
  $message = array();

  if (isset($_SESSION["batch_given"])){
    header("Location: results.php");
  }

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    $batchId =  mysqli_real_escape_string($db,$_POST['batchId']);
    $batchKeyWord =  mysqli_real_escape_string($db,$_POST['batchKeyWord']);
    $sql = "SELECT * FROM batch WHERE batch_id = '$batchId' and batch_keyword = '$batchKeyWord'";
    $result = mysqli_query($db,$sql);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    $count = mysqli_num_rows($result);	
    
      if($count == 1) {
         $_SESSION['batch_given'] = $batchId;
         header("Location: results.php");
         exit;
      }else {
         $error = "Your Batdh Id or Batch Keyword is invalid";
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
        <input name="batchId" type="text" placeholder="Batch ID">
      </div>
      <div class="group">
        <input  name="batchKeyWord" type="password" placeholder="Batch Keyword">
      </div>
      <input type="submit" class="button buttonBlue" name="View Results" value="Submit"/>
      <?php
              if(isset($_SESSION['message'])){ 
                echo"<div class='group'><label style='font-size:10px;color:red;' >". $_SESSION['message']."</label></div>";};
      ?>      
    </form>
    <script  src="js/index.js"></script>
  </body>
</html>
