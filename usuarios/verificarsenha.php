<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lumiere";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Erro de conexão com o banco"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id_estante'], $data['senha'], $data['id_usuario'])) {
    echo json_encode(["success" => false, "message" => "Dados incompletos"]);
    exit;
}

$id_estante = $data['id_estante'];
$senha = $data['senha'];
$id_usuario = $data['id_usuario'];

$stmt = $conn->prepare("SELECT id_usuario FROM estantes WHERE id_estantes = ?");
$stmt->bind_param("i", $id_estante);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Estante não encontrada"]);
    exit;
}

$row = $result->fetch_assoc();
if ($row['id_usuario'] != $id_usuario) {
    echo json_encode(["success" => false, "message" => "Usuário não autorizado"]);
    exit;
}

$stmt2 = $conn->prepare("SELECT senha FROM usuarios WHERE id_usuario = ?");
$stmt2->bind_param("i", $id_usuario);
$stmt2->execute();
$result2 = $stmt2->get_result();

if ($result2->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Usuário não encontrado"]);
    exit;
}

$user = $result2->fetch_assoc();

if ($user['senha'] === $senha) {
    echo json_encode(["success" => true, "message" => "Senha correta"]);
} else {
    echo json_encode(["success" => false, "message" => "Senha incorreta"]);
}

$stmt->close();
$stmt2->close();
$conn->close();
?>
