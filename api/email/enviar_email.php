<?php
require_once __DIR__ .'/../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$mail = new PHPMailer(true);
try{
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'user';
    $mail->Password = 'senha';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 000;
    $mail->setFrom(
        'email',
        'email'
    );
    $mail->addAddress(
        'email'
    );
    $mail->Subject = 'Teste SMTP';
    $mail->Body=
    'Teste de envio SMTP da APICALDEVContatos';
    $mail->send();
    echo 'Email enviado com sucesso!';
} catch (Exception $e){
    echo 'Erro: ' . $mail->ErrorInfo;
}