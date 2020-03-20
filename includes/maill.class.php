<?php

require_once ('class.phpmailer.php');
$to_email = "velmani.eswaran@sirahu";
$subject = "Simple Email Test via PHP";
$body = "Hi-3";
$headers = 'List-Unsubscribe: <list-request@acme.com?subject=unsubscribe><http://www.acme.com/unsubscribe.html?opaque=987654321>' . "\r\n" ;

 
if (mail($to_email, $subject, $body, $headers)) {
    echo "Email successfully sent to $to_email...";
} else {
    echo "Email sending failed...";
}


// // 
// require_once ('class.phpmailer.php');
// $mailer = new PHPMailer();
// $mailer->From = "velmani.eswaran@sirahu.com";
// $mailer->FromName = "velmani";
// $mailer->AddAddress ("velmani.eswaran@sirahu.com","To Name");
// $mailer->AddReplyTo("velmani.eswaran@sirahu.com","From Name");
// $mailer->AddAttachment("vcall.xml");
// $mailer->Subject = "xml";
// $mailer->Body = "Hi vel,\n\nHere is your xml file.\n\nvelu";

// if($mailer->Send()){
//         echo "sent";
// } else {
//         echo "not sent";
// // }
