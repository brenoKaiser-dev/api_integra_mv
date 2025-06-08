<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

define('CHAVE_SECRETA', 'uma-chave-secreta-de-32-bytes!!');

function criptografar($dados) {
    $json = json_encode($dados);
    $iv = openssl_random_pseudo_bytes(16);
    $cifrado = openssl_encrypt($json, 'aes-256-cbc', CHAVE_SECRETA, OPENSSL_RAW_DATA, $iv);
    return base64_encode($iv . $cifrado);
}

$input = file_get_contents("php://input");
$dados = json_decode($input, true);

if (!isset($dados['usuario'], $dados['atendimento'], $dados['prestador'])) {
    http_response_code(400);
    echo json_encode(["erro" => "Campos obrigatórios ausentes."]);
    exit;
}

$token = criptografar($dados);
$url = "localhost/prd/prd/index.php?token=" . urlencode($token);

echo json_encode(["url" => $url]);