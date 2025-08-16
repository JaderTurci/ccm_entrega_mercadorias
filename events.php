<?php
// Obtenha dados do POST
$username = isset($_POST['username']) ? $_POST['username'] : '';
$senha = isset($_POST['senha']) ? $_POST['senha'] : '';
$numeroNota = isset($_POST['numero_nota']) ? $_POST['numero_nota'] : '';

// Sanitiza a saída para evitar XSS
$usernameSafe = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
$senhaSafe = htmlspecialchars($senha, ENT_QUOTES, 'UTF-8');
$numeroNotaSafe = htmlspecialchars($numeroNota, ENT_QUOTES, 'UTF-8');

// Retorna os dados formatados
echo "<p>Usuário: {$usernameSafe}</p>";
echo "<p>Senha: {$senhaSafe}</p>";
echo "<p>Número da Nota Fiscal: {$numeroNotaSafe}</p>";
?>
