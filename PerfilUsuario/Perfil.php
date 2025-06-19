<?php
session_start();

if (!isset($_SESSION['email_usuario'])) {
  header("Location: ../TelaLogin/telaLogin.html");
  exit();
}

$conexao = mysqli_connect('localhost', 'root', '', 'bairro_alerta');
if (!$conexao) {
  die("Erro: Não foi possível conectar ao banco de dados.");
}

$email = $_SESSION['email_usuario'];


$stmt = mysqli_prepare($conexao, "SELECT * FROM usuario WHERE email = ?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) !== 1) {
  
  session_destroy();
  header("Location: ../TelaLogin/login.html");
  exit();
}

$usuario = mysqli_fetch_assoc($result);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
  $nome = $_POST['nome'];
  $novo_email = $_POST['email'];
 

  $stmt_update = mysqli_prepare($conexao, "UPDATE usuario SET nome = ?, email = ? WHERE email = ?");
  mysqli_stmt_bind_param($stmt_update, "sss", $nome, $novo_email, $email);

  if ($stmt_update->execute()) {
    
    $_SESSION['email_usuario'] = $novo_email;
    $_SESSION['nome_usuario'] = $nome;
    echo "<script>alert('Dados atualizados com sucesso!');</script>";
  
    $usuario['nome'] = $nome;
    $usuario['email'] = $novo_email;
  } else {
    echo "<script>alert('Erro ao atualizar dados');</script>";
  }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
  $stmt_delete = mysqli_prepare($conexao, "DELETE FROM usuario WHERE email = ?");
  mysqli_stmt_bind_param($stmt_delete, "s", $email);
  if ($stmt_delete->execute()) {
    session_destroy();
    echo "<script>alert('Conta deletada com sucesso!'); window.location.href='../TelaLogin/telaLogin.html';</script>";
    exit();
  } else {
    echo "<script>alert('Erro ao deletar conta');</script>";
  }
}

mysqli_close($conexao);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <title>Perfil do Usuário</title>
  <link rel="stylesheet" href="perfil.css">
</head>

<body>

  <h2>Perfil de <?php echo htmlspecialchars($usuario['nome']); ?></h2>

  <form method="POST" action="">
    <label>Nome:</label><br>
    <input type="text" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required><br><br>

    <button type="submit" name="update">Salvar alterações</button>
  </form>

  <form method="POST" action="" onsubmit="return confirm('Tem certeza que quer deletar sua conta?');">
    <button type="submit" name="delete">Deletar conta</button>
  </form>

  <br><a href="../TelaNavegacao/navegacao.php">Voltar para Navegação</a>

</body>

</html>