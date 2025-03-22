<?php
// form.php

// Configuração do banco de dados
$host = "localhost";
$dbname = "sys_bandeira";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

// Buscar escolas, turmas e professores
$escolas = $pdo->query("SELECT * FROM escola")->fetchAll(PDO::FETCH_ASSOC);
$turmas = $pdo->query("SELECT * FROM turmas")->fetchAll(PDO::FETCH_ASSOC);
$professores = $pdo->query("SELECT * FROM users WHERE tipoUser='Professor'")->fetchAll(PDO::FETCH_ASSOC);

// Lógica para gerar a tabela após o envio do formulário
$tipo = $_POST['tipo'] ?? null;
$id_escola = $_POST['id_escola'] ?? null;
$id_turma = $_POST['id_turma'] ?? null;
$id_professor = $_POST['id_professor'] ?? null;

if ($tipo && $id_escola) {
    echo "<h3>Horário Escolar</h3>";
    echo "<table>";
    echo "<tr><th>Aula</th><th>Segunda</th><th>Terça</th><th>Quarta</th><th>Quinta</th><th>Sexta</th></tr>";

    $qtd_aulas = $pdo->query("SELECT qtd_aulas FROM escola WHERE id = $id_escola")->fetchColumn();

    for ($aula = 1; $aula <= $qtd_aulas; $aula++) {
        echo "<tr><td>$aula</td>";

        foreach (["segunda", "terca", "quarta", "quinta", "sexta"] as $dia) {
            if ($tipo == "coletivo" && $id_turma) {
                $stmt = $pdo->prepare("
                    SELECT d.nome AS disciplina, u.nome AS professor 
                    FROM aulas a
                    JOIN disciplinas d ON a.id_disciplina = d.id
                    JOIN users u ON a.id_professor = u.id
                    WHERE a.numero_aula = ? AND a.id_turma = ? AND a.dia_semana = ?
                ");
                $stmt->execute([$aula, $id_turma, $dia]);
                $aula_info = $stmt->fetch(PDO::FETCH_ASSOC);
                $conteudo = $aula_info ? "{$aula_info['disciplina']}<br><small>{$aula_info['professor']}</small>" : "-";
            } elseif ($tipo == "individual" && $id_professor) {
                $stmt = $pdo->prepare("
                    SELECT d.nome AS disciplina, t.nome AS turma 
                    FROM aulas a
                    JOIN disciplinas d ON a.id_disciplina = d.id
                    JOIN turmas t ON a.id_turma = t.id
                    WHERE a.numero_aula = ? AND a.id_professor = ? AND a.dia_semana = ?
                ");
                $stmt->execute([$aula, $id_professor, $dia]);
                $aula_info = $stmt->fetch(PDO::FETCH_ASSOC);
                $conteudo = $aula_info ? "{$aula_info['disciplina']}<br><small>{$aula_info['turma']}</small>" : "-";
            } else {
                $conteudo = "-";
            }

            echo "<td>$conteudo</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Horários</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: center; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>

<h2>Gerar Horário Escolar</h2>

<form method="POST" action="../controller/gerarhorario.php">
    <label>Escolha o tipo de horário:</label><br>
    <select name="tipo" required>
        <option value="">Selecione</option>
        <option value="coletivo">Horário Coletivo (por turma)</option>
        <option value="individual">Horário Individual (por professor)</option>
    </select><br><br>

    <label>Selecione a Escola:</label><br>
    <select name="id_escola" required>
        <option value="">Selecione</option>
        <?php
        foreach ($escolas as $escola) {
            echo "<option value='{$escola['id']}'>{$escola['nome']}</option>";
        }
        ?>
    </select><br><br>

    <div id="turmaSelect" style="display: none;">
        <label>Selecione a Turma:</label><br>
        <select name="id_turma">
            <option value="">Selecione</option>
            <?php
            foreach ($turmas as $turma) {
                echo "<option value='{$turma['id']}'>{$turma['nome']}</option>";
            }
            ?>
        </select><br><br>
    </div>

    <div id="professorSelect" style="display: none;">
        <label>Selecione o Professor:</label><br>
        <select name="id_professor">
            <option value="">Selecione</option>
            <?php
            foreach ($professores as $professor) {
                echo "<option value='{$professor['id']}'>{$professor['nome']}</option>";
            }
            ?>
        </select><br><br>
    </div>

    <button type="submit">Gerar Horário</button>
</form>

<script>
    document.querySelector("select[name='tipo']").addEventListener("change", function() {
        document.getElementById("turmaSelect").style.display = this.value === "coletivo" ? "block" : "none";
        document.getElementById("professorSelect").style.display = this.value === "individual" ? "block" : "none";
    });
</script>

</body>
</html>
