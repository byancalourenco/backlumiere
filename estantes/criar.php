<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

include_once("../conecta.php");

$dados = json_decode(file_get_contents("php://input"), true);

$nome = $dados["nome"] ?? null;
$descricao = $dados["descricao"] ?? null;

if (!$nome) {
    echo json_encode(["sucesso" => false, "erro" => "Nome da estante é obrigatório"]);
    exit;
}

$sql = "INSERT INTO estantes (nome_estante, descricao) VALUES (?, ?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("ss", $nome, $descricao);

if ($stmt->execute()) {
    echo json_encode([
        "sucesso" => true,
        "id_estantes" => $stmt->insert_id
    ]);
} else {
    echo json_encode(["sucesso" => false, "erro" => $stmt->error]);
}

$stmt->close();
$con->close();
