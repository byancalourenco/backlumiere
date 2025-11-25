<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Access-Control-Allow-Methods: POST");
    header("Content-Type: application/json");

    include_once("../conecta.php");

    $dados = json_decode(file_get_contents("php://input"), true);

    $id_estante = $dados["id_estantes"] ?? null;
    $obras = $dados["obras"] ?? [];

    if (!$id_estante) {
        echo json_encode(["sucesso" => false, "erro" => "ID da estante é obrigatório"]);
        exit;
    }

    if (count($obras) == 0) {
        echo json_encode(["sucesso" => false, "erro" => "Nenhuma obra selecionada"]);
        exit;
    }

    $sql = "INSERT INTO estante_obras (id_estantes, id_obras) VALUES (?, ?)";
    $stmt = $con->prepare($sql);

    foreach ($obras as $obraID) {
        $stmt->bind_param("ii", $id_estante, $obraID);
        $stmt->execute();
    }

    echo json_encode(["sucesso" => true, "mensagem" => "Obras adicionadas com sucesso"]);

    $stmt->close();
    $con->close();
?>
