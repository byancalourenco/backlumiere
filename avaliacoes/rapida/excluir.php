<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Content-Type: application/json");

    include "../../conecta.php"; 

    $dados = json_decode(file_get_contents("php://input"), true);

    if (!isset($dados['id_avaliacao_rap'])) { 
        echo json_encode(["status" => "erro", "mensagem" => "ID da avaliação não fornecido."]);
        exit;
    }

    $id_avaliacao_rap = $dados['id_avaliacao_rap']; 

    try {
        $sql = $con->prepare("DELETE FROM avaliacao_rapida WHERE id_avaliacao_rap = ?"); 
        
        $sql->bind_param('s', $id_avaliacao_rap); 
        
        if ($sql->execute()) {
            if ($sql->affected_rows > 0) { 
                echo json_encode(["status" => "ok", "mensagem" => "Avaliação excluída com sucesso!"]);
            } else {
                echo json_encode(["status" => "erro", "mensagem" => "Avaliação não encontrada ou já excluída."]);
            }
        } else {
            echo json_encode(["status" => "erro", "mensagem" => "Erro ao executar a exclusão no banco de dados: " . $con->error]);
        }
    } catch (Exception $e) {
        echo json_encode(["status" => "erro", "mensagem" => "Erro: " . $e->getMessage()]);
    }

    if (isset($con)) {
        $con->close();
    }

?>