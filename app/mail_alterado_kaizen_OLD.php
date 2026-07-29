<meta charset="utf-8">
<?php



	

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
include_once($root.$diretorio."app/funcoes8.php");
include_once($root.$diretorio."app/conexao8.php");
include_once($root.$diretorio."app/configuracoes.php");
	
$tituloGeral = "Kaizen alterado pelo colaborador";



include($root.$diretorio."app/_top_email.php");
$corpo.= '<p>Prezado(a) Gestor,</p>

      <p>Um Kaizen foi editado/corrigido no sistema.</p>

      <p>Segue abaixo seus dados deste Kaizen. </p>
    
      <p>Colaborador: <strong>'.$nome_colaborador.'</strong><br />
      <p>Usuário: <strong>'.$usuario_colaborador.'</strong><br />

      ID Kaizen: <strong>'.$idkaizen.'</strong></p>
	  
	  <p>Acesse o sistema para analisar. </p>
      <p>Atenciosamente,<br />

     Orion Engineered Carbons  </p>';
include($root.$diretorio."app/_bottom_email.php");

	
	
//// PHP Mailer
	require_once($root.$diretorio.'app/PHPMailer_v5.1/class.phpmailer.php');
/*	if (empty($_POST['emailPara'])){
		$para = 'contato@dominio.com.br';
	} else {
		$para = $_POST['emailPara'];
	}*/ 
	
	// $cc = $_POST['emailCc'];
	
	$mailer = new PHPMailer();
	$mailer->IsSMTP();
	$mailer->SMTPDebug = 1;
	$mailer->Port = $porta_smtp; //Indica a porta de conexão para a saída de e-mails
	$mailer->Host = $host_smtp;
	$mailer->SMTPAuth = true; //define se haverá ou não autenticação no SMTP
	$mailer->SMTPSecure = "ssl";
	$mailer->Username = $conta_envio; //Informe o e-mai o completo
	$mailer->Password = $senha_email; //Senha da caixa postal
	$mailer->CharSet =  'utf-8'; //Sets the CharSet of the message.
	$mailer->IsHTML(true); // set email format to HTML
	$mailer->FromName = 'Sistema Kaizen Orion Carbons '; //Nome que será exibido para o destinatário
	$mailer->From = $conta_envio; //Obrigatório ser a mesma caixa postal indicada em "username"
	$mailer->AddAddress($para, "Sistema Kaizen Orion Carbons "); //Destinatários
	if (!empty($cc)){
		$mailer->AddCC($cc, 'Sistema Kaizen Orion Carbons '); 
	}
	
	// $mailer->AddReplyTo($_POST['email'], $_POST['nome']); 
	$mailer->Subject = $tituloGeral.' - Sistema Kaizen Orion Carbons';
	$mailer->Body = $corpo;
	
	if(!$mailer->Send()){
		
		$envio = false;
		
		
	} else {
		
		$envio = true;
		
	}

////
	

?>