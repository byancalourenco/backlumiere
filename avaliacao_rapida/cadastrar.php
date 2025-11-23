<?php
// permissões
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");

// conexão com o banco
include "../conecta.php";

// captura os dados JSON
$dados = json_decode(file_get_contents("php://input"), true);

// log para depuração
error_log("Dados recebidos: " . json_encode($dados));

// validação básica (comentário pode ser vazio)
if (
    !isset($dados["nota"]) || // Nota pode ser 0, mas deve estar presente
    empty($dados["opiniao"]) || // A opinião deve ser uma string não vazia
    empty($dados["id_usuario"]) || 
    empty($dados["id_obras"])
) {
    echo json_encode(["status" => "erro", "mensagem" => "Dados incompletos."]);
    exit;
}

// variáveis
$nota = (float)$dados["nota"]; // nota pode ser decimal
$comentario = isset($dados["comentario"]) ? $dados["comentario"] : "";
$opiniao = $dados["opiniao"];
$id_usuario = (int)$dados["id_usuario"];
$id_obras = (int)$dados["id_obras"];

// prepara SQL
$sql = $con->prepare("
    INSERT INTO avaliacao_rapida 
    (nota, comentario, opiniao, data_avaliacao_rap, id_usuario, id_obras)
    VALUES (?, ?, ?, NOW(), ?, ?)
");

// verifica se a preparação falhou
if (!$sql) {
    echo json_encode(["status" => "erro", "mensagem" => "Erro na preparação da query."]);
    exit;
}

// vincula os parâmetros
$sql->bind_param("dssii", $nota, $comentario, $opiniao, $id_usuario, $id_obras);

// executa e responde
if ($sql->execute()) {
    echo json_encode(["status" => "ok", "mensagem" => "Avaliação salva com sucesso."]);
} else {
    echo json_encode(["status" => "erro", "mensagem" => "Erro ao salvar avaliação."]);
}
?>