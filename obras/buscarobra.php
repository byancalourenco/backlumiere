<?php

header("Content-Type: application/json");



include "../conecta.php";

$id = $_GET["id_obras"];

$sql = "SELECT * FROM obras WHERE id_obras = $id";
$res = $con->query($sql);



if ($res->num_rows > 0) {
     echo json_encode($res->fetch_assoc());
} else {
     echo json_encode(["erro" => true, "mensagem" => "Obra não encontrada"]);
}
?>
