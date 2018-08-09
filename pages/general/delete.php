<?php
include('../../utils/session.php');
include('../../components/header.php');
include('../../config/config.php');
include('../../utils/verifier.php');
$acceptedDelete = ['deleteNotice', 'deleteResource', 'deleteResult'];
$errors = array();
$messages = array();

if(!$verified){
    header("Location: ".$mainPage."pages/general/error.php");
}

foreach($acceptedDelete as $key) {
    if (isset($_GET[$key])) {
        delete_data($key, $_GET[$key]);
        break;
    }
}

function delete_data($key, $_id) {
    global $db;
    switch($key){
        case "deleteResource":
        $query = 'DELETE FROM resource WHERE resource_id='.$_id;
        $data = mysqli_query($db, $query);
        if ($data) {
        header("Location: ".$mainPage."/gcesServer/pages/admin/admin_resources.php?message=".htmlspecialchars("Sucessfully Deleted Resource"));
        } else {
        array_push($errors,"Unable to Delete Resource");
        }
        break;

        case "deleteNotice":
        $query = 'DELETE FROM notice WHERE notice_id='.$_id;
        $data = mysqli_query($db, $query);
        if ($data) {
        header("Location: ".$mainPage."/gcesServer/pages/admin/admin_notices.php?message=".htmlspecialchars("Sucessfully Deleted Notice"));
        } else {
        array_push($errors,"Unable to Delete Notice");
        }
        break;

        case "deleteResult":
        $query = 'DELETE FROM result WHERE result_id='.$_id;
        $data = mysqli_query($db, $query);
        if ($data) {
        header("Location: ".$mainPage."/gcesServer/pages/admin/admin_results.php?message=".htmlspecialchars("Sucessfully Deleted Result"));
        } else {
        array_push($errors,"Unable to Delete Notice");
        }
        break;
    }
}
?>

<?php
include('../../components/footer.php');
?>