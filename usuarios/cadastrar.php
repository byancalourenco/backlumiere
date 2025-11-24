<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Content-Type: application/json");

    include "../conecta.php";

    $dados = json_decode(file_get_contents("php://input"), true);

    $nota       = $dados["nota"]       ?? null;
    $opiniao    = $dados["opiniao"]    ?? null;
    $comentario = $dados["comentario"] ?? "";
    $id_usuario = $dados["id_usuario"] ?? null;
    $id_obras   = $dados["id_obras"]   ?? null;

    // 🛑 Validações (igual React valida)
    if ($nota === null || $opiniao === null || $id_usuario === null || $id_obras === null) {
        echo json_encode([
            "status" => "erro",
            "mensagem" => "Dados incompletos para cadastrar avaliação."
        ]);
        exit;
    }

    // 💥 Inserção igual ao cadastro de usuário
    $sql = $con->prepare("
        INSERT INTO avaliacao_rapida 
        (nota, opiniao, comentario, id_usuario, id_obras, data_avaliacao)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");

    $sql->bind_param("dssii", $nota, $opiniao, $comentario, $id_usuario, $id_obras);

    if ($sql->execute()) {
        echo json_encode([
            "status" => "ok",
            "mensagem" => "Avaliação cadastrada com sucesso!"
        ]);
    } else {
        echo json_encode([
            "status" => "erro",
            "mensagem" => "Erro ao salvar avaliação."
        ]);
    }
?>
