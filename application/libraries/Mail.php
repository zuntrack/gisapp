<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
use Dompdf\Dompdf;

/*
 *  @author : CodesLab
 *  @support: support@codeslab.net
 *  date    : 05 June, 2015
 *  Easy Inventory
 *  http://www.codeslab.net
 *  version: 1.0
 */

class Mail
{
    public function __construct()
    {
        $this->CI = &get_instance();
        //$this->CI->load->library('email');
        $this->CI->load->helper(array('dompdf', 'file'));
    }

   /* public function sendEmail($from, $to, $subject, $page)
    {

        $this->CI->email->set_mailtype('html');
        $this->CI->email->from($from[0], $from[1]);
        $this->CI->email->to($to);
        $this->CI->email->subject($subject);
        $body = $page;
        $this->CI->email->message($body);
        $send = $this->CI->email->send();
        if ($send) {
            return true;
        } else {
            return false;
        }
    }*/

    function send($text, $to, $subject){ 
     require_once 'application/swiftmailer/swift_required.php'; 
     
            include_once ('email_template.php');
     //Create the Transport 
     $transporter = Swift_SmtpTransport::newInstance('mail.measurementsystems.co.ke', 25)
    ->setUsername('system@measurementsystems.co.ke')
    ->setPassword('kasarani2016'); 
     
     $mailer = Swift_Mailer::newInstance($transport); 

     //Create a message 
     $message = Swift_Message::newInstance($subject) 
     ->setFrom(array('info@measurementsystems.co.ke' => 'Measurement Systems Ltd')) 
     ->setTo($to) ->setBody($content_start.$text.$content_end, 'text/html'); 

     //Send the message 
     $result = $mailer->send($message);
 }

 function sendPdf($text, $to, $subject, $html) {

     require_once 'application/swiftmailer/swift_required.php';      
    //generate the pdf
        $filename = $subject;
        require_once("system/helpers/dompdf/autoload.inc.php");
        $dompdf = new Dompdf();
        $dompdf->load_html($html);
        $dompdf->render();
        $output = $dompdf->output(); 

      //email client           
            include_once ('email_template.php');

    $transporter = Swift_SmtpTransport::newInstance('mail.measurementsystems.co.ke', 25)
    ->setUsername('system@measurementsystems.co.ke')
    ->setPassword('kasarani2016');
    
      $attachment = Swift_Attachment::newInstance($output, $subject.".pdf", 'application/pdf');
      $mailer = Swift_Mailer::newInstance($transporter);

      $message = Swift_Message::newInstance();
      $message->setSubject($subject);
      $message->setFrom(array('info@measurementsystems.co.ke' => 'Measurement Systems Ltd'));
      $message->setTo(array($to));
      $message->setBody($content_start.$text.$content_end, 'text/html');
      $message->attach($attachment);

      $result = $mailer->send($message);
}
}
