<?php
session_start(); // Inicia a sessão apenas uma vez

if (!isset($_SESSION['dados_ocorrencia'])) {
    echo "Nenhuma ocorrência para confirmar.";
    exit();
}

$dados = $_SESSION['dados_ocorrencia'];

// Limpar os dados após exibição para evitar que persistam entre requisições
unset($_SESSION['dados_ocorrencia']);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Confirmação de Ocorrência</title>
    <link rel="stylesheet" href="confirmacao.css">
</head>

<body>
    <div class="container">
        <h1>Ocorrência registrada com sucesso!</h1>
        <h2>Detalhes:</h2>
        <ul>
            <li><strong>Endereço:</strong> <?= htmlspecialchars($dados['endereco'] ?: 'Não informado') ?></li>
            <li><strong>CEP:</strong> <?= htmlspecialchars($dados['cep'] ?: 'Não informado') ?></li>
            <li><strong>Tipo de Ocorrência:</strong> <?= htmlspecialchars($dados['tipo'] ?: 'Não especificado') ?></li>
            <li><strong>Item Roubado:</strong> <?= htmlspecialchars($dados['item'] ?: 'Não especificado') ?></li>
            <li><strong>Horário:</strong> <?= htmlspecialchars($dados['horario'] ?: 'Não especificado') ?></li>
            <li><strong>Condições da Rua:</strong> <?= htmlspecialchars($dados['condicoes'] ?: 'Não informado') ?></li>
            <li><strong>Descrição:</strong> <?= nl2br(htmlspecialchars($dados['descricao'])) ?></li>
        </ul>
        <a href="http://localhost/bairro_alerta/TelaNavegacao/navegacao.html" class="btn-voltar">Voltar ao início</a>
    </div>
</body>

</html>