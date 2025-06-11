<?php
$conexao = mysqli_connect('localhost', 'root', '', 'bairro_alerta');

if (!$conexao) {
    die("Erro: Não foi possível conectar ao banco de dados.");
}

$email = $_POST['email'];
$senha = md5($_POST['senha']); // Senha criptografada (melhor usar password_hash)

$stmt = mysqli_prepare($conexao, "SELECT * FROM usuario WHERE email = ? AND senha = ?");
mysqli_stmt_bind_param($stmt, "ss", $email, $senha);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    header("Location: ../Telanavegacao/navegacao.html");
    exit();
} else {
    echo "<script>
            alert('Erro: Email ou senha inválidos.');
            window.history.back();
          </script>";
    exit();
}

mysqli_close($conexao);
