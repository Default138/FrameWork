// ─── Estado ────────────────────────────────────────────────────────────────
let x = 0; // contador de campos

// ─── Mapa de tipos disponíveis ──────────────────────────────────────────────
const TIPOS = {
    text:     "Texto",
    number:   "Número",
    date:     "Data",
    select:   "Opções (Select)",   // NOVO – com sub-opções editáveis
    textarea: "Área de texto"      // NOVO – textarea
};

// ─── Inicialização: cria o primeiro campo automaticamente ───────────────────
window.addEventListener("DOMContentLoaded", () => {
    criarCampoNoDom(); // campo 0 já aparece ao abrir
});

// ─── novoCampo ──────────────────────────────────────────────────────────────
function novoCampo() {
    criarCampoNoDom();
}

// ─── criarCampoNoDom ────────────────────────────────────────────────────────
// Monta a linha de um campo no construtor e a insere no DOM.
function criarCampoNoDom() {
    const idx = x;
    const wrapper = document.getElementById("campos-wrapper");

    // Linha principal
    const linha = document.createElement("div");
    linha.className = "campo-linha";
    linha.id = "campo-linha-" + idx;

    // --- Rótulo ---
    const divRotulo = document.createElement("div");
    const lblRotulo = document.createElement("label");
    lblRotulo.innerText = "Rótulo";
    const inputRotulo = document.createElement("input");
    inputRotulo.type = "text";
    inputRotulo.name = "rotulo" + idx;
    inputRotulo.placeholder = "Ex: Nome";
    divRotulo.appendChild(lblRotulo);
    divRotulo.appendChild(inputRotulo);

    // --- Tipo ---
    const divTipo = document.createElement("div");
    const lblTipo = document.createElement("label");
    lblTipo.innerText = "Tipo";
    const select = document.createElement("select");
    select.name = "tipo" + idx;

    for (const [valor, texto] of Object.entries(TIPOS)) {
        const opt = document.createElement("option");
        opt.value = valor;
        opt.innerText = texto;
        select.appendChild(opt);
    }
    divTipo.appendChild(lblTipo);
    divTipo.appendChild(select);

    // ── NOVO: Container de opções do <select> ────────────────────────────────
    // Aparece apenas quando o tipo escolhido é "select".
    const divOpcoes = document.createElement("div");
    divOpcoes.className = "opcoes-container";
    divOpcoes.id = "opcoes-" + idx;

    const lblOpcoes = document.createElement("label");
    lblOpcoes.innerText = "Opções (uma por linha):";
    divOpcoes.appendChild(lblOpcoes);

    // Botão para adicionar mais campos de opção
    const btnAddOpcao = document.createElement("button");
    btnAddOpcao.type = "button";
    btnAddOpcao.innerText = "+ Adicionar opção";
    btnAddOpcao.onclick = () => adicionarSubOpcao(idx);
    divOpcoes.appendChild(btnAddOpcao);

    // Já inicia com 2 campos de opção
    adicionarSubOpcaoNoDom(divOpcoes, idx, 0);
    adicionarSubOpcaoNoDom(divOpcoes, idx, 1);
    // ────────────────────────────────────────────────────────────────────────

    // Listener: mostra/esconde o container de opções conforme o tipo escolhido
    select.addEventListener("change", () => {
        if (select.value === "select") {
            divOpcoes.classList.add("visivel");
        } else {
            divOpcoes.classList.remove("visivel");
        }
    });

    // Monta a linha
    linha.appendChild(divRotulo);
    linha.appendChild(divTipo);
    linha.appendChild(divOpcoes);

    wrapper.appendChild(linha);
    wrapper.appendChild(Object.assign(document.createElement("hr"), {}));

    x++;
}

// ─── adicionarSubOpcao (chamado pelo botão "+") ─────────────────────────────
function adicionarSubOpcao(campoIdx) {
    const container = document.getElementById("opcoes-" + campoIdx);
    // conta quantos inputs de opção já existem nesse container
    const qtd = container.querySelectorAll("input.sub-opcao").length;
    adicionarSubOpcaoNoDom(container, campoIdx, qtd);
}

// ─── adicionarSubOpcaoNoDom ─────────────────────────────────────────────────
// Cria um input de texto para uma opção do <select> e insere ANTES do botão.
function adicionarSubOpcaoNoDom(container, campoIdx, opcaoIdx) {
    const input = document.createElement("input");
    input.type = "text";
    input.className = "sub-opcao";
    input.name = "opcao_" + campoIdx + "_" + opcaoIdx;
    input.placeholder = "Opção " + (opcaoIdx + 1);

    // Insere antes do botão "+ Adicionar opção" (sempre o último filho)
    const btnAdd = container.querySelector("button");
    container.insertBefore(input, btnAdd);
}

// ─── visualizarForm ─────────────────────────────────────────────────────────
// Lê todos os campos definidos e gera o HTML do formulário no <iframe>.
function visualizarForm() {
    const iframe = document.getElementById("preview");
    const doc = iframe.contentDocument || iframe.contentWindow.document;

    // Estilos básicos para o preview
    let html = `
        <style>
            body { font-family: 'Segoe UI', sans-serif; padding: 16px; color: #222; }
            label { display: block; font-size: 0.85rem; font-weight: 600; margin-top: 12px; margin-bottom: 4px; }
            input, select, textarea {
                display: block;
                width: 100%;
                padding: 7px 10px;
                border: 1px solid #ccc;
                border-radius: 6px;
                font-size: 0.9rem;
                font-family: inherit;
            }
            textarea { resize: vertical; min-height: 80px; }
        </style>
        <form>
    `;

    for (let i = 0; i < x; i++) {
        const inputRotulo = document.getElementsByName("rotulo" + i)[0];
        const inputTipo   = document.getElementsByName("tipo" + i)[0];

        if (!inputRotulo || !inputTipo) continue;

        const rotulo = inputRotulo.value || "Campo " + (i + 1);
        const tipo   = inputTipo.value;

        html += `<label>${rotulo}</label>`;

        if (tipo === "select") {
            // ── NOVO: coleta as sub-opções preenchidas pelo usuário ──────────
            const subOpcoes = document.querySelectorAll(`input.sub-opcao[name^="opcao_${i}_"]`);
            html += `<select name="${rotulo}">`;
            subOpcoes.forEach(opt => {
                const texto = opt.value.trim() || opt.placeholder;
                html += `<option>${texto}</option>`;
            });
            html += `</select>`;
            // ────────────────────────────────────────────────────────────────

        } else if (tipo === "textarea") {
            // ── NOVO: textarea ───────────────────────────────────────────────
            html += `<textarea name="${rotulo}" placeholder="Digite aqui..."></textarea>`;
            // ────────────────────────────────────────────────────────────────

        } else {
            html += `<input type="${tipo}" name="${rotulo}">`;
        }
    }

    html += `<br><button type="submit" style="margin-top:14px;padding:8px 20px;background:#4f46e5;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:0.9rem;">Enviar</button></form>`;

    doc.open();
    doc.write(html);
    doc.close();
}
