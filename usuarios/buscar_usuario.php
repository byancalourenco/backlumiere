<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Content-Type: application/json");

    include "../conecta.php";

    $dados = json_decode(file_get_contents("php://input"), true);

    $email = $dados["email"];

    $sql = $con->prepare("SELECT id_usuario, nome, email, tipo_usuario, data_cadastro 
                        FROM usuarios WHERE email = ?");
    $sql->bind_param("s", $email);
    $sql->execute();
    $res = $sql->get_result();

    if ($res->num_rows === 0) {
        echo json_encode(["status" => "erro", "mensagem" => "Usuário não encontrado"]);
        exit;
    }

    $user = $res->fetch_assoc();

    echo json_encode([
        "status" => "ok",
        "usuario" => $user
    ]);
?>
