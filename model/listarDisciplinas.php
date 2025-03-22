<?php
require './conexao.php'; // Certifique-se de que este arquivo contém a conexão com o banco

session_start();

$idEscola = $_SESSION['id_escola'];

$pdo = Connection::getInstance();
$sql = "SELECT * FROM disciplinas WHERE id_escola = $idEscola ORDER BY nome";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$disciplina= $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($disciplina);
?>
