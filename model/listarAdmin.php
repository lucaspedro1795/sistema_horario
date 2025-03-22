<?php
require '../model/conexao.php';

session_start();

$idEscola = $_SESSION['id_escola'];

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = Connection::getInstance();
    $sql = "SELECT id, nome, email, foto FROM users WHERE id_escola = $idEscola"; 
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($usuarios as &$usuario) {
        if (!empty($usuario['foto'])) {
            $usuario['foto'] = base64_encode($usuario['foto']); // Converte imagem para Base64
        } else {
            $usuario['foto'] = null; // Define como null caso não tenha foto
        }
    }

    // Se não houver usuários, retorne um array vazio
    echo json_encode($usuarios ?: []);

} catch (Exception $e) {
    echo json_encode(["error" => "Erro ao buscar usuários", "details" => $e->getMessage()]);
}
