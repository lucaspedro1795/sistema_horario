<?php
require './conexao.php'; // Certifique-se de que este arquivo contém a conexão com o banco

session_start();

$idEscola = $_SESSION['id_escola'];

$pdo = Connection::getInstance();
$sql = "SELECT u.id, u.nome, u.email, e.nome AS nomeescola FROM users AS u INNER JOIN escola AS e ON e.id = u.id_escola WHERE tipoUser = 'Professor' AND id_escola = $idEscola ORDER BY nome ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$professores= $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($professores);
?>
