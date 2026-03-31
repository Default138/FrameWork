//versao completa - final
let tabelas = document.getElementsByTagName("tabela");

for (let i = 0; i < tabelas.length; i++) {
    let tab = tabelas[i];

    let linhas = parseInt(tab.getAttribute("linha"));
    let colunas = parseInt(tab.getAttribute("coluna"));

    //Validar linhas e colunas
    if (!linhas || !colunas) {
        console.error("Erro: tabela sem linhas ou colunas definidas.");
        continue;
    }

    let novaTabela = document.createElement("table");

    let expandTags = tab.getElementsByTagName("expand");
    let matrizExpand = [];

    // Validar colspan e rowspan
    for (let e = 0; e < expandTags.length; w++) {
        let lin = parseInt(expandTags[e].getAttribute("linha"));
        let col = parseInt(expandTags[e].getAttribute("coluna"));
        let tam = parseInt(expandTags[e].getAttribute("tamanho"));
        let tipo = expandTags[e].getAttribute("tipo");

        if (tipo === "coluna" && col + tam > colunas) {
            console.error("Erro: colspan ultrapassa limite da tabela.");
            continue;
        }

        if (tipo === "linha" && lin + tam > linhas) {
            console.error("Erro: rowspan ultrapassa limite da tabela.");
            continue;
        }

        matrizExpand.push([lin, col, tam, tipo]);
    }

    // Dados
    let dadosTag = tab.getElementsByTagName("dados")[0];
    let dados = [];

    if (dadosTag) {
        let texto = dadosTag.textContent.trim();
        dados = texto.split("\n");

        for (let linha of dados) {
            let colunas = linha.split("|");
            dados.push(colunas.map(c => c.trim()));
        }
    }

    if (dados.length > linhas * colunas) {
        console.error("Erro: quantidade de dados maior que a capacidade da tabela.");
        continue;
    }

    let contadorDados = 0;

    let bordaAttr = tab.getAttribute("borda");
    if (bordaAttr) {
        let vetBorda = bordaAttr.split(" ");
        novaTabela.style.setProperty('--tamanho-borda', vetBorda[0]);
        novaTabela.style.setProperty('--tipo-borda', vetBorda[1]);
        novaTabela.style.setProperty('--cor-borda', vetBorda[2]);
    }

    let matrizRowspan = [];

    // Construção da tabela
    for (let x = 0; x < linhas; x++) {
        let tr = document.createElement("tr");

        for (let y = 0; y < colunas; y++) {

            // Verifica se exatamente nessa posicao ha um expand
            let pular = matrizRowspan.some(pos => pos[0] === x && pos[1] === y);
            if (pular) continue;

            let td = document.createElement("td");
            let span = 1;
            let tipo = null;

            // Verifica se há expansão
            for (let k = 0; k < matrizExpand.length; k++) {
                if (matrizExpand[k][0] === x && matrizExpand[k][1] === y) {
                    span = matrizExpand[k][2];
                    tipo = matrizExpand[k][3];
                    break;
                }
            }

            // COLSPAN
            if (span > 1 && tipo === "coluna") {
                td.setAttribute("colspan", span);
                y += span - 1;
            }

            // ROWSPAN
            else if (span > 1 && tipo === "linha") {
                td.setAttribute("rowspan", span);

                for (let c = 1; c < span; c++) {
                    matrizRowspan.push([x + c, y]);
                }
            }

            // Inserir dados (se existirem)
            if (contadorDados < dados.length) {
                td.textContent = dados[contadorDados];
                contadorDados++;
            }

            tr.appendChild(td);
        }

        novaTabela.appendChild(tr);
    }

    tab.appendChild(novaTabela);
}