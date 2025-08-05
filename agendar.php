<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=agendamentos;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'] ?? '';
        $cpf = $_POST['cpf'] ?? '';
        $telefone = $_POST['telefone'] ?? '';
        $data = $_POST['data'] ?? '';
        $horario = $_POST['horario'] ?? '';
        $obs = $_POST['obs'] ?? '';

        // Verificar se o horário já tem 13 agendamentos
        $verificar = $pdo->prepare("SELECT COUNT(*) FROM agendamentos WHERE data = ? AND horario = ?");
        $verificar->execute([$data, $horario]);
        $quantidade = $verificar->fetchColumn();

        if ($quantidade >= 13) {
            echo "Este horário já atingiu o limite de 13 agendamentos. Por favor, escolha outro.";
            exit;
        }

        // Inserir agendamento (não haverá conflito UNIQUE se remover o índice)
        $stmt = $pdo->prepare("INSERT INTO agendamentos (nome, cpf, telefone, data, horario, obs) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nome, $cpf, $telefone, $data, $horario, $obs]);

        echo "Agendamento realizado com sucesso!";
        exit;
    }
} catch (PDOException $e) {
    echo "Erro no banco de dados: " . $e->getMessage();
    exit;
}
?>
