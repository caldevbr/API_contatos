<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    echo json_encode([
        'status' => 'erro',
        'mensagem' => 'Utilize POST'
    ]);

    exit;
}

$origem   = $_POST['origem'] ?? '';
$nome     = $_POST['nome'] ?? '';
$email    = $_POST['email'] ?? '';
$telefone = $_POST['telefone'] ?? '';
$mensagem = $_POST['mensagem'] ?? '';

if (
    empty($origem) ||
    empty($nome) ||
    empty($email) ||
    empty($telefone) ||
    empty($mensagem)
) {

    echo json_encode([
        'status' => 'erro',
        'mensagem' => 'Campos obrigatórios não preenchidos'
    ]);

    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    echo json_encode([
        'status' => 'erro',
        'mensagem' => 'E-mail inválido'
    ]);

    exit;
}

$telefoneLimpo = preg_replace('/\D/', '', $telefone);
if (
    strlen($telefoneLimpo) < 10 ||
    strlen($telefoneLimpo) > 11
) {

    echo json_encode([
        'status' => 'erro',
        'mensagem' => 'Telefone inválido'
    ]);

    exit;
}

require_once __DIR__ . '/config/conexao.php';
$sql = "
INSERT INTO api_contatos
(
    origem,
    nome,
    email,
    telefone,
    mensagem,
    data_envio
)
VALUES
(
    :origem,
    :nome,
    :email,
    :telefone,
    :mensagem,
    NOW()
)
";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':origem'   => $origem,
        ':nome'     => $nome,
        ':email'    => $email,
        ':telefone' => $telefone,
        ':mensagem' => $mensagem
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'erro',
        'mensagem' => $e->getMessage()
    ]);
    exit;
}

try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'host';
    $mail->SMTPAuth = true;
    $mail->Username = 'email';
    $mail->Password = 'senha';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 000;
    $mail->CharSet = 'UTF-8';
    $mail->setFrom(
        'email',
        'APICALDEVContatos'
    );

    $mail->addAddress(
        'email'
    );

    $mail->Subject =
        'Novo contato recebido';
    $mail->Body =
"Origem: $origem
Nome: $nome
Email: $email
Telefone: $telefone
Mensagem:
$mensagem";
    $mail->send();
} catch (Exception $e) {
}

echo json_encode([
    'status' => 'sucesso',
    'mensagem' => 'Contato recebido com sucesso'
]);