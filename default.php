<?php
// Força o uso de UTF-8 nas respostas HTTP
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Entrega de Mercadorias</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
        margin: 0;
        padding: 0;
    }

    .app-container {
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        box-sizing: border-box;
    }

    h1 {
        font-size: 1.8em;
        text-align: center;
        color: #333;
    }

    label {
        font-size: 1.3em;
        display: block;
        margin-top: 20px;
        color: #222;
    }

    input {
        width: 100%;
        font-size: 1.3em;
        padding: 12px 10px;
        margin-top: 5px;
        box-sizing: border-box;
        border: 2px solid #ccc;
        border-radius: 6px;
    }

    button {
        margin-top: 30px;
        width: 100%;
        font-size: 1.3em;
        padding: 14px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 6px;
    }

    button:hover {
        background-color: #0069d9;
    }

    #resposta {
        margin-top: 30px;
        font-size: 1.4em;
        color: #333;
        padding: 10px;
        background-color: #ffffff;
        border: 1px solid #ddd;
        border-radius: 6px;
        display: none;
    }

    .logo-container {
        text-align: center;
        margin-bottom: 15px;
    }

    .logo-container img {
        max-width: 120px;
        height: auto;
    }

    /* Estilos para a lista de sugestões de notas fiscais */
    .suggestions-list {
        list-style-type: none;
        padding: 0;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: #ffffff;
        max-height: 150px;
        overflow-y: auto;
        display: none;
    }

    .suggestions-list li {
        padding: 8px 12px;
        cursor: pointer;
    }

    .suggestions-list li:hover {
        background-color: #f0f0f0;
    }
</style>
</head>
<body>
<div class="app-container">
    <div class="logo-container">
        <img src="logotipo.png" alt="Logo CCM">
    </div>
    <h1>Entrega de Mercadorias</h1>
    <form id="envioForm">
        <label for="username">Usuário:</label>
        <input type="text" id="username" name="username" required>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>

        <label for="numero_nota">Número da Nota Fiscal:</label>
        <!-- Campo numérico com entrada restrita a números -->
        <input type="text" id="numero_nota" name="numero_nota" required inputmode="numeric" pattern="\d*">
        <!-- Lista de sugestões de notas fiscais -->
        <ul id="suggestions" class="suggestions-list"></ul>

        <button type="submit" id="submitBtn" disabled>Enviar</button>
    </form>
    <div id="resposta"></div>
</div>

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
        const resp = document.getElementById('resposta');
        resp.innerHTML = data;
        resp.style.display = 'block';
    })
    .catch(error => {
        const resp = document.getElementById('resposta');
        resp.innerHTML = 'Erro ao enviar dados: ' + error;
        resp.style.display = 'block';
    });

    // Fecha o manipulador de submissão do formulário
});

// Lista de notas fiscais para autocomplete
const notas = ["15423","15453","15543","15643","16743","16892","18443","19443","19543","19643","19655","19666","19667"];
const inputNota = document.getElementById('numero_nota');
const suggestions = document.getElementById('suggestions');
const submitBtn = document.getElementById('submitBtn');
// Garante que o botão de envio inicie desabilitado
if (submitBtn) {
    submitBtn.disabled = true;
}

inputNota.addEventListener('input', function() {
    // Sempre desabilita o botão enquanto o usuário está digitando
    if (submitBtn) {
        submitBtn.disabled = true;
    }

    // Remove caracteres não numéricos para garantir apenas números
    let cleaned = this.value.replace(/\D/g, '');
    if (cleaned !== this.value) {
        this.value = cleaned;
    }
    const value = cleaned.trim();
    // Limpa a lista e oculta se a entrada estiver vazia
    suggestions.innerHTML = '';
    if (value === '') {
        suggestions.style.display = 'none';
        return;
    }
    // Filtra notas que começam com o valor digitado
    const matches = notas.filter(nota => nota.startsWith(value));
    if (matches.length === 0) {
        suggestions.style.display = 'none';
        return;
    }
    // Cria elementos de lista para cada sugestão
    matches.forEach(match => {
        const li = document.createElement('li');
        li.textContent = match;
            li.addEventListener('click', function() {
                inputNota.value = match;
                suggestions.style.display = 'none';
                // Ao selecionar uma nota, habilita o botão de envio
                if (submitBtn) {
                    submitBtn.disabled = false;
                }
            });
        suggestions.appendChild(li);
    });
    suggestions.style.display = 'block';
});
</script>
</body>
</html>
