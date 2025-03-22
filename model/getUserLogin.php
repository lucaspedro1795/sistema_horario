<?php

require './conexao.php';

function getUser($email, $senha){
    $pdo = Connection::getInstance();

    $sql = "SELECT * FROM users WHERE email = :email AND senha = :senha";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam('senha', $senha);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (!empty($email) && !empty($senha)) {
        $user = getUser($email, $senha);

        if ($user) {
            session_start();
            $_SESSION['user'] = $user;
            $_SESSION['id'] = $user['id'];
            $_SESSION['id_escola'] = $user['id_escola'];
            echo json_encode(["success" => true, "message" => "Login realizado com sucesso!"]);
        } else {
            echo json_encode(["success" => false, "message" => "E-mail ou senha inválidos!"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Preencha todos os campos!"]);
    }
}

?>
