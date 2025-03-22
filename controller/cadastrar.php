<?php
session_start();
require '../model/conexao.php';

function cadastrarUsers($nome, $cpf, $email, $senha){
    $pdo = Connection::getInstance();
    $sql = "INSERT INTO users(nome, cpf, email, senha, tipoUser) VALUES (:nome, :cpf, :email, :senha, 'Administrador')";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);
    $stmt->execute();

    return $pdo->lastInsertId(); // Retorna o ID do usuário cadastrado
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $cpf = $_POST['cpf'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (!empty($nome) && !empty($cpf) && !empty($email) && !empty($senha)) {
        $userId = cadastrarUsers($nome, $cpf, $email, $senha);
        
        // Armazena o ID do usuário na sessão
        $_SESSION['user_id'] = $userId;

        header("Location: ../view/plano.php");
        exit;
    } else {
        echo "Por favor, preencha todos os campos.";
    }
}
?>
