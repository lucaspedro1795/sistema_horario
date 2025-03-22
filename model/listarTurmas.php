<?php
require './conexao.php'; // Certifique-se de que este arquivo contém a conexão com o banco

session_start();

$idEscola = $_SESSION['id_escola'];

$pdo = Connection::getInstance();
$sql = "SELECT t.id AS ident, t.nome AS turma, e.id, e.nome AS escola FROM turmas AS t INNER JOIN escola AS e ON e.id = t.id_escola WHERE t.id_escola = $idEscola";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$turmas= $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($turmas);
?>
