<meta charset="utf-8">
<?php

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
	$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LfAKyAUAAAAAFpHwo5ioKFvqXSYdQMoJFayd3ik&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
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
	
	

/*	
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
	
*/
	
	
$root = $_SERVER['DOCUMENT_ROOT']."/";
$diretorio = "";
include($root.$diretorio."app/_top_email.php");
$corpo.= '<p><strong>Pedido Nº '.$id_pedido.'</strong></p>
	  <p>Prezado(a)  '.$nome_cliente.',</p>
	  '.$textoNotificacao;
include($root.$diretorio."app/_bottom_email.php");	
	
	
//// PHP Mailer
	require_once('PHPMailer_v5.1/class.phpmailer.php');
	include ("configuracoes.php");
	include ("funcoes8.php");
	include ("conexao8.php");
	if (empty($_POST['emailPara'])){
		$para = 'contato@dominio.com.br';
	} else {
		$para = $_POST['emailPara'];
	}
	
	$cc = $_POST['emailCc'];
	
	$mailer = new PHPMailer();
	$mailer->IsSMTP();
	$mailer->SMTPDebug = 1;
	$mailer->Port = $porta_smtp; //Indica a porta de conexão para a saída de e-mails
	$mailer->Host = $host_smtp;
	$mailer->SMTPAuth = true; //define se haverá ou não autenticação no SMTP
	//$mailer->SMTPSecure = "ssl";
	$mailer->Username = $conta_envio; //Informe o e-mai o completo
	$mailer->Password = $senha_email; //Senha da caixa postal
	$mailer->CharSet =  'utf-8'; //Sets the CharSet of the message.
	$mailer->IsHTML(true); // set email format to HTML
	$mailer->FromName = 'Site '; //Nome que será exibido para o destinatário
	$mailer->From = $conta_envio; //Obrigatório ser a mesma caixa postal indicada em "username"
	$mailer->AddAddress($para, "Site "); //Destinatários
	if (!empty($cc)){
		$mailer->AddCC($cc, 'Site '); 
	}
	
	$mailer->AddReplyTo($_POST['email'], $_POST['nome']); 
	$mailer->Subject = 'Contato - Site';
	$mailer->Body = $corpo;
	
	if(!$mailer->Send()){
		?>
		<script type="text/javascript">
			alert("<? echo $msg2 ?>");
			window.location.href = "<? echo $volta ?>";
		</script>
		<?
			die;
	} else {
		
		$email = todas_minusculas (trata($_POST['email']));

		if (is_email($email)){
			$sql = mysqli_query($con, "SELECT * FROM `emailcontato` WHERE `email_emailcontato` LIKE '".$email."' LIMIT 1");

			if (mysqli_num_rows($sql)< 1){
				$sql = mysqli_query($con, "INSERT INTO emailcontato (email_emailcontato, nome_emailcontato) VALUES ('".$email."', '".$nome."')");
			}
		}

		
		
		?><script type="text/javascript">
				alert("<? echo $msg1 ?>");
				window.location.href = "<? echo $volta ?>";
		</script><?	die;
	}

////
	
} else { // fim if isset post
?><script type="text/javascript">
	alert("<? echo $msg3 ?>");
	window.location.href = "<? echo $volta ?>";
</script><?		die;
}
?>