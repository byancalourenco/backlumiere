<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include "../conecta.php";

$sql = "SELECT * FROM obras WHERE tipo='Série'";
$resultado = $con->query($sql);

$dados = [];
while ($linha = $resultado->fetch_assoc()) {
    $dados[] = $linha;
}

echo json_encode($dados);
?>
