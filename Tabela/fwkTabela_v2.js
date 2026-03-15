let tabelas = document.getElementsByTagName("tabela");

for (let i = 0; i < tabelas.length; i++) {
    let tab = tabelas[i];
    let linhas = parseInt(tab.getAttribute("linha"));
    let colunas = parseInt(tab.getAttribute("coluna"));
    let novaTabela = document.createElement("table");
    
    let expands = tab.getElementsByTagName("expand");
    let expandsInfo = [];
    
    for (let e = 0; e < expands.length; e++) {
        expandsInfo.push({
            linha: parseInt(expands[e].getAttribute("linha")),
            coluna: parseInt(expands[e].getAttribute("coluna")),
            tamanho: parseInt(expands[e].getAttribute("tamanho")),
            tipo: expands[e].getAttribute("tipo")
        });
    }

    let bordaAttr = tab.getAttribute("borda");
    if (bordaAttr) {
        let vetBorda = bordaAttr.split(" ");
        novaTabela.style.setProperty('--tamanho-borda', vetBorda[0]);
        novaTabela.style.setProperty('--tipo-borda', vetBorda[1]);
        novaTabela.style.setProperty('--cor-borda', vetBorda[2]);
    }

    let ocupado = Array(linhas).fill().map(() => Array(colunas).fill(false));
    
    for (let exp of expandsInfo) {
        if (exp.tipo === "linha") {
            for (let l = 1; l < exp.tamanho; l++) {
                if (exp.linha + l < linhas) {
                    ocupado[exp.linha + l][exp.coluna] = true;
                }
            }
        }
    }

    for (let linha = 0; linha < linhas; linha++) {
        let tr = document.createElement("tr");

        for (let coluna = 0; coluna < colunas; coluna++) {
            if (!ocupado[linha][coluna]) {
                let td = document.createElement("td");
                let expandAtual = expandsInfo.find(e => e.linha === linha && e.coluna === coluna);

                if (expandAtual) {
                    if (expandAtual.tipo === "coluna") {
                        td.setAttribute("colspan", expandAtual.tamanho);
                    } else if (expandAtual.tipo === "linha") {
                        td.setAttribute("rowspan", expandAtual.tamanho);
                    }
                }
            }

            tr.appendChild(td);
        }
        
        if (tr.children.length > 0) {
            novaTabela.appendChild(tr);
        }
    }
    
    tab.appendChild(novaTabela);
}