const form = document.getElementsByTagName("form")[0];
let x=1;

function novoCampo(){
    let label= document.createElement("label");
    label.setAttribute("for","rotulo"+x);
    label.innerText="Rótulo";

    let input= document.createElement("input");
    input.setAttribute("type","text");
    input.setAttribute("name","rotulo"+x);

    let label1= document.createElement("label");
    label1.setAttribute("for","tipo"+x);
    label1.innerText=" Tipo ";

    let select = document.createElement("select");
    select.setAttribute("name","tipo"+x);
    //ADICIONADO: onchange para mostrar/esconder campo de opções
    select.setAttribute("onchange","toggleOpcoes(this,"+x+")");
    let vet ={
        text:"Texto",
        number:"Número",
        date:"Data",
        select:"Opções (Select)",   //ADICIONADO: <Select>
        textarea:"Área de texto"    //ADICIONADO <TextArea>
    }
    for(let chave in vet){
        let opt=document.createElement("option");
        opt.value=chave;
        opt.innerText=vet[chave];
        select.appendChild(opt);
    }

    ///ADICIONADO: span oculto com input de opções para quando tipo=select
    let span = document.createElement("span");
    span.id = "opcoes-wrapper"+x;
    span.style.display = "none";
    let lblOpcoes = document.createElement("label");
    lblOpcoes.innerText = " Opções (separadas por vírgula): ";
    let inputOpcoes = document.createElement("input");
    inputOpcoes.type = "text";
    inputOpcoes.name = "opcoes"+x;
    inputOpcoes.placeholder = "ex: Sim,Não,Talvez";
    span.appendChild(lblOpcoes);
    span.appendChild(inputOpcoes);

    x++;
    let br= document.createElement("br");
    form.appendChild(label);
    form.appendChild(input);
    form.appendChild(label1);
    form.appendChild(select);
    form.appendChild(span);  //span
    form.appendChild(br);
}

//ADICIONADO: mostra/esconde o campo de opções quando tipo "select" é escolhido
function toggleOpcoes(selectEl, idx){
    let wrapper = document.getElementById("opcoes-wrapper"+idx);
    if(selectEl.value === "select"){
        wrapper.style.display = "inline";
    } else {
        wrapper.style.display = "none";
    }
}

function visualizarForm(){
    const iframe=document.getElementById("preview");
    let doc= iframe.contentDocument || iframe.contentWindow.document;
    let html="<form>";
    for(let i=0;i<x;i++){
        let rotulo = document.getElementsByName("rotulo"+i)[0].value;
        let tipo = document.getElementsByName("tipo"+i)[0].value;
        html+=`<label>${rotulo}</label>`;
        if(tipo === "select"){
            //ADICIONADO: lê as opções digitadas e gera um <select> real
            let opcoesInput = document.getElementsByName("opcoes"+i)[0];
            let opcoes = opcoesInput && opcoesInput.value
                ? opcoesInput.value.split(",")
                : ["opção 1"];
            html += `<select name="${rotulo}">`;
            opcoes.forEach(op => {
                html += `<option>${op.trim()}</option>`;
            });
            html += `</select><br>`;
        } else if(tipo === "textarea"){
            //ADICIONADO: gera um <textarea>
            html += `<textarea name="${rotulo}"></textarea><br>`;
        } else {
            html+=`<input type="${tipo}" name="${rotulo}"><br>`
        }
    }
    console.log(html);
    html+="</form>";
    doc.open();
    doc.write(html);
    doc.close();
}
