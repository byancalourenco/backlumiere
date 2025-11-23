<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include "../conecta.php";

$dados = json_decode(file_get_contents("php://input"), true);

$id = intval($dados["id"]);
$nome = $con->real_escape_string($dados["nome"]);
$descricao = $con->real_escape_string($dados["descricao"]);
$obras = $dados["obras"]; 

if (!$id) {
    echo json_encode(["erro" => "ID não enviado"]);
    exit;
}

$sql1 = "UPDATE estantes SET nome_estante='$nome', descricao='$descricao' WHERE id_estantes=$id";

if (!$con->query($sql1)) {
    echo json_encode(["erro" => "Erro ao atualizar estante"]);
    exit;
}

$sql2 = "DELETE FROM estante_obras WHERE id_estantes=$id";
$con->query($sql2);

foreach ($obras as $id_obras) {
    $id_obras = intval($id_obras);
    $con->query("INSERT INTO estante_obras (id_estantes, id_obras) VALUES ($id, $id_obras)");
}

echo json_encode(["sucesso" => true, "mensagem" => "Estante atualizada com sucesso!"]);
?>
