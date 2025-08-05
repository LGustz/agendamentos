<?php
// Cabeçalhos para download CSV que o Excel abre
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=agendamentos.csv');

// BOM UTF-8 para Excel
echo "\xEF\xBB\xBF";

// Força o Excel a usar delimitador de vírgula
// (algumas versões do Excel usam delimitador padrão do sistema)
fputs(fopen('php://output', 'w'), "sep=,\n");

// Abre o "arquivo" de saída
$output = fopen('php://output', 'w');

// Cabeçalho da tabela
$cabecalho = ['Nome', 'CPF', 'Telefone', 'Data', 'Horário', 'Obs'];
fputcsv($output, $cabecalho, ',', '"');

try {
    $pdo = new PDO('mysql:host=localhost;dbname=agendamentos;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obter parâmetros da requisição
    $data = $_GET['data'] ?? '';
    $horario = $_GET['horario'] ?? '';

    // Construir a consulta SQL 
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

    // Escrever cada linha
    while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, [
            $linha['nome'],
            $linha['cpf'],
            $linha['telefone'],
            $linha['data'],
            $linha['horario'],
            $linha['obs']
        ], ',', '"');
    }

} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}

fclose($output);
exit;
