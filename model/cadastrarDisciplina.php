<?php

require './conexao.php';

session_start();

$idEscola = $_SESSION['id_escola'];


function cadastrarDisciplina($nome,$idEscola){
    $pdo = Connection::getInstance();

    $sql = "INSERT INTO disciplinas(id,nome,id_escola) VALUES (0, :nome, :id_escola)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':id_escola', $idEscola);
    $stmt->execute();

}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nomeDisc'] ?? '';

    if (!empty($nome) && !empty($idEscola)) {
        $disciplinha = cadastrarDisciplina($nome, $idEscola);
        header("Location: ../view/home.php#disciplinas");
    } 
}

?>
