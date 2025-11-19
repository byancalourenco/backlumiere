<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Content-Type: application/json");

    include "../conecta.php";

    // Recebe os dados em JSON
    $dados = json_decode(file_get_contents("php://input"), true);

    $nome = $dados["nome"];
    $email = $dados["email"];
    $senha = $dados["senha"];
    $tipo = $dados["tipo_usuario"];

    $sql = $con->prepare("INSERT INTO usuarios (nome, email, senha, tipo_usuario) VALUES (?, ?, ?, ?)");
    $sql->bind_param("ssss", $nome, $email, $senha, $tipo);

    if ($sql->execute()) {
        echo json_encode(["status" => "ok", "mensagem" => "Usuário cadastrado com sucesso!"]);
    } else {
        echo json_encode(["status" => "erro", "mensagem" => "Erro ao cadastrar usuário"]);
    }
?>
