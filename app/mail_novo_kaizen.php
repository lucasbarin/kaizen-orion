<meta charset="utf-8">
<?php

error_reporting(E_ERROR);

include_once(__DIR__ . "/funcoes8.php");
include_once(__DIR__ . "/conexao8.php");
include_once(__DIR__ . "/configuracoes.php");

//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer-6.7.1/src/Exception.php';
require __DIR__ . '/PHPMailer-6.7.1/src/PHPMailer.php';
require __DIR__ . '/PHPMailer-6.7.1/src/SMTP.php';

$tituloGeral = "Novo Kaizen cadastrado";
$corpo = '';

include(__DIR__ . "/_top_email.php");
$corpo.= '<p>Prezado(a) Gestor,</p>

      <p>Um novo Kaizen foi cadastrado no sistema.</p>

      <p>Segue abaixo seus dados deste Kaizen. </p>
    
      <p>Colaborador: <strong>'.$nome_colaborador.'</strong><br />
      <p>Usuário: <strong>'.$usuario_colaborador.'</strong><br />

      ID Kaizen: <strong>'.$idkaizen.'</strong></p>
	  
	  <p>Acesse o sistema para analisar. </p>
      <p>Atenciosamente,<br />

     Orion Engineered Carbons  </p>';
include(__DIR__ . "/_bottom_email.php");

// Criar instância do PHPMailer
$mail = new PHPMailer(true);

try {
    //Server settings
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();
    $mail->Host       = $host_smtp;
    $mail->SMTPAuth   = true;
    $mail->Username   = $conta_envio;
    $mail->Password   = $senha_email;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = $porta_smtp;
    $mail->CharSet    = 'UTF-8';
    
    if (!$tsl){
        $mail->SMTPAutoTLS = false; 
    }
    
    //Recipients
    $mail->setFrom($conta_envio, $nome_remetente);
    $mail->addAddress($para, "Sistema Kaizen Orion Carbons");
    
    if (!empty($cc)){
        $mail->addCC($cc);
    }
    
    //Content
    $mail->isHTML(true);
    $mail->Subject = $tituloGeral.' - Sistema Kaizen Orion Carbons';
    $mail->Body    = $corpo;
    $mail->AltBody = strip_tags($corpo);
    
    $mail->send();
    $envio = true;
    
} catch (Exception $e) {
    $envio = false;
    error_log("Erro ao enviar email: {$mail->ErrorInfo}");
}

?>
