<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "lumiere";

$con = new mysqli($servidor, $usuario, $senha, $banco);

if ($con->connect_error) {
    die(json_encode(["erro" => "Falha na conexão: " . $con->connect_error]));
}

$id_usuario = isset($_GET['id_usuario']) ? intval($_GET['id_usuario']) : 0;

if ($id_usuario <= 0) {
    echo json_encode([]);
    exit;
}

$sql = "
SELECT a.*, o.titulo AS nome_obra
FROM avaliacao_rapida a
JOIN obras o ON a.id_obras = o.id_obras
WHERE a.id_usuario = $id_usuario
ORDER BY a.data_avaliacao_rap DESC
";

$result = $con->query($sql);

$avaliacoes = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $avaliacoes[] = $row;
    }
}

echo json_encode($avaliacoes);
$con->close();
