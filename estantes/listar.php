<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");

    // conexao uou
    include "../conecta.php";

    // buscar todas as estantes queridas
    $sql = "SELECT * FROM estantes";
    $result = $con->query($sql);

    $estantes = [];

    while ($row = $result->fetch_assoc()) {
        $id_estante = $row["id_estantes"];

        // busca as obras relacionadas com as estantes
        $sql2 = "SELECT o.id_obras, o.titulo, o.tipo, o.capa
                FROM estante_obras eo
                INNER JOIN obras o ON o.id_obras = eo.id_obras
                WHERE eo.id_estantes = $id_estante";

        $result2 = $con->query($sql2);

        $obras = [];
        while ($obra = $result2->fetch_assoc()) {
            $obras[] = $obra;
        }

        // montar estrutura final
        $row["obras"] = $obras;
        $estantes[] = $row;
    }

    echo json_encode($estantes);

    $con->close();
?>
