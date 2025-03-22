<?php
require './conexao.php'; // Certifique-se de que este arquivo contém a conexão com o banco

session_start();

$idEscola = $_SESSION['id_escola'];

$pdo = Connection::getInstance();
$sql = "SELECT *, f.nome AS filial, e.nome AS matriz FROM filiais AS f INNER JOIN escola AS e ON e.id = f.id_escola";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$filiais= $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($filiais);
?>
 