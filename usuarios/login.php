<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Content-Type: application/json");

    include "../conecta.php";

    $dados = json_decode(file_get_contents("php://input"), true);

    $email = $dados["email"];
    $senha = $dados["senha"];

    $sql = $con->prepare("SELECT id_usuario, nome, email, senha, tipo_usuario, data_cadastro 
                        FROM usuarios WHERE email = ?");
    $sql->bind_param("s", $email);
    $sql->execute();
    $res = $sql->get_result();

    if ($res->num_rows === 0) {
        echo json_encode(["status" => "erro", "mensagem" => "Email não encontrado."]);
        exit;
    }

    $usuario = $res->fetch_assoc();

    // valida senha (SEM HASH, igual seu sistema atual)
    if ($senha !== $usuario["senha"]) {
        echo json_encode(["status" => "erro", "mensagem" => "Senha incorreta."]);
        exit;
    }

    echo json_encode([
        "status" => "ok",
        "mensagem" => "Login realizado com sucesso!",
        "usuario" => [
            "id" => $usuario["id_usuario"],
            "nome" => $usuario["nome"],
            "email" => $usuario["email"],
            "tipo_usuario" => $usuario["tipo_usuario"],
            "data_cadastro" => $usuario["data_cadastro"]
        ]
    ]);
?>
