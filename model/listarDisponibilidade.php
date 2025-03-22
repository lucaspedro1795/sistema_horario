<?php
require './conexao.php'; // Certifique-se de que este arquivo contém a conexão com o banco

session_start();

$idEscola = $_SESSION['id_escola'];
$idProfessor = $_SESSION['id'];


$pdo = Connection::getInstance();
$sql = "SELECT * FROM disponibilidade WHERE id_professor = $idProfessor";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$disponibilidade= $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($disponibilidade);
?>
