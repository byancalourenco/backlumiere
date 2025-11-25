<?php

        include "../../conecta.php"; 

        $id_obras = $_GET['id_obras'] ?? 0;

        $sql = "SELECT ar.*, u.nome as usuario FROM avaliacao_rapida ar
                LEFT JOIN usuarios u ON u.id_usuario = ar.id_usuario
                WHERE ar.id_obras = ?";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id_obras);
        $stmt->execute();

        $result = $stmt->get_result();
        $avaliacoes = $result->fetch_all(MYSQLI_ASSOC);
        
        echo json_encode($avaliacoes);
?>
