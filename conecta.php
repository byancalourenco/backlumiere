<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "lumiere";

$con = new mysqli($servidor, $usuario, $senha, $banco);

if ($con->connect_error) {
    die(json_encode(["erro" => "Falha na conexão: " . $con->connect_error]));
}

$con->set_charset("utf8");
?>
