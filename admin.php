<?php
$pdo = new PDO('mysql:host=localhost;dbname=agendamentos;charset=utf8', 'root', '');

$data = isset($_GET['data']) ? $_GET['data'] : '';
$horario = isset($_GET['horario']) ? $_GET['horario'] : '';

$query = "SELECT * FROM agendamentos WHERE 1";
$params = [];

if ($data) {
    $query .= " AND data LIKE ?";
    $params[] = "%$data%";
}

if ($horario) {
    $query .= " AND horario = ?";
    $params[] = $horario;
}

$query .= " ORDER BY data, horario";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gerar os horários disponíveis
function gerarHorarios() {
    $horarios = [];
    $hora = 8;
    $minuto = 30;

    while ($hora < 19) {
        $totalMin = $hora * 60 + $minuto;

        if (!(($totalMin >= 690 && $totalMin < 790) || $totalMin === 1010)) {
            $horarios[] = sprintf("%02d:%02d", $hora, $minuto);
        }

        $minuto += 20;
        if ($minuto >= 60) {
            $minuto -= 60;
            $hora++;
        }
    }

    return $horarios;
}

$horariosDisponiveis = gerarHorarios();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            padding: 30px;
        }

        h1 {
            color: #333;
        }

        form {
            margin-bottom: 20px;
            background: #fff;
            padding: 15px;
            border-radius: 5px;
            display: flex;
            gap: 20px;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            flex-wrap: wrap;
        }

        label {
            font-weight: bold;
        }

        input[type="date"],
        select {
            padding: 6px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 6px 12px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <h1>Agendamentos</h1>

    <form method="GET" style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap;">
    <label>Filtrar por data:
        <input type="date" name="data" value="<?= htmlspecialchars($data) ?>">
    </label>

    <label>Filtrar por horário:
        <select name="horario">
            <option value="">Todos</option>
            <?php foreach ($horariosDisponiveis as $h): ?>
                <option value="<?= $h ?>" <?= ($horario === $h) ? 'selected' : '' ?>><?= $h ?></option>
            <?php endforeach; ?>
        </select>
    </label>

    <button type="submit">Filtrar</button>

    <!-- Botão para exportar -->
    <button type="submit" formaction="exportar.php" formmethod="GET">Exportar Excel</button>
</form>


    <table>
        <tr>
            <th>Nome</th>
            <th>CPF</th>
            <th>Telefone</th>
            <th>Data</th>
            <th>Horário</th>
            <th>Obs</th>
        </tr>
        <?php foreach ($dados as $linha): ?>
            <tr>
                <td><?= htmlspecialchars($linha['nome']) ?></td>
                <td><?= htmlspecialchars($linha['cpf']) ?></td>
                <td><?= htmlspecialchars($linha['telefone']) ?></td>
                <td><?= $linha['data'] ?></td>
                <td><?= $linha['horario'] ?></td>
                <td><?= htmlspecialchars($linha['obs']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
