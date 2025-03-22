<?php

require './conexao.php';

file_put_contents('log.txt', file_get_contents("php://input"));

session_start();

// print_r($_SESSION);

$idEscola = $_SESSION['id_escola'] ?? null;
$idProfessor = $_SESSION['id'] ?? null;

// echo $idEscola;
echo $idProfessor;

function cadastrarDisponibilidade($idProfessor, $idEscola, $numeroAula, $segunda, $terca, $quarta, $quinta, $sexta) {
    $pdo = Connection::getInstance();

    // Deleta registros antigos do professor para evitar duplicação
    $sqlDelete = "DELETE FROM disponibilidade WHERE id_professor = :id_professor AND numero_aula = :numero_aula";
    $stmtDelete = $pdo->prepare($sqlDelete);
    $stmtDelete->bindParam(':id_professor', $idProfessor, PDO::PARAM_INT);
    $stmtDelete->bindParam(':numero_aula', $numeroAula, PDO::PARAM_INT);
    $stmtDelete->execute();

    // Insere a nova disponibilidade
    $sqlInsert = "INSERT INTO disponibilidade (numero_aula, segunda, terca, quarta, quinta, sexta, id_professor, id_escola) 
    VALUES (:numero_aula, :segunda, :terca, :quarta, :quinta, :sexta, :id_professor, :id_escola)";


    $stmtInsert = $pdo->prepare($sqlInsert);
    $stmtInsert->bindParam(':id_professor', $idProfessor, PDO::PARAM_INT);
    $stmtInsert->bindParam(':id_escola', $idEscola, PDO::PARAM_INT);
    $stmtInsert->bindParam(':numero_aula', $numeroAula, PDO::PARAM_INT);
    $stmtInsert->bindParam(':segunda', $segunda, PDO::PARAM_BOOL);
    $stmtInsert->bindParam(':terca', $terca, PDO::PARAM_BOOL);
    $stmtInsert->bindParam(':quarta', $quarta, PDO::PARAM_BOOL);
    $stmtInsert->bindParam(':quinta', $quinta, PDO::PARAM_BOOL);
    $stmtInsert->bindParam(':sexta', $sexta, PDO::PARAM_BOOL);

    $stmtInsert->execute();
}

// Processar requisição AJAX
$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data['id_professor']) && !empty($idEscola) && !empty($data['disponibilidades'])) {
    $idProfessor = $_SESSION['id'] ?? null;

    // Criar um array para armazenar as disponibilidades por aula
    $aulasDisponibilidade = [];

    foreach ($data['disponibilidades'] as $disponibilidade) {
        $aula = $disponibilidade['aula'];
        $dia = $disponibilidade['dia'];
        $checked = $disponibilidade['checked'];

        if (!isset($aulasDisponibilidade[$aula])) {
            $aulasDisponibilidade[$aula] = [0, 0, 0, 0, 0]; // Segunda a Sexta
        }

        $aulasDisponibilidade[$aula][$dia] = $checked;
    }

    // Inserir no banco de dados
    foreach ($aulasDisponibilidade as $numeroAula => $dias) {
        cadastrarDisponibilidade($idProfessor, $idEscola, $numeroAula, $dias[0], $dias[1], $dias[2], $dias[3], $dias[4]);
    }

    echo json_encode(["status" => "success", "message" => "Disponibilidade cadastrada com sucesso"]);
} else {
    echo json_encode(["status" => "error", "message" => "Dados inválidos"]);
}
?>
