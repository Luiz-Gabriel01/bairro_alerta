<?php
// Conexão com o banco de dados
$conexao = mysqli_connect('localhost', 'root', '', 'bairro_alerta');

if (!$conexao) {
    die("Erro: Não foi possível conectar ao banco de dados.");
}


$email = $_POST['email'];
$senha = md5($_POST['senha']); // Senha criptografada


$query = "SELECT * FROM usuario WHERE email = '$email' AND senha = '$senha'";
$result = mysqli_query($conexao, $query);

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
?>
