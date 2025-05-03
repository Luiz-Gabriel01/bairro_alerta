<?php
session_start(); // Necessário para usar $_SESSION

// Conexão com o banco
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bairro_alerta";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Conexão falhou: " . $conn->connect_error);
}

// Receber os dados do formulário
$endereco = $_POST['endereco'] ?? '';
$cep = $_POST['cep'] ?? '';
$descricao = $_POST['mensagem'] ?? '';

// Mapas para texto legível
$tipo_ocorrencia_valor = $_POST['tipo_ocorrencia'] ?? '';
$tipos_ocorrencia_map = [
  'assalto' => 'Assalto',
  'furto' => 'Furto',
  'roubo' => 'Roubo',
  'assassinato' => 'Assassinato',
  'latrocinio' => 'Latrocínio',
  'invasao' => 'Invasão a domicílio/estabelecimento',
  'assedio' => 'Assédio',
  'importunacao' => 'Importunação sexual',
  'outros' => 'Outros'
];
$tipo_ocorrencia_texto = $tipos_ocorrencia_map[$tipo_ocorrencia_valor] ?? 'Não especificado';

$item_roubado_valor = $_POST['item_roubado'] ?? '';
$itens_roubados_map = [
  'carro' => 'Carro',
  'moto' => 'Moto',
  'bicicleta' => 'Bicicleta',
  'celular' => 'Celular',
  'carteira' => 'Carteira',
  'bolsa' => 'Bolsa',
  'roupa' => 'Roupa',
  'chapeu' => 'Chapéu',
  'computador' => 'Computador',
  'oculos' => 'Óculos',
  'fone' => 'Fone',
  'relogio' => 'Relógio',
  'outros' => 'Outros'
];
$item_roubado_texto = $itens_roubados_map[$item_roubado_valor] ?? 'Não especificado';

$horario_valor = $_POST['horario'] ?? '';
$horarios_map = [
  'manha' => 'Manhã',
  'tarde' => 'Tarde',
  'noite' => 'Noite',
  'madrugada' => 'Madrugada',
  'outros' => 'Outros'
];
$horario_texto = $horarios_map[$horario_valor] ?? 'Não especificado';

// Checkboxes
$checklist = [];
if (isset($_POST['postes_apagados'])) $checklist[] = "Tem postes apagados";
if (isset($_POST['pouco_movimento_pessoas'])) $checklist[] = "Pouco/Nenhum movimento de pessoas";
if (isset($_POST['pouco_movimento_veiculos'])) $checklist[] = "Pouco/Nenhum movimento de veículos";
if (isset($_POST['outros_assaltos'])) $checklist[] = "Houve outros assaltos nessa rua";

$condicoes_rua = implode(", ", $checklist);

// Inserção no banco
$sql = "INSERT INTO ocorrencias (
    endereco, cep, tipo_ocorrencia_valor, tipo_ocorrencia_texto,
    item_roubado_valor, item_roubado_texto, horario_valor, horario_texto,
    condicoes_rua, descricao
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
  "ssssssssss",
  $endereco,
  $cep,
  $tipo_ocorrencia_valor,
  $tipo_ocorrencia_texto,
  $item_roubado_valor,
  $item_roubado_texto,
  $horario_valor,
  $horario_texto,
  $condicoes_rua,
  $descricao
);

if ($stmt->execute()) {
  // Salvar dados na sessão e redirecionar
  $_SESSION['dados_ocorrencia'] = [
    'endereco' => $endereco,
    'cep' => $cep,
    'tipo' => $tipo_ocorrencia_texto,
    'item' => $item_roubado_texto,
    'horario' => $horario_texto,
    'condicoes' => $condicoes_rua,
    'descricao' => $descricao
  ];
  header("Location: http://localhost/bairro_alerta/TelaDeOcorrencia/confirmacao.php");
  exit();
} else {
  echo "Erro ao registrar: " . $stmt->error;
}

$conn->close();
