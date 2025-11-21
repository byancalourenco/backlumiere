<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Content-Type: application/json");

    include "../conecta.php";

    $dados = json_decode(file_get_contents("php://input"), true);

    $email = $dados["emailcadastrado"];
    $nova_senha = $dados["senhanova"];

    $sql = $con->prepare("UPDATE usuarios SET senha = ? WHERE email = ?");
    $sql->bind_param("ss", $nova_senha, $email);

    if ($sql->execute()) {
        echo json_encode(["status" => "ok", "mensagem" => "Senha atualizada com sucesso!"]);
    } else {
        echo json_encode(["status" => "erro", "mensagem" => "Erro ao atualizar senha."]);
    }
?>
