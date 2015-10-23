<?php

include "libmail.php";
$m = new Mail;
$m->From("mail@sturm.com.ua");
$m->To("kottem@mail.ru");
$m->Subject("test");
$m->Body("text la la la la la ");
$m->Priority(1);
$m->smtp_on("ssl://smtp.gmail.com","mail@sturm.com.ua","natalia2002",465);//465 587
$m->Send();
echo $m->Get();

/*function sendHtmlMail($from, $to, $subject, $body, $attachments = array()) {
    require_once 'swift_required.php';
    $message = Swift_Message::newInstance()
      ->setSubject($subject)
      ->setFrom($from)
      ->setTo($to)
      ->setBody($body, 'text/html');
    $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 587)
      ->setUsername('mail@sturm.com.ua')
      ->setPassword('natalia2002');
    $mailer = Swift_Mailer::newInstance($transport);
    echo "transport\n";
   // return $mailer->send($message);
    echo "send\n";
}
echo "start\n"; 
//var_dump(sendHtmlMail("mail@sturm.com.ua", "mail@sturm.com.ua", "subject", "body"));
sendHtmlMail("mail@sturm.com.ua", "mail@sturm.com.ua", "subject", "body");

echo "end";*/
?>


