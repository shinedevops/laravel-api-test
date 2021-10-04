<?php
 
namespace App\Traits;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Swift_Attachment;
 
trait SendMailTrait {

    public function send_mail($to, $subject, $boby, $cc = NULL, $file = NULL, $imageName = NULL){
        $from_email = 'developersd.shinedezign@gmail.com';
        $from_name = 'Developer Sd';
        $password = 'sxfwuillpwapakep';
        // $password = decrypt_userdata(Config::get('constants.SMTP_PASSWORD'));
        $host = 'smtp.gmail.com';
        $port = 587;
        $encryption = 'tls';
        // Create the Transport
        $transport = (new Swift_SmtpTransport($host, $port, $encryption ))
        ->setUsername($from_email)
        ->setPassword($password);
        
        // Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);
        
        // Create a message
        $message = new Swift_Message();
        $message->setSubject($subject);
        $message->setFrom([$from_email => $from_name]);
        $message->setTo($to);
        if($cc)
        $message->setCc($cc);
        if($file)
        {
            $attachment = Swift_Attachment::fromPath($file->getRealPath(), $file->getMimeType())->setFilename($imageName);

            // Attach it to the message
            $message->attach($attachment);
        }
        $message->setBody($boby, 'text/html');
        // Send the message
        $result = $mailer->send($message);
        return $result;
    }
 
}