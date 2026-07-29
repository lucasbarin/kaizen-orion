<meta charset="utf-8">
<?php

error_reporting(E_ERROR);

include ("configuracoes8.php");
include ("funcoes8.php");

$assunto = "Contato do site";

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-6.7.1/src/Exception.php';
require 'PHPMailer-6.7.1/src/PHPMailer.php';
require 'PHPMailer-6.7.1/src/SMTP.php';

if (isset($_POST)){
	// $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=YOUR_SITE_KEY&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
		switch($_POST['idioma']){
		
		case 'Inglês':
			$volta = "../index.php";
			$msg1 = "Message sent successfully.";
			$msg2 = "Error while sending the message. Try again later.";
			$msg3 = "Error while collecting form data. E-mail not sent.";
			$msg4 = "O captcha não pode ser verificado. Tente novamente.";
			break;
			
		case 'Espanhol':
			$volta = "../index.php";
			$msg1 = "Mensaje enviado con éxito.";
			$msg2 = "Error al enviar el mensaje. Inténtalo más tarde.";
			$msg3 = "Error al recuperar datos desde el formulario. Correo electrónico no enviado.";
			$msg4 = "O captcha não pode ser verificado. Tente novamente.";
			break;
			
		default:
			$volta = "../index.php";
			$msg1 = "Mensagem enviada com sucesso.";
			$msg2 = "Erro ao enviar o mensagem. Tente novamente mais tarde.";
			$msg3 = "Erro ao coletar dados do formulário. E-mail não enviado.";
			$msg4 = "O captcha não pode ser verificado. Tente novamente.";
			break;
			
		
		
	}
	

	$captcha = $_POST['g-recaptcha-response'];
	$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LeGBAskAAAAAFFVjSxEi_xaWykgsY_sfZ93WQUp&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
	$obj = json_decode($response);

	if(!$obj->success == true)
	{
		?>
		<script type="text/javascript">
			alert("<? echo $msg4 ?>");
			history.back();
		</script>
		<?
		die;
	}

	
	//Recebdno formulário, compondo o corpo 
	$corpo = "<font face='Trebuchet MS'>
	<h3>Contato - Site</h3>
	<p>Data: ".date("d/m/Y H:i")."h</p>
	";
	// pega todos os posts e monta o corpo
	if(strtoupper($_SERVER["REQUEST_METHOD"]) == "POST") {
		foreach($_POST as $key => $valor){
			if ($valor == ".X.X.X."){
				$corpo.= "<br />"; 
				$corpo.= "<h3>". strtoupper($key)."</h3>";
				$corpo.= "<br />";
			} else {
				if (!empty($valor) && $key != 'bt-enviar' && $key != 'enviar' && $key != 'emailPara' && $key != 'emailCc' && $key != 'g-recaptcha-response'){ 
				$campo = $key;
				$corpo.= "<strong>". strtoupper($campo).": </strong>";
				$corpo.= $valor."<br />";
				}
			}
		}
	}
	$corpo.= "</font>";
	
	if (empty($_POST['emailPara'])){
		$para = $paraPadao;
	} else {
		$para = $_POST['emailPara'];
	}
	
	$cc = $_POST['emailCc'];
	
	
	//Create an instance; passing `true` enables exceptions
	$mail = new PHPMailer(true);

	try {
		//Server settings
		//$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
		$mail->isSMTP();                                            //Send using SMTP
		$mail->Host       = $host_smtp;                     //Set the SMTP server to send through
		$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
		$mail->Username   = $conta_envio;                     //SMTP username
		$mail->Password   = $senha_email;                               //SMTP password
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
		$mail->Port       = $porta_smtp;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

		//Recipients
		$mail->setFrom($conta_envio, $nome_remetente);
		$mail->addAddress($para);     //Add a recipient
		
		if (!$tsl){
			$mail->SMTPAutoTLS  = false; 
		}
		
		/*$mail->addAddress('ellen@example.com');               //Name is optional
		$mail->addReplyTo('info@example.com', 'Information');
		$mail->addCC('cc@example.com');
		$mail->addBCC('bcc@example.com');

		//Attachments
		$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
		$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
	*/

		//Content
		$mail->isHTML(true);                                  //Set email format to HTML
		$mail->Subject = $assunto;
		$mail->Body    = $corpo;
		$mail->AltBody = resumir3($corpo, 200);

		$mail->send();
		// sucesso
		?><script type="text/javascript">
				alert("<? echo $msg1 ?>");
				window.location.href = "<? echo $volta ?>";
		</script><?	die;
		
		
	} catch (Exception $e) {
		//erro
		?>
		<script type="text/javascript">
			alert("<? echo $msg2 . $mail->ErrorInfo ?>");
			window.location.href = "<? echo $volta ?>";
		</script>
		<?	die;
	
	}


	
} else { // fim if isset post
?><script type="text/javascript">
	alert("<? echo $msg3 ?>");
	window.location.href = "<? echo $volta ?>";
</script><?		die;
}
?>