<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

// Conexão
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "lumiere";

$con = new mysqli($servidor, $usuario, $senha, $banco);
if ($con->connect_error) {
    die(json_encode(["sucesso" => false, "mensagem" => "Falha na conexão: " . $con->connect_error]));
}
$con->set_charset("utf8");

// Recebe JSON
$dados = json_decode(file_get_contents("php://input"), true);
if (!isset($dados['id_obras'])) {
    echo json_encode(["sucesso" => false, "mensagem" => "ID da obra não informado."]);
    exit;
}

$id_obras = intval($dados['id_obras']);

// Primeiro, excluir avaliações detalhadas
$con->query("DELETE FROM criterios WHERE id_avaliacao_det IN (SELECT id_avaliacao_det FROM avaliacao_detalhada WHERE id_obras = $id_obras)");
$con->query("DELETE FROM avaliacao_detalhada WHERE id_obras = $id_obras");

// Excluir avaliações rápidas
$con->query("DELETE FROM avaliacao_rapida WHERE id_obras = $id_obras");

// Excluir da estante_obras
$con->query("DELETE FROM estante_obras WHERE id_obras = $id_obras");

// Excluir temporadas (para séries)
$con->query("DELETE FROM temporadas WHERE id_obras = $id_obras");

// Finalmente, excluir a obra
if ($con->query("DELETE FROM obras WHERE id_obras = $id_obras")) {
    echo json_encode(["sucesso" => true, "mensagem" => "Obra excluída com sucesso."]);
} else {
    echo json_encode(["sucesso" => false, "mensagem" => "Erro ao excluir obra: " . $con->error]);
}
?>
