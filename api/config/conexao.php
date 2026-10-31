<?php
$host = 'host';
$banco = 'banco';
$usuario = 'banco';
$senha = 'senha';

try{
    $pdo = new PDO(
        "mysql:host=$host;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );
} catch (PDOException $e){
    die(
        json_encode([
            'status' => 'erro',
            'mensagem' => 'falha na conexão com o banco.'
        ])
    );
}