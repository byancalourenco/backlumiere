<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

include "../conecta.php";

// Verifica campos obrigatórios
if (!isset($_FILES['capa'])) {
    echo json_encode(["erro" => true, "mensagem" => "Nenhuma imagem enviada."]);
    exit;
}

$titulo = $_POST["titulo"] ?? "";
$tipo = $_POST["tipo"] ?? "";
$descricao = $_POST["descricao"] ?? "";
$ano = $_POST["ano"] ?? "";
$autor = $_POST["autor"] ?? null;
$editora = $_POST["editora"] ?? null;
$num_temporadas = $_POST["num_temporadas"] ?? null;
$num_episodios = $_POST["num_episodios"] ?? null;
$genero = $_POST["genero"] ?? null;
$classificacao = $_POST["classificacao"] ?? null;
$id_usuario = $_POST["id_usuario"] ?? null;

// Validação básica
if ($titulo === "" || $descricao === "" || $tipo === "" || !$autor) {
    echo json_encode(["erro" => true, "mensagem" => "Preencha todos os campos obrigatórios."]);
    exit;
}
if ($tipo === "Livro" && !$editora) {
    echo json_encode(["erro" => true, "mensagem" => "Preencha a editora do livro."]);
    exit;
}
if ($tipo === "Série" && (!$num_temporadas || !$num_episodios)) {
    echo json_encode(["erro" => true, "mensagem" => "Preencha número de temporadas e episódios da série."]);
    exit;
}

// Upload da imagem
$arquivo = $_FILES['capa'];
$nomeArquivo = uniqid() . "_" . $arquivo['name'];
$caminhoFinal = "../uploads/" . $nomeArquivo;

if (!move_uploaded_file($arquivo['tmp_name'], $caminhoFinal)) {
    echo json_encode(["erro" => true, "mensagem" => "Falha ao salvar imagem."]);
    exit;
}

// inserir na tabela obras
$stmt = $con->prepare("
    INSERT INTO obras 
    (titulo, tipo, descricao, ano_lancamento, autor, editora, capa, data_cadastro, id_usuario) 
    VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?)
");
$stmt->bind_param(
    "sssssssi",
    $titulo,
    $tipo,
    $descricao,
    $ano,
    $autor,
    $editora,
    $nomeArquivo,
    $id_usuario
);

if (!$stmt->execute()) {
    echo json_encode(["erro" => true, "mensagem" => "Erro ao cadastrar obra: " . $stmt->error]);
    exit;
}

$id_obra = $stmt->insert_id;

if ($tipo === "Série") {
    $stmt2 = $con->prepare("INSERT INTO temporadas (numero_temp, numero_eps, id_obras) VALUES (?, ?, ?)");
    $stmt2->bind_param("iii", $num_temporadas, $num_episodios, $id_obra);
    $stmt2->execute();
}

echo json_encode([
    "erro" => false,
    "mensagem" => "Obra cadastrada com sucesso!",
    "caminho_imagem" => $nomeArquivo
]);
?>
