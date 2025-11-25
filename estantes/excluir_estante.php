<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type");

    $dados = json_decode(file_get_contents("php://input"), true);

    if (!isset($dados["id"])) {
        echo json_encode(["sucesso" => false, "erro" => "ID da estante não enviado."]);
        exit;
    }

    $id = intval($dados["id"]);

    $mysqli = new mysqli("localhost", "root", "", "lumiere");

    if ($mysqli->connect_error) {
        echo json_encode(["sucesso" => false, "erro" => "Erro na conexão com o banco."]);
        exit;
    }

    $stmt1 = $mysqli->prepare("DELETE FROM estante_obras WHERE id_estantes = ?");
    $stmt1->bind_param("i", $id);
    $stmt1->execute();

    $stmt2 = $mysqli->prepare("DELETE FROM estantes WHERE id_estantes = ?");
    $stmt2->bind_param("i", $id);

    if ($stmt2->execute()) {
        echo json_encode(["sucesso" => true]);
    } else {
        echo json_encode(["sucesso" => false, "erro" => "Erro ao excluir a estante."]);
    }
?>
