<?php
session_start();
require '../model/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $escolaNome = $_POST['escola'] ?? '';
    $cnpj = $_POST['cnpj'] ?? '';
    $endereco = $_POST['endereco'] ?? '';
    $qtdAulas = $_POST['qtdAulas'] ?? '';

    if (!empty($cnpj)) {
        try {
            $pdo = Connection::getInstance();

            // Verifica se a escola já existe
            $sqlCheck = "SELECT id FROM escola WHERE cnpj = :cnpj";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->bindParam(':cnpj', $cnpj);
            $stmtCheck->execute();
            $escola = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($escola) {
                $escolaId = $escola['id'];
            } else {
                // Insere nova escola
                $sqlInsert = "INSERT INTO escola (nome, endereco, cnpj, qtd_aulas) VALUES (:nome, :endereco, :cnpj, :qtdAulas)";
                $stmtInsert = $pdo->prepare($sqlInsert);
                $stmtInsert->bindParam(':nome', $escolaNome);
                $stmtInsert->bindParam(':endereco', $endereco);
                $stmtInsert->bindParam(':cnpj', $cnpj);
                $stmtInsert->bindParam(':qtdAulas', $qtdAulas);
                $stmtInsert->execute();
                $escolaId = $pdo->lastInsertId();
            }

            // Armazena o ID da escola na sessão
            $_SESSION['escola_id'] = $escolaId;

            // Redireciona para o cadastro do usuário administrador
            header("Location: ../view/pages/cadastroUser.php");
            exit;
        } catch (Exception $e) {
            echo "Erro ao cadastrar a escola: " . $e->getMessage();
        }
    } else {
        echo "Por favor, informe o nome da escola.";
    }
}
?>
