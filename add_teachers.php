<?php
session_start();
ob_start();
include('utils/session.php');
include('components/header.php');
include('config/config.php');
$errors = array();
$messages = array();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $firstName =$_POST["fname"];
    $lastName =$_POST["lname"];
    $username =$_POST["username"];
    $password =$_POST["password"];
    $confirm_password =$_POST["confirm_password"];
    $email =$_POST["email"];
    $phone = $_POST["password"];
    if(empty($username)){ array_push($errors, "Username Required");};
    if(empty($firstName)){ array_push($errors, "First Name Required");};
    if(empty($lastName)){ array_push($errors, "Last Name Required");};
    if(empty($password)){ array_push($errors, "Password Required");};
    if(empty($phone)){ array_push($errors, "Phone Number Required");};
    if(empty($email)){ array_push($errors, "Email Required");};
    if($password !== $confirm_password){
        array_push($errors, "Password Mismatched");
    };
    if(count($errors) == 0){
        $user_check_query = "SELECT * FROM teacher WHERE username='$username' OR email='$email' LIMIT 1";
        $result = mysqli_query($db, $user_check_query);
        $user = mysqli_fetch_assoc($result);
        
        if ($user) {
          if ($user['username'] === $username) {
            array_push($errors, "Username already exists");
          }
          if ($user['email'] === $email) {
            array_push($errors, "Email already exists");
          }
        }else{
            $password = md5($password);
            $query = "INSERT INTO teacher ( username, password, first_name, last_name, email, phone) VALUES ('".$username."','". $password."','". $firstName."','". $lastName."','".$email."','".$phone."')";
            $data = mysqli_query($db, $query);
            if($data){
                $messages[] = "Added User ".$firstName." ".$lastName;
            }else{
                $errors[] = "ERROR ". $query;
            }
        }
    }
}
?>

<form method="post" action="">
    <h1>Add Teachers</h1>
    <?php include('components/errors.php')?>
    <?php include('components/message.php')?>    
    <input type="text" name="fname" placeholder="First Name"/>
    <input type="text" name="lname" placeholder="Last Name"/>
    <input type="text" name="username" placeholder="Username"/>
    <input type="text" name="password" placeholder="Password"/>
    <input type="text" name="confirm_password" placeholder="Confirm Password"/>
    <input type="email" name="email" placeholder="Email"/>
    <input type="number" name="phone" placeholder="Phone Number"/>
    <input type="submit"class="button buttonBlue" name="Login" value="Submit"/>
</form>

<?php
// $currentPage = substr($currentPage,12);
echo $currentPage;
include('components/footer.php');
?>
