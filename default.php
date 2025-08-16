<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>CCM Entrega de Mercadorias - Formulário</title>
</head>
<body>
    <h1>Entrega de Mercadorias</h1>
    <form id="envioForm">
        <label for="username">Usuário:</label>
        <input type="text" name="username" id="username" required><br><br>

        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required><br><br>

        <label for="numero_nota">Número da Nota Fiscal:</label>
        <input type="text" name="numero_nota" id="numero_nota" required><br><br>

        <button type="submit">Enviar</button>
    </form>

    <div id="resposta" style="margin-top:20px;"></div>

    <script>
    document.getElementById('envioForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('events.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            document.getElementById('resposta').innerHTML = data;
        })
        .catch(error => {
            document.getElementById('resposta').innerText = 'Erro: ' + error;
        });
    });
    </script>
</body>
</html>
