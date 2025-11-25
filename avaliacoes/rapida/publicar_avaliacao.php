<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Content-Type: application/json");

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }

    include "../../conecta.php";

    $input = file_get_contents("php://input");
    file_put_contents("debug.txt", $input); 
    $dados = json_decode($input, true);

    $nota = $dados["nota"] ?? null;
    $opiniao = $dados["opiniao"] ?? null;
    $comentario = $dados["comentario"] ?? null;
    $id_obras = $dados["id_obras"] ?? null;
    $id_usuario = $dados["id_usuarios"] ?? null;

    if ($nota === null || $opiniao === null || $id_obras === null || $id_usuario === null) {
        http_response_code(400);
        echo json_encode([
            "status" => "erro",
            "mensagem" => "Dados incompletos."
        ]);
        exit;
    }

    $check = $con->prepare("SELECT 1 FROM avaliacao_rapida WHERE id_usuario = ? AND id_obras = ?");
    $check->bind_param("ii", $id_usuario, $id_obras);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo json_encode([
            "status" => "erro",
            "mensagem" => "Você já avaliou esta obra."
        ]);
        exit;
    }

    $check->close();

    $sql = $con->prepare("
        INSERT INTO avaliacao_rapida 
        (nota, opiniao, comentario, id_usuario, id_obras, data_avaliacao_rap)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");

    $sql->bind_param("dssii", $nota, $opiniao, $comentario, $id_usuario, $id_obras);

    if ($sql->execute()) {
        echo json_encode([
            "status" => "ok",
            "mensagem" => "Avaliação publicada!"
        ]);
    } else {
        echo json_encode([
            "status" => "erro",
            "mensagem" => "Erro MySQL: " . $sql->error
        ]);
    }

    $sql->close();
    $con->close();
?>
