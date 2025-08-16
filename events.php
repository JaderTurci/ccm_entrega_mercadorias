<?php
// Força o uso de UTF-8 nas respostas HTTP
header('Content-Type: text/html; charset=UTF-8');

// Obtenha dados do POST
$username = isset($_POST['username']) ? $_POST['username'] : '';
$senha = isset($_POST['senha']) ? $_POST['senha'] : '';
$numeroNota = isset($_POST['numero_nota']) ? $_POST['numero_nota'] : '';

// Sanitiza a saída para evitar XSS
$usernameSafe = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
$senhaSafe = htmlspecialchars($senha, ENT_QUOTES, 'UTF-8');
$numeroNotaSafe = htmlspecialchars($numeroNota, ENT_QUOTES, 'UTF-8');

// Estrutura HTML de retorno
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Dados enviados</title>
</head>
<body>
<p>Usuário: <?= $usernameSafe ?></p>
<p>Senha: <?= $senhaSafe ?></p>
<p>Número da Nota Fiscal: <?= $numeroNotaSafe ?></p>
</body>
</html>
