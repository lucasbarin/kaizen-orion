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

$tituloGeral = "Recuperar Senha";
$corpo = ''; // Inicializa variável

include(__DIR__ . "/_top_email.php");
$corpo.= '<p>Prezado(a)  '.$nome_colaborador.',</p>

      <p>Foi solicitada a redefinição de senha em nosso sistema.</p>

      <p>Segue abaixo seus dados de acesso com sua nova senha. Por questões de segurança, faça a alteração desta senha assim que realizar o primeiro acesso.</p>

      <p>Usuário: <strong>'.$usuario_colaborador.'</strong><br />

      Senha: <strong>'.$novasenha.'</strong></p>

      <p>Atenciosamente,<br />

     Orion Engineered Carbons  </p>';
include(__DIR__ . "/_bottom_email.php");

// Criar instância do PHPMailer
$mail = new PHPMailer(true);

try {
    //Server settings
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;  // Debug detalhado (descomente se necessário)
    $mail->isSMTP();
    $mail->Host       = $host_smtp;
    $mail->SMTPAuth   = true;
    $mail->Username   = $conta_envio;
    $mail->Password   = $senha_email;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  // SSL implícito
    $mail->Port       = $porta_smtp;
    $mail->CharSet    = 'UTF-8';
    
    if (!$tsl){
        $mail->SMTPAutoTLS = false; 
    }
    
    //Recipients
    $mail->setFrom($conta_envio, $nome_remetente);
    $mail->addAddress($para, $nome_colaborador);
    
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
