<?php
$acceptedArray = ["editEvent", "editTeacher", "editResource", "editResult", "editTeacher", "deleteNotice", "editBatch", "deleteNotice", "deleteResult"];
function add_to_db($key, $content) {
    global $db;
    global $verified;
    switch($key){
        case "editEvent":
            $sql = 'SELECT * FROM event WHERE event_id = '.$content.' AND  added_by="'.$_SESSION["login_user"].'";';
            $result = mysqli_query($db,$sql);
            $verified = (mysqli_num_rows($result) > 0) ? true : false; 
            echo $verified."verified";
        break;
        case "editTeacher":
        $sql = 'SELECT * FROM teacher WHERE id='.$content.';';
        $result = mysqli_query($db,$sql);
        $verified = (mysqli_num_rows($result) > 0) ? true : false; 
        break;
        case "editBatch":
        $verified = ($_SESSION["login_user"] == "admin") ? true : false; 
        break;
        case "deleteNotice":
        $sql = 'SELECT * FROM notice WHERE notice_id = '.$content.' AND  added_by="'.$_SESSION["login_user"].'";';
        $result = mysqli_query($db,$sql);
        $verified = (mysqli_num_rows($result) > 0) ? true : false; 
        break;
    }
}

$found = false;
foreach($acceptedArray as $key) {
    if (isset($_GET[$key])) {
        $found = true;
        add_to_db($key, $_GET[$key]);
        break;
    }
}
if (!$found) {
    header("Location: ".$mainPage."home.php");

}

?>