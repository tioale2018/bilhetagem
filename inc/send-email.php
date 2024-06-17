<?php
// Inclui os arquivos necessários do PHPMailer
require '../libs/PHPMailer/src/Exception.php';
require '../libs/PHPMailer/src/PHPMailer.php';
require '../libs/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Configurações do servidor SMTP
    $mail->isSMTP();
    $mail->Host = 'mail.w3brand.com.br';  // Servidor SMTP
    $mail->SMTPAuth = true;
    $mail->Username = 'bilhetagem@w3brand.com.br';  // Usuário SMTP
    $mail->Password = 'Temp@2024';  // Senha SMTP
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 465;  // Porta TCP para SSL/TLS

    // Destinatário e remetente
    $mail->setFrom('bilhetagem@w3brand.com.br', 'Sistema de bilhetagem');
    $mail->addAddress('ale.tpd@gmail.com', 'Alessandro');

    // Conteúdo do email
    $mail->isHTML(true);
    $mail->Subject = 'Cadastro no sistema';
    $mail->Body    = 'Conteúdo do email em <b>HTML</b>';
    $mail->AltBody = 'Conteúdo do email em texto plano';

    $mail->send();
    echo 'Email enviado com sucesso';
} catch (Exception $e) {
    echo "Erro ao enviar email: {$mail->ErrorInfo}";
}


?>