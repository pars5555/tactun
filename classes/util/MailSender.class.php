<?php
require_once(CLASSES_PATH."/lib/phpmailer/class.phpmailer.php");
require_once(CLASSES_PATH."/util/ImSmarty.class.php");

class MailSender{

	private $config;

	public function __construct($config){
		$this->config = $config;
	
	}
	
	public function send($from, $recipients, $subject, $template, $params = array(), $separate = false){

//--proccessing the message
		$smarty = new ImSmarty($this->config["static"]);
		$smarty->assign("ns", $params);
		$message = $smarty->fetch($template);
		
		
		
		
		$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

		$mail->IsSMTP(); // telling the class to use SMTP
		
		try {
			$mail->XMailer       = "imusic.am (http://imusic.am)"; // SMTP server
		//  $mail->Host       = "mail.naghashyan.com"; // SMTP server
		  $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
		  $mail->SMTPAuth   = true;                  // enable SMTP authentication
		  $mail->SMTPSecure = "tls";                 // sets the prefix to the servier
		  $mail->Host       = "naghashyan.com";      // sets GMAIL as the SMTP server
		  $mail->Port       = 25;                   // set the SMTP port for the GMAIL server
		  $mail->Username   = "info@imusic.am";  // GMAIL username
		  $mail->Password   = "Test123";            // GMAIL password
		  $mail->AddAddress($recipients[0]);
		  $mail->SetFrom('support@imusic.am', 'imusic.am Support Team');
		  $mail->Subject = $subject;
		  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
		  $mail->CharSet = 'UTF-8';

		  $mail->MsgHTML($message);
		//  $mail->AddAttachment('images/phpmailer.gif');      // attachment
		 // $mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
		  $mail->Send();
		} catch (phpmailerException $e) {
		  echo $e->errorMessage(); //Pretty error messages from PHPMailer
		} catch (Exception $e) {
		  echo $e->getMessage(); //Boring error messages from anything else!
		}
	

	}
	
	
}
?>