<?php
// Força o uso de UTF-8 nas respostas HTTP
// 
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
        color: #fff;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }

    button:hover {
        opacity: 0.9;
    }

    /* Estilo para botão desabilitado */
    button:disabled {
        background-color: #6c757d;
        color: #fff;
        cursor: not-allowed;
        opacity: 0.65;
    }

    /* Estilo específico para o botão de envio */
    #submitBtn {
        background-color: #007bff;
    }
    #submitBtn:hover {
        background-color: #0069d9;
    }

    /* Estilo específico para o botão de atualizar lista */
    #updateListBtn {
        margin-top: 20px;
        background-color: #28a745;
    }
    #updateListBtn:hover {
        background-color: #218838;
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

/* Estilo para ícone de menu no canto superior esquerdo */
#menuIcon {
    position: absolute;
    top: 10px;
    left: 10px;
    font-size: 1.8em;
    cursor: pointer;
    color: #333;
    z-index: 1100;
}

/* Estilos para o popup de menu */
#menuPopup {
    display: none;
    position: fixed;
    z-index: 1200;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5);
}

#menuContent {
    background-color: #ffffff;
    margin: 20% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 300px;
    border-radius: 8px;
    text-align: center;
}

#menuContent ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

#menuContent li {
    margin: 10px 0;
    padding: 10px;
    cursor: pointer;
    background-color: #f2f2f2;
    border-radius: 4px;
}

#menuContent li:hover {
    background-color: #e0e0e0;
}

/* Tema escuro */
body.dark-mode {
    background-color: #2c2c2c;
    color: #f5f5f5;
}

body.dark-mode .modal-content,
body.dark-mode #menuContent {
    background-color: #444;
    color: #fff;
}

body.dark-mode input,
body.dark-mode .suggestions-list {
    background-color: #555;
    color: #fff;
    border-color: #666;
}

body.dark-mode button {
    color: #fff;
}


/* Ajustes de tema escuro para títulos e labels */
body.dark-mode h1,
body.dark-mode label {
    color: #f5f5f5;
}

body.dark-mode #menuIcon {
    color: #fff;
}

body.dark-mode #menuContent li {
    background-color: #555;
    color: #fff;
}

/* Inverte cores do logotipo no modo escuro */
body.dark-mode .logo-container img {
    filter: invert(1) brightness(2);
}

</style>
</head>
<body>
<div class="app-container" style="position: relative;">
    <!-- Ícone de menu no canto superior esquerdo -->
    <div id="menuIcon">&#9776;</div>
    <div class="logo-container">
        <img src="logotipo.png" alt="Logo CCM">
    </div>
    <h1>Entrega de Mercadorias</h1>
    <form id="envioForm">
        <!-- Seção de credenciais do usuário (nome de usuário e senha). Será ocultada após atualizar a lista -->
        <div id="credentialSection">
            <label for="username">Usuário:</label>
            <input type="text" id="username" name="username" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
        </div>

        <button type="button" id="updateListBtn">Entrar</button>

        <!-- Seção que agrupa o rótulo, o campo da nota, a lista de sugestões e o botão de envio
             Esta seção inicia oculta e só é exibida depois que a lista de notas é carregada -->
        <div id="notaSection" style="display: none;">
            <label for="numero_nota">Número da Nota Fiscal:</label>
            <!-- Campo numérico com entrada restrita a números -->
            <input type="text" id="numero_nota" name="numero_nota" required inputmode="numeric" pattern="\d*">
            <!-- Lista de sugestões de notas fiscais -->
            <ul id="suggestions" class="suggestions-list"></ul>

            <button type="submit" id="submitBtn" disabled>Enviar</button>
        </div>
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


<!-- Popup de menu -->
<div id="menuPopup" class="modal">
    <div id="menuContent">
        <ul>
            <li id="toggleTheme">Modo claro/escuro</li>
            <li id="logout">Sair</li>
        </ul>
    </div>
</div>
<script>
// Lista de notas fiscais (inicialmente vazia; será atualizada a partir do servidor)
let notas = [];

const inputNota    = document.getElementById('numero_nota');
const suggestions  = document.getElementById('suggestions');
const submitBtn    = document.getElementById('submitBtn');
const form         = document.getElementById('envioForm');
const updateListBtn= document.getElementById('updateListBtn');
// Seção de credenciais (usuário e senha) que deve ser ocultada após a atualização da lista
const credentialSection = document.getElementById('credentialSection');
// Seção que contém os controles de número da nota, lista de sugestões e botão de envio
const notaSection  = document.getElementById('notaSection');
// Variáveis relacionadas ao modal
const modal        = document.getElementById('modal');
const modalMessage = document.getElementById('modal-message');
const okBtn        = document.getElementById('okBtn');

// Referências aos campos de usuário e senha
const usernameInput = document.getElementById('username');
const senhaInput    = document.getElementById('senha');

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

// Garante que a seção de nota esteja oculta inicialmente
if (notaSection) {
    notaSection.style.display = 'none';
}

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

// Evento para atualizar a lista de notas fiscais a partir do servidor
updateListBtn.addEventListener('click', function() {
    // Cria FormData com credenciais e ação listar
    const data = new FormData();
    data.append('username', usernameInput.value);
    data.append('senha', senhaInput.value);
    data.append('action', 'listar');
    fetch('events.php', {
        method: 'POST',
        body: data
    })
    .then(response => response.json())
    .then(list => {
        // Atualiza a lista de notas
        notas = list;
        selectedNote = null;
        // Desabilita botão de envio até selecionar nova nota
        if (submitBtn) {
            submitBtn.disabled = true;
        }
        // Exibe ou oculta a seção de nota conforme existirem notas
        if (notaSection) {
            if (notas && notas.length > 0) {
                notaSection.style.display = 'block';
            } else {
                notaSection.style.display = 'none';
            }
        }

        // Oculta as credenciais, pois não será necessário redigitá-las
        if (credentialSection) {
            credentialSection.style.display = 'none';
        }
        // Atualiza as sugestões exibidas
        renderSuggestions('');

        // Após a primeira atualização, altera o texto do botão para "Atualizar lista"
        updateListBtn.textContent = 'Atualizar lista';
    })
    .catch(error => {
        // Mostra erro no modal
        modalMessage.innerHTML = 'Erro ao atualizar lista: ' + error;
        modal.style.display = 'block';
    });
});

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
    // Se não houver mais notas, oculta a seção de nota
    if (notaSection) {
        if (!notas || notas.length === 0) {
            notaSection.style.display = 'none';
        }
    }
});

// Funções e eventos para o menu
const menuIcon     = document.getElementById('menuIcon');
const menuPopup    = document.getElementById('menuPopup');
const toggleTheme  = document.getElementById('toggleTheme');
const logoutBtn    = document.getElementById('logout');

// Exibe o menu quando o ícone é clicado
if (menuIcon) {
    menuIcon.addEventListener('click', function() {
        menuPopup.style.display = 'block';
    });
}

// Alterna entre modo claro e escuro
if (toggleTheme) {
    toggleTheme.addEventListener('click', function() {
        document.body.classList.toggle('dark-mode');
        menuPopup.style.display = 'none';
    });
}

// Função para realizar logout: limpar dados e reiniciar interface
function logoutUser() {
    // Limpa localStorage de credenciais
    localStorage.removeItem('savedUsername');
    localStorage.removeItem('savedSenha');
    // Limpa campos de entrada
    usernameInput.value = '';
    senhaInput.value = '';
    // Exibe credenciais novamente
    if (credentialSection) {
        credentialSection.style.display = 'block';
    }
    // Oculta seção de notas
    if (notaSection) {
        notaSection.style.display = 'none';
    }
    // Limpa lista de notas
    notas = [];
    selectedNote = null;
    // Recarrega sugestões vazias
    renderSuggestions('');
    // Reinicia botão Entrar/Atualizar lista
    updateListBtn.textContent = 'Entrar';
    // Desabilita botão de envio
    if (submitBtn) {
        submitBtn.disabled = true;
    }
    // Oculta popup de menu
    menuPopup.style.display = 'none';
}

// Define evento de logout
if (logoutBtn) {
    logoutBtn.addEventListener('click', function() {
        logoutUser();
    });
}

</script>
</body>
</html>
