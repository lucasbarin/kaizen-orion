<?php
/**
 * Template base para migração de emails PHPMailer 5.1 → 6.7.1 (PHP 8)
 * 
 * INSTRUÇÕES:
 * 1. Copie este template
 * 2. Substitua [CONTEUDO_EMAIL] pelo conteúdo do corpo do email
 * 3. Defina $tituloGeral com o assunto do email
 * 4. Defina variável $para com o destinatário
 * 5. Ajuste variáveis específicas do email (nome_colaborador, etc)
 */

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

// ===== CONFIGURAR VARIÁVEIS DO EMAIL =====
$tituloGeral = "Assunto do Email";
$corpo = ''; // Inicializa variável

include(__DIR__ . "/_top_email.php");
$corpo.= '[CONTEUDO_EMAIL]';
include(__DIR__ . "/_bottom_email.php");

// ===== ENVIO COM PHPMAILER 6.7.1 =====
$mail = new PHPMailer(true);

try {
    //Server settings
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;  // Descomentar para debug
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
    $mail->addAddress($para, $nome_destinatario);
    
    // CC opcional
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
