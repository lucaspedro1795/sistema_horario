<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// Conexão com o banco de dados
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'escola';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Função para buscar professores e suas disponibilidades
function getProfessoresDisponiveis($conn) {
    $sql = "SELECT p.id, p.nome, d.dia_semana, d.hora_inicio, d.hora_fim FROM professores p 
            JOIN disponibilidade d ON p.id = d.professor_id ORDER BY p.id, d.dia_semana, d.hora_inicio";
    $result = $conn->query($sql);
    
    $professores = [];
    while ($row = $result->fetch_assoc()) {
        $professores[$row['id']]['nome'] = $row['nome'];
        $professores[$row['id']]['disponibilidade'][] = [
            'dia' => $row['dia_semana'],
            'inicio' => $row['hora_inicio'],
            'fim' => $row['hora_fim']
        ];
    }
    return $professores;
}

// Função para gerar horários com base na disponibilidade
function gerarHorarios($conn) {
    $professores = getProfessoresDisponiveis($conn);
    $horarios = [];

    foreach ($professores as $id => $professor) {
        foreach ($professor['disponibilidade'] as $disp) {
            $horarios[] = [
                'professor' => $professor['nome'],
                'dia' => $disp['dia'],
                'inicio' => $disp['inicio'],
                'fim' => $disp['fim'],
                'status' => verificarConflitos($conn, $id, $disp['dia'], $disp['inicio'], $disp['fim'])
            ];
        }
    }
    return $horarios;
}

// Função para verificar conflitos
function verificarConflitos($conn, $professor_id, $dia, $inicio, $fim) {
    $sql = "SELECT COUNT(*) as total FROM horarios WHERE professor_id = ? AND dia_semana = ? 
            AND ((hora_inicio <= ? AND hora_fim > ?) OR (hora_inicio < ? AND hora_fim >= ?))";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $professor_id, $dia, $inicio, $inicio, $fim, $fim);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['total'] > 0 ? 'Conflito' : 'Disponível';
}

$horarios = gerarHorarios($conn);

// Exportar para PDF
if (isset($_GET['export']) && $_GET['export'] == 'pdf') {
    $options = new Options();
    $options->set('defaultFont', 'Courier');
    $dompdf = new Dompdf($options);
    
    ob_start();
    include 'pdf_template.php';
    $html = ob_get_clean();
    
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    $dompdf->stream("horarios.pdf", ["Attachment" => false]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horários Escolares</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .conflito { background-color: #ffcccc; }
        .disponivel { background-color: #ccffcc; }
    </style>
</head>
<body>
    <h2>Horários Escolares</h2>
    <a href="?export=pdf">Exportar para PDF</a>
    <table>
        <tr>
            <th>Professor</th>
            <th>Dia</th>
            <th>Início</th>
            <th>Fim</th>
            <th>Status</th>
        </tr>
        <?php foreach ($horarios as $horario) : ?>
            <tr class="<?php echo $horario['status'] == 'Conflito' ? 'conflito' : 'disponivel'; ?>">
                <td><?php echo $horario['professor']; ?></td>
                <td><?php echo $horario['dia']; ?></td>
                <td><?php echo $horario['inicio']; ?></td>
                <td><?php echo $horario['fim']; ?></td>
                <td><?php echo $horario['status']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
