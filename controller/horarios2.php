<?php
require '../model/conexao.php'; // Arquivo de conexão com o banco de dados

session_start(); // Inicia a sessão

$pdo = Connection::getInstance();

// Inserção de nova aula
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_professor = $_POST['id_professor'];
    $id_turma = $_POST['id_turma'];
    $id_disciplina = $_POST['id_disciplina'];
    $duracao_minutos = $_POST['duracao_minutos']; // Substituído "dia_semana" por "duracao_minutos"
    $id_escola = $_POST['id_escola'];

    // Obter o próximo número da aula disponível para o professor
    $stmt = $pdo->prepare("SELECT MAX(numero_aula) AS max_numero_aula FROM aulas WHERE id_professor = ?");
    $stmt->execute([$id_professor]);
    $result = $stmt->fetch();
    $numero_aula = $result['max_numero_aula'] + 1; // Próximo número da aula

    // Inserir a nova aula
    $stmt = $pdo->prepare("INSERT INTO aulas (id_professor, id_turma, id_disciplina, duracao_minutos, numero_aula, id_escola) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$id_professor, $id_turma, $id_disciplina, $duracao_minutos, $numero_aula, $id_escola]);
    
    header("Location: ../controller/horarios2.php"); // Redirecionar para a página de cadastro de aulas
    exit();
}

// Obtendo professores, turmas e disciplinas disponíveis
$professores = $pdo->query("SELECT id, nome FROM users WHERE tipoUser = 'Professor'")->fetchAll();
$turmas = $pdo->query("SELECT id, nome FROM turmas")->fetchAll();
$disciplinas = $pdo->query("SELECT id, nome FROM disciplinas")->fetchAll();
$aulas = $pdo->query("SELECT a.id, u.nome AS professor, t.nome AS turma, d.nome AS disciplina, a.duracao_minutos, a.numero_aula 
                      FROM aulas a 
                      JOIN users u ON a.id_professor = u.id 
                      JOIN turmas t ON a.id_turma = t.id 
                      JOIN disciplinas d ON a.id_disciplina = d.id")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Aulas</title>
</head>
<body>
    <h2>Cadastrar Aula</h2>
    <form method="POST">
        <label>Professor:</label>
        <select name="id_professor" required>
            <?php foreach ($professores as $prof) echo "<option value='{$prof['id']}'>{$prof['nome']}</option>"; ?>
        </select>
        <br>
        <label>Turma:</label>
        <select name="id_turma" required>
            <?php foreach ($turmas as $turma) echo "<option value='{$turma['id']}'>{$turma['nome']}</option>"; ?>
        </select>
        <br>
        <label>Disciplina:</label>
        <select name="id_disciplina" required>
            <?php foreach ($disciplinas as $disciplina) echo "<option value='{$disciplina['id']}'>{$disciplina['nome']}</option>"; ?>
        </select>
        <br>
        <label>Duração da Aula (minutos):</label>
        <input type="number" name="duracao_minutos" required>
        <br>
        <label>ID da Escola:</label>
        <input type="number" name="id_escola" required>
        <br>
        <button type="submit">Cadastrar</button>
    </form>
    
    <h2>Lista de Aulas Cadastradas</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Professor</th>
            <th>Turma</th>
            <th>Disciplina</th>
            <th>Duração (minutos)</th>
            <th>Número da Aula</th>
        </tr>
        <?php foreach ($aulas as $aula) {
            echo "<tr>
                    <td>{$aula['id']}</td>
                    <td>{$aula['professor']}</td>
                    <td>{$aula['turma']}</td>
                    <td>{$aula['disciplina']}</td>
                    <td>{$aula['duracao_minutos']}</td>
                    <td>{$aula['numero_aula']}</td>
                  </tr>";
        } ?>
    </table>
</body>
</html>
