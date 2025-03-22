<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Horários Escolares</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Horários Escolares</h2>
    <table>
        <tr>
            <th>Professor</th>
            <th>Dia</th>
            <th>Início</th>
            <th>Fim</th>
            <th>Status</th>
        </tr>
        <?php foreach ($horarios as $horario) : ?>
            <tr>
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
