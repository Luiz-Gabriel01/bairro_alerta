<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bairro_alerta";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Conexão falhou: " . $conn->connect_error);
}

$sql = "SELECT * FROM ocorrencias ORDER BY data_ocorrencia DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <title>Feed de Ocorrências</title>
  <link rel="stylesheet" href="feed.css">
  <link rel="icon" href="../logo.jpg" type="image/png">
</head>

<body>
  <div class="topo">
    <a href="../TelaNavegacao/navegacao.html" class="voltar">
      <img src="../TelaDeOcorrencia/voltar.jpeg" alt="Voltar" width="40">
    </a>
    <h1>Feed de Ocorrências</h1>
  </div>

  <div class="feed">
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="ocorrencia">
          <h2><?= htmlspecialchars($row['tipo_ocorrencia_texto']) ?></h2>
          <p><strong>Item roubado:</strong> <?= htmlspecialchars($row['item_roubado_texto']) ?></p>
          <p><strong>Horário:</strong> <?= htmlspecialchars($row['horario_texto']) ?></p>
          <p><strong>Endereço:</strong> <?= htmlspecialchars($row['endereco']) ?> | <strong>CEP:</strong> <?= htmlspecialchars($row['cep']) ?></p>
          <p><strong>Condições da rua:</strong> <?= htmlspecialchars($row['condicoes_rua']) ?></p>
          <p><strong>Descrição:</strong> <?= nl2br(htmlspecialchars($row['descricao'])) ?></p>
          <small>Registrado em: <?= date('d/m/Y H:i', strtotime($row['data_ocorrencia'])) ?></small>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="nenhuma">Nenhuma ocorrência registrada ainda.</p>
    <?php endif; ?>
  </div>
</body>

</html>