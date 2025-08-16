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
        cursor: pointer;
    }

    button:hover {
        background-color: #0069d9;
    }

    /* Estilo para botão desabilitado */
    button:disabled {
        background-color: #6c757d;
        color: #fff;
        cursor: not-allowed;
        opacity: 0.65;
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

    /* Estilos para o modal popup */
    .modal {
        display: none; /* oculto por padrão */
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: #ffffff;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 400px;
        border-radius: 8px;
        text-align: center;
    }

    #okBtn {
        margin-top: 20px;
        padding: 10px 20px;
        font-size: 1.2em;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    #okBtn:hover {
        background-color: #0069d9;
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

<!-- Modal para exibir a resposta -->
<div id="modal" class="modal">
    <div class="modal-content">
        <p id="modal-message"></p>
        <button id="okBtn">OK</button>
    </div>
</div>

<script>
// Lista de notas fiscais para autocomplete
const notas = ["15423","15453","15543","15643","16743","16892","18443","19443","19543","19643","19655","19666","19667"];

const inputNota = document.getElementById('numero_nota');
const suggestions = document.getElementById('suggestions');
const submitBtn = document.getElementById('submitBtn');
const form = document.getElementById('envioForm');
// Variáveis relacionadas ao modal
const modal = document.getElementById('modal');
const modalMessage = document.getElementById('modal-message');
const okBtn = document.getElementById('okBtn');

// Referências aos campos de usuário e senha
const usernameInput = document.getElementById('username');
const senhaInput = document.getElementById('senha');

// Carrega valores salvos no armazenamento local e pré-preenche os campos
if (localStorage.getItem('savedUsername')) {
    usernameInput.value = localStorage.getItem('savedUsername');
}
if (localStorage.getItem('savedSenha')) {
    senhaInput.value = localStorage.getItem('savedSenha');
}

// Persiste os valores digitados pelo usuário em localStorage sempre que houver mudança
usernameInput.addEventListener('input', function() {
    localStorage.setItem('savedUsername', this.value);
});
senhaInput.addEventListener('input', function() {
    localStorage.setItem('savedSenha', this.value);
});

// Variável para armazenar a nota fiscal selecionada
let selectedNote = null;

// Garante que o botão de envio inicie desabilitado
if (submitBtn) {
    submitBtn.disabled = true;
}

// Evento de envio do formulário
form.addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(this);
    fetch('events.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        // Exibe a resposta no modal em vez de na div #resposta
        modalMessage.innerHTML = data;
        modal.style.display = 'block';
    })
    .catch(error => {
        modalMessage.innerHTML = 'Erro ao enviar dados: ' + error;
        modal.style.display = 'block';
    });
});

// Função para renderizar as sugestões de notas fiscais
function renderSuggestions(value) {
    // Limpa a lista de sugestões
    suggestions.innerHTML = '';
    // Filtra notas que começam com o valor digitado (se vazio, retorna todas)
    const matches = notas.filter(nota => nota.startsWith(value));
    if (matches.length === 0) {
        suggestions.style.display = 'none';
        return;
    }
    matches.forEach(match => {
        const li = document.createElement('li');
        li.textContent = match;
        li.addEventListener('click', function() {
            inputNota.value = match;
            selectedNote = match;
            suggestions.style.display = 'none';
            // Ao selecionar uma nota, habilita o botão de envio
            if (submitBtn) {
                submitBtn.disabled = false;
            }
        });
        suggestions.appendChild(li);
    });
    suggestions.style.display = 'block';
}

// Evento de entrada no campo de número de nota
inputNota.addEventListener('input', function() {
    // Sempre desabilita o botão enquanto o usuário está digitando
    if (submitBtn) {
        submitBtn.disabled = true;
    }
    // Remove caracteres não numéricos para garantir apenas números
    let cleaned = this.value.replace(/[^0-9]/g, '');
    if (cleaned !== this.value) {
        this.value = cleaned;
    }
    const value = cleaned.trim();
    renderSuggestions(value);
});

// Exibe todas as sugestões quando a página carrega
inputNota.dispatchEvent(new Event('input'));

// Evento do botão OK do modal
okBtn.addEventListener('click', function() {
    // Fecha o modal
    modal.style.display = 'none';
    // Limpa o campo de nota
    inputNota.value = '';
    // Remove a nota selecionada do array de notas
    if (selectedNote) {
        const index = notas.indexOf(selectedNote);
        if (index > -1) {
            notas.splice(index, 1);
        }
        selectedNote = null;
    }
    // Desabilita novamente o botão de envio
    if (submitBtn) {
        submitBtn.disabled = true;
    }
    // Reexibe as sugestões atualizadas
    renderSuggestions('');
});
</script>
</body>
</html>
