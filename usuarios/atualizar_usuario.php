<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Content-Type: application/json");

    include "../conecta.php";

    $dados = json_decode(file_get_contents("php://input"), true);
    $id = isset($dados['id']) ? intval($dados['id']) : 0;
    $nome = isset($dados['nome']) ? $dados['nome'] : '';

    if ($id <= 0 || empty($nome)) {
        echo json_encode(["status" => "erro", "mensagem" => "Dados inválidos."]);
        exit;
    }

    $sql = $con->prepare("UPDATE usuarios SET nome = ? WHERE id_usuario = ?");
    $sql->bind_param("si", $nome, $id);

    if ($sql->execute()) {
        echo json_encode(["status" => "ok", "mensagem" => "Nome atualizado com sucesso."]);
    } else {
        echo json_encode(["status" => "erro", "mensagem" => "Erro ao atualizar."]);
    }

?>