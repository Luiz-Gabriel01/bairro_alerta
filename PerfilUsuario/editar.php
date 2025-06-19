// Dados do formulário
$nome = $_POST['nome'];
$novo_email = $_POST['email'];

// Email atual na sessão (chave para identificar o usuário)
$email = $_SESSION['email_usuario'];

// Atualiza nome e email do usuário que tem este email
$stmt_update = mysqli_prepare($conexao, "UPDATE usuario SET nome = ?, email = ? WHERE email = ?");
mysqli_stmt_bind_param($stmt_update, "sss", $nome, $novo_email, $email);
$stmt_update->execute();

// Se deu certo, atualiza o email na sessão para o novo email
$_SESSION['email_usuario'] = $novo_email;
$_SESSION['nome_usuario'] = $nome;