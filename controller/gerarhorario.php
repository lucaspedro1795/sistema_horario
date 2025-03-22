<?php
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

// Verifica se foi feita uma escolha
$tipo = $_POST['tipo'] ?? null;
$id_escola = $_POST['id_escola'] ?? null;
$id_turma = $_POST['id_turma'] ?? null;
$id_professor = $_POST['id_professor'] ?? null;

// Atribuição automática de aulas
if ($tipo === "coletivo" && $id_turma) {
    // Deleta horários antigos antes de inserir novos (comentado)
    // $pdo->prepare("DELETE FROM aulas WHERE id_turma = ? AND id_escola = ?")->execute([$id_turma, $id_escola]);

    // Busca professores disponíveis e distribui as aulas
    $dias = ["segunda", "terca", "quarta", "quinta", "sexta"];
    $qtd_aulas = $pdo->query("SELECT qtd_aulas FROM escola WHERE id = $id_escola")->fetchColumn();

    for ($aula = 1; $aula <= $qtd_aulas; $aula++) {
        foreach ($dias as $dia) {
            // Seleciona um professor disponível para essa aula e sua disciplina
            $stmt = $pdo->prepare("
                SELECT u.id AS id_professor, d.id AS id_disciplina, d.nome AS disciplina
                FROM disponibilidade disp
                JOIN users u ON disp.id_professor = u.id
                JOIN disciplinas d ON FIND_IN_SET(d.id, u.disciplinas)
                WHERE disp.numero_aula = ? AND disp.id_escola = ? 
                AND disp.$dia = 1 AND FIND_IN_SET(?, u.turmas)
                ORDER BY RAND() LIMIT 1
            ");
            $stmt->execute([$aula, $id_escola, $id_turma]);
            $professor = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($professor) {
                // Verifica se já existe uma aula para esse horário
                $stmt = $pdo->prepare("
                    SELECT COUNT(*) FROM aulas 
                    WHERE id_professor = ? AND id_turma = ? AND id_disciplina = ? 
                    AND numero_aula = ? AND id_escola = ? 
                ");
                $stmt->execute([ 
                    $professor['id_professor'], $id_turma, $professor['id_disciplina'], $aula, $id_escola
                ]);
                $existe = $stmt->fetchColumn();
            
                if ($existe == 0) { // Apenas insere se não existir
                    // Insere a aula com o professor e disciplina correspondente
                    $pdo->prepare("
                        INSERT INTO aulas (id_professor, id_turma, id_disciplina, numero_aula, id_escola)
                        VALUES (?, ?, ?, ?, ?)
                    ")->execute([ 
                        $professor['id_professor'], $id_turma, $professor['id_disciplina'], $aula, $id_escola
                    ]);
                }
            }
        }
    }
}

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
                    WHERE a.numero_aula = ? AND a.id_turma = ? 
                ");
                $stmt->execute([$aula, $id_turma]);
                $aula_info = $stmt->fetch(PDO::FETCH_ASSOC);
                $conteudo = $aula_info ? "{$aula_info['disciplina']}<br><small>{$aula_info['professor']}</small>" : "-";
            } elseif ($tipo == "individual" && $id_professor) {
                $stmt = $pdo->prepare("
                    SELECT d.nome AS disciplina, t.nome AS turma 
                    FROM aulas a
                    JOIN disciplinas d ON a.id_disciplina = d.id
                    JOIN turmas t ON a.id_turma = t.id
                    WHERE a.numero_aula = ? AND a.id_professor = ? 
                ");
                $stmt->execute([$aula, $id_professor]);
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
