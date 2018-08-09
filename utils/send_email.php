<?php
require '/opt/lampp/htdocs/gcesServer/PHPMailerAutoload.php';
function sendEmail($type, $emailList, $htmlBody){

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
    try {
        //Server settings
        $mail = new PHPMailer(true);  
        // $mail->SMTPDebug = 4;  
        $mail->isSMTP();                             
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;                              
        $mail->Username = 'nodexeon@gmail.com';
        $mail->Password = 'bastola123';
        $mail->SMTPSecure = 'tls';                            
        $mail->Port = 587;   
        //Recipients
        foreach($emailList as $email){
            $mail->addAddress($email); 
        }
        $mail->setFrom('webapp@gces.edu.np', 'GCES WEBAPP');
        $mail->addReplyTo('nodexeon@gmail.com', 'WEBAPP');
        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail->isHTML(true);
        $mail->Subject = "GCES WEBAPP - ".$_eventTitle;
        $mail->Body    = $htmlBody."\n BODY";
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        $mail->send();
        return true;
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        return $mail->ErrorInfo;
    }



}
?>