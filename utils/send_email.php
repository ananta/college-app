<?php

function sendEmail($type, $author, $emailList){
    global $_eventTitle;

    switch($type){
        case "eventEmail":
            $_eventTitle = "Event Notification";
        break;
        case "noticeEmail":
            $_eventTitle = "Notice Notification";
        break;
        case "resultEmail":
            $_eventTitle = "Result Notification";
        break;
        case "resourceEmail":
            $_eventTitle = "Resource Notification";
        break;
    }
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From:".$author."<@gces.edu.np>";
    $headers .= "Cc: ".$_eventTitle."\r\n";

    $emailComponent="
        <html>
            <head>
                <title></title>
            </head>
            <body>
                <p>This email contains HTML Tags!</p>
                <table>
                <tr>
                <th>Firstname</th>
                <th>Lastname</th>
                </tr>
                <tr>
                <td>John</td>
                <td>Doe</td>
                </tr>
                </table>
            </body>
        </html>
    ";

    foreach($emailList as $email){
        mail($email,"GCES Notification",$emailComponent,$headers);
    }


}
?>