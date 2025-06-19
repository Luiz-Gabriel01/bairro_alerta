<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
  header("Location: login.html");
  exit();
}

$conexao = mysqli_connect('localhost', 'root', '', 'bairro_alerta');
$id = $_SESSION['id_usuario'];

$stmt = mysqli_prepare($conexao, "DELETE FROM usuario WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

session_destroy();

// Redireciona via PHP
header("Location:../TelaLogin/telaLogin.html");
exit();
