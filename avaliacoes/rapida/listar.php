<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");

    include "../../conecta.php";

    $query = "
        SELECT 
            ar.id_avaliacao_rap,
            ar.nota, 
            ar.comentario, 
            ar.opiniao, 
            u.nome AS usuario, 
            o.titulo AS nome
        FROM avaliacao_rapida ar
        JOIN usuarios u ON ar.id_usuario = u.id_usuario
        JOIN obras o ON ar.id_obras = o.id_obras
        ORDER BY ar.data_avaliacao_rap DESC
    ";


    $result = $con->query($query);

    $avaliacoes = [];
    while ($row = $result->fetch_assoc()) {
        $avaliacoes[] = $row;
    }

    echo json_encode($avaliacoes);
?>
