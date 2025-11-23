<?php
header("Content-Type: application/json");
include "../conecta.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id = $_POST["id_obras"];
    $titulo = $_POST["titulo"];
    $tipo = $_POST["tipo"];
    $descricao = $_POST["descricao"];
    $ano = $_POST["ano"];
    $autor = $_POST["autor"];
    $editora = $_POST["editora"];
    $genero = $_POST["genero"];
    $classificacao = $_POST["classificacao"];

    // Imagem
    $novoNome = null;

    if (!empty($_FILES["capa"]["name"])) {
        $ext = pathinfo($_FILES["capa"]["name"], PATHINFO_EXTENSION);
        $novoNome = uniqid()."_obra.".$ext;
        move_uploaded_file($_FILES["capa"]["tmp_name"], "uploads/".$novoNome);

        $sql = "UPDATE obras SET capa='$novoNome' WHERE id_obras=$id";
        $con->query($sql);
    }

    $sql = "UPDATE obras SET 
                titulo='$titulo',
                tipo='$tipo',
                descricao='$descricao',
                ano_lancamento='$ano',
                autor='$autor',
                editora='$editora'
            WHERE id_obras=$id";

    if ($con->query($sql)) {
        echo json_encode(["erro" => false, "mensagem" => "Obra atualizada com sucesso!"]);
    } else {
        echo json_encode(["erro" => true, "mensagem" => "Erro ao atualizar obra"]);
    }
}
?>
