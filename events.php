<?php
// Detecta se a requisição é para listar notas fiscais
if (isset($_POST['action']) && $_POST['action'] === 'listar') {
    // Lista fixa de notas fiscais
    $notas = [
        '15423', '15453', '15543', '15643', '16743', '16892',
        '18443', '19443', '19543', '19643', '19655', '19666', '19667'
    ];
    // Retorna como JSON
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($notas);
    exit;
}

// Para outras requisições, força uso de UTF-8 em HTML
header('Content-Type: text/html; charset=UTF-8');

// Obtenha dados do POST
$username = isset($_POST['username']) ? $_POST['username'] : '';
$senha    = isset($_POST['senha'])    ? $_POST['senha']    : '';
$numeroNota = isset($_POST['numero_nota']) ? $_POST['numero_nota'] : '';

// Sanitiza a saída para evitar XSS
$usernameSafe   = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
$senhaSafe      = htmlspecialchars($senha,    ENT_QUOTES, 'UTF-8');
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
<p>Nota fiscal <?= $numeroNotaSafe ?> informada com sucesso</p>
</body>
</html>
