document.addEventListener("DOMContentLoaded", function () {
    const tabela = document.querySelector("tabela");
    if (!tabela) return;

    const cabecalho = tabela.querySelector("cabecalho");
    const dadosEls = tabela.querySelectorAll("dados");

    const table = document.createElement("table");
    table.border = tabela.getAttribute("borda") || "1";

    if (cabecalho) {
        const thead = document.createElement("thead");
        const tr = document.createElement("tr");
        cabecalho.textContent.split(",").forEach(function (col) {
            const th = document.createElement("th");
            th.textContent = col.trim();
            tr.appendChild(th);
        });
        thead.appendChild(tr);
        table.appendChild(thead);
    }

    const tbody = document.createElement("tbody");
    dadosEls.forEach(function (dado) {
        const tr = document.createElement("tr");
        dado.textContent.split(",").forEach(function (col) {
            const td = document.createElement("td");
            td.textContent = col.trim();
            tr.appendChild(td);
        });
        tbody.appendChild(tr);
    });
    table.appendChild(tbody);

    tabela.replaceWith(table);
});