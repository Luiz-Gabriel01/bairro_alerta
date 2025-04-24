<?php
    // Conexão com o banco de dados
    $conexao = mysqli_connect('localhost', 'root', '', 'bairro_alerta');

    if (!$conexao) {
        echo "<script>
                alert('Erro: Não foi possível conectar ao banco de dados.');
                window.history.back();
              </script>";
        exit();
    }

    $login = $_POST['nome'];
    $password = md5($_POST['senha']); // A senha está sendo criptografada
    $email = $_POST['email'];

    // Verificar se o e-mail já existe no banco de dados
    $check_email_query = "SELECT * FROM usuario WHERE email = '$email'";
    $result = mysqli_query($conexao, $check_email_query);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>
                alert('Erro: Este e-mail já está cadastrado.');
                window.history.back();
              </script>";
        exit();
    } else {
        // Inserir novo usuário
        $sql = "INSERT INTO usuario (nome, email, senha) VALUES ('$login', '$email', '$password')";

        if (mysqli_query($conexao, $sql)) {
            echo "<script>
                    alert('Cadastro realizado com sucesso!');
                    window.location.href = './tela login/telaLogin.html';
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
?>
