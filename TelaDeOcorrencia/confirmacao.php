<?php
session_start();

if (!isset($_SESSION['dados_ocorrencia'])) {
    echo "Nenhuma ocorrência para confirmar.";
    exit();
}

$dados = $_SESSION['dados_ocorrencia'];


unset($_SESSION['dados_ocorrencia']);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Confirmação de Ocorrência</title>
    <link rel="stylesheet" href="./confirmacao.css">

</head>

<body>
    <div class="container">
        <h1>Ocorrência registrada com sucesso!</h1>
        <h2>Detalhes:</h2>
        <ul>
            <li><strong>Endereço:</strong> <?= htmlspecialchars($dados['endereco']) ?></li>
            <li><strong>CEP:</strong> <?= htmlspecialchars($dados['cep']) ?></li>
            <li><strong>Tipo de Ocorrência:</strong> <?= htmlspecialchars($dados['tipo']) ?></li>
            <li><strong>Item Roubado:</strong> <?= htmlspecialchars($dados['item']) ?></li>
            <li><strong>Horário:</strong> <?= htmlspecialchars($dados['horario']) ?></li>
            <li><strong>Condições da Rua:</strong> <?= htmlspecialchars($dados['condicoes'] ?: 'Não informado') ?></li>
            <li><strong>Descrição:</strong> <?= nl2br(htmlspecialchars($dados['descricao'])) ?></li>
        </ul>
        <a href="http://localhost/bairro_alerta/TelaNavegacao/navegacao.html" class="btn-voltar">Voltar ao início</a>
    </div>
</body>

</html>