<?php
require './conexao.php'; // Certifique-se de que este arquivo contém a conexão com o banco

session_start();

$idEscola = $_SESSION['id_escola'];

$pdo = Connection::getInstance();
$sql = "SELECT * FROM escola WHERE id = $idEscola";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$escola= $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($escola);
?>
