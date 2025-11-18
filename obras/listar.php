<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// conexao uou
include "../conecta.php";

// faz a consulta no banco para listar
$sql = "SELECT * FROM obras";
$resultado = $con->query($sql);

// cria a lista vazia tadinha para enviar para o front
$dados = [];

// pega linha por linha e coloca dentro da lista
while ($linha = $resultado->fetch_assoc()) {
    $dados[] = $linha;
}

// devolve os dados em formato JSON
echo json_encode($dados);
?>
