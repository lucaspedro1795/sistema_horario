<?php

require './conexao.php';

session_start();

$idEscola = $_SESSION['id_escola'];


function cadastrarTurmas($nome,$idEscola){
    $pdo = Connection::getInstance();

    $sql = "INSERT INTO turmas(id,nome,id_escola) VALUES (0, :nome, :id_escola)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':id_escola', $idEscola);
    $stmt->execute();

}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nameTurm'] ?? '';

    if (!empty($nome) && !empty($idEscola)) {
        $turmas = cadastrarTurmas($nome, $idEscola);
        header("Location: ../view/home.php#turmas");
    } 
}

?>
