<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");

    // conexao uou
    include "../conecta.php";


    if ($con->connect_error) {
        echo json_encode(["success" => false, "mensagem" => "Erro de conexão: " . $con->connect_error]);
        exit;
    }

    // recebe dados do front
    $dados = json_decode(file_get_contents("php://input"), true);

    if (!isset($dados['id']) || empty($dados['id'])) {
        echo json_encode(["success" => false, "mensagem" => "ID da obra não fornecido"]);
        exit;
    }

    $id = intval($dados['id']);

    // deletar a obra do banco
    $sql = "DELETE FROM obras WHERE id_obras = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "mensagem" => "Obra excluída com sucesso"]);
    } else {
        echo json_encode(["success" => false, "mensagem" => "Erro ao excluir obra"]);
    }

    $stmt->close();
    $con->close();
?>
