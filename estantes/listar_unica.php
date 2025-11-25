<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");

    include "../conecta.php";

    if (!isset($_GET["id"])) {
        echo json_encode(["erro" => "ID não fornecido"]);
        exit;
    }

    $id = intval($_GET["id"]);

    $sql = "SELECT * FROM estantes WHERE id_estantes = $id LIMIT 1";
    $result = $con->query($sql);

    if ($result->num_rows === 0) {
        echo json_encode(["erro" => "Estante não encontrada"]);
        exit;
    }

    $estante = $result->fetch_assoc();

    $sql2 = "SELECT eo.id AS id_estante_obras, o.id_obras, o.titulo, o.tipo, o.capa
            FROM estante_obras eo
            INNER JOIN obras o ON o.id_obras = eo.id_obras
            WHERE eo.id_estantes = $id";

    $result2 = $con->query($sql2);

    $obras = [];
    while ($obra = $result2->fetch_assoc()) {
        $obras[] = $obra;
    }

    $estante["obras"] = $obras;

    echo json_encode($estante);
    $con->close();
?>
