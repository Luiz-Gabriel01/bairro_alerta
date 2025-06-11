<?php
$conexao = mysqli_connect('localhost', 'root', '', 'bairro_alerta');

if (!$conexao) {
    echo "<script>
            alert('Erro: Não foi possível conectar ao banco de dados.');
            window.history.back();
          </script>";
    exit();
}

$login = $_POST['nome'];
$password = md5($_POST['senha']);
$email = $_POST['email'];

// Preparar statement para verificar se o email já existe
$stmt = mysqli_prepare($conexao, "SELECT * FROM usuario WHERE email = ?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    echo "<script>
            alert('Erro: Este e-mail já está cadastrado.');
            window.history.back();
          </script>";
    exit();
} else {
    // Preparar statement para inserir novo usuário
    $stmt = mysqli_prepare($conexao, "INSERT INTO usuario (nome, email, senha) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $login, $email, $password);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
                alert('Cadastro realizado com sucesso!');
                window.location.href = '../Telalogin/telaLogin.html';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Erro: Não foi possível completar o cadastro. Tente novamente mais tarde.');
                window.history.back();
              </script>";
        exit();
    }
}

mysqli_close($conexao);
