<?php
    // Define os cabeçalhos para permitir requisições de outras origens (CORS) e JSON
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Content-Type: application/json");

    // 1. Inclui o arquivo de conexão com o banco de dados (ex: $con)
    include "../conecta.php"; // Ajuste o caminho se necessário (duas pastas acima)

    // 2. Recebe o corpo da requisição POST (o JSON enviado pelo React)
    $dados = json_decode(file_get_contents("php://input"), true);

    // 3. Pega as variáveis enviadas do React
    // Os nomes das chaves devem ser EXATAMENTE os mesmos enviados no body do fetch
    $nota          = $dados["nota"]          ?? null;
    $opiniao       = $dados["opiniao"]       ?? null;
    $comentario    = $dados["comentario"]    ?? null;
    $id_obras      = $dados["id_obras"]      ?? null;
    $id_usuario    = $dados["id_usuarios"]   ?? null; // Atenção: React envia "id_usuarios"

    // 4. Validação dos dados essenciais
    if ($nota === null || $opiniao === null || $id_obras === null || $id_usuario === null) {
        http_response_code(400); // Bad Request
        echo json_encode([
            "status" => "erro",
            "mensagem" => "Dados incompletos: Nota, opinião, ID da obra ou ID do usuário ausentes."
        ]);
        exit;
    }

    // 5. Verifica se já existe uma avaliação do usuário para essa obra (OPCIONAL)
    // Isso evita que um usuário cadastre a mesma avaliação mais de uma vez.
    $check_sql = $con->prepare("
        SELECT id_avaliacao FROM avaliacao_rapida 
        WHERE id_usuario = ? AND id_obras = ?
    ");
    $check_sql->bind_param("ii", $id_usuario, $id_obras);
    $check_sql->execute();
    $check_sql->store_result();

    if ($check_sql->num_rows > 0) {
        echo json_encode([
            "status" => "erro",
            "mensagem" => "Você já avaliou esta obra. Edite a avaliação existente."
        ]);
        exit;
    }
    $check_sql->close();


    // 6. Prepara o INSERT na tabela `avaliacao_rapida`
    // Usamos instruções preparadas para prevenir SQL Injection.
    $sql = $con->prepare("
        INSERT INTO avaliacao_rapida 
        (nota, opiniao, comentario, id_usuario, id_obras, data_avaliacao)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");

    /* 7. Liga os parâmetros com as variáveis
     * Tipos:
     * d: double (para a nota, que é 0.5, 1.0, etc.)
     * s: string (para opinião e comentário)
     * i: integer (para id_usuario e id_obras)
     */
    $sql->bind_param("dssii", $nota, $opiniao, $comentario, $id_usuario, $id_obras);

    // 8. Execução e Resposta
    if ($sql->execute()) {
        echo json_encode([
            "status" => "ok",
            "mensagem" => "Avaliação publicada com sucesso!"
        ]);
    } else {
        // Loga o erro do MySQL para debug
        error_log("Erro no MySQL: " . $sql->error);
        
        http_response_code(500); // Internal Server Error
        echo json_encode([
            "status" => "erro",
            "mensagem" => "Erro ao salvar avaliação. Detalhe: " . $sql->error
        ]);
    }

    $sql->close();
    $con->close();
?>