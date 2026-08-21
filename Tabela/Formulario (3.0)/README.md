# 🛠️ Construtor de Formulários

Ferramenta simples que roda direto no navegador e permite criar formulários personalizados sem escrever código.

---

## 📁 Arquivos do projeto

| Arquivo      | Para que serve                                        |
|--------------|-------------------------------------------------------|
| `index.html` | A "tela" do programa — o que aparece no navegador.    |
| `script.js`  | O "cérebro" — toda a lógica de criar campos e gerar o formulário. |

> Para usar, basta abrir o `index.html` no navegador. Não precisa instalar nada.

---

## 🖥️ Como a tela inicial funciona

Ao abrir o arquivo, você verá:

```
┌─────────────────────────────────────────────────────┐
│  Crie seu formulário                                │
│                                                     │
│  [ Novo campo ]   [ Concluir ]                      │
│  ─────────────────────────────────────────────────  │
│  Rótulo [__________]  Tipo [Texto ▾]                │
│                                                     │
│  ┌ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ┐  │
│    Prévia do formulário aparece aqui após Concluir  │
│  └ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ┘  │
└─────────────────────────────────────────────────────┘
```

- **Novo campo** — adiciona mais um campo ao formulário.
- **Concluir** — gera a prévia do formulário na parte de baixo da página.
- O primeiro campo já aparece pronto quando a página abre.

---

## ✏️ Configurando um campo

Cada campo tem dois preenchimentos:

```
Rótulo [Nome completo]   Tipo [Texto ▾]
   ↑                          ↑
Nome que aparece          Como o usuário
no formulário             vai responder
```

**Rótulo** — o nome visível no formulário. Ex: `Nome completo`, `Data de nascimento`, `Cidade`.

**Tipo** — define o formato da resposta (veja a tabela abaixo).

---

## 📋 Tipos de campo disponíveis

| Tipo              | Quando usar                          | Exemplo de resposta     |
|-------------------|--------------------------------------|-------------------------|
| Texto             | Qualquer resposta em palavras        | João da Silva           |
| Número            | Resposta numérica                    | 42                      |
| Data              | Selecionar uma data no calendário    | 15/06/2025              |
| Opções (Select)   | Escolher uma opção de uma lista      | Sim / Não / Talvez      |
| Área de texto     | Respostas longas, observações        | Gostaria de informar... |

---

## ➕ Adicionando vários campos

Clique em **"Novo campo"** para cada campo que precisar. Os campos aparecem empilhados:

```
Rótulo [Nome   ]  Tipo [Texto  ▾]
────────────────────────────────────
Rótulo [Idade  ]  Tipo [Número ▾]
────────────────────────────────────
Rótulo [       ]  Tipo [Texto  ▾]  ← novo campo aparece aqui
```

> 💡 Você pode adicionar quantos campos quiser antes de clicar em "Concluir".

---

## 🔽 Usando o tipo "Opções (Select)"

Ao escolher **Opções (Select)**, um campo extra aparece automaticamente para você digitar as opções da lista:

```
Rótulo [Você prefere?]  Tipo [Opções (Select) ▾]  Opções [Café,Chá,Suco]
                                                         ↑
                                          Digite as opções separadas por vírgula
```

No formulário final, o usuário verá uma lista suspensa:

```
Você prefere?
┌─────────────┐
│ Café      ▾ │
├─────────────┤
│ Café        │  ← opções que
│ Chá         │     você digitou
│ Suco        │
└─────────────┘
```

**Como preencher as opções:** digite separado por vírgula, sem espaço. Exemplos:
- `Sim,Não`
- `Manhã,Tarde,Noite`
- `SP,RJ,MG,PR,RS`

---

## 📝 Usando o tipo "Área de texto"

Ideal para respostas longas — observações, descrições, comentários.

```
Observações
┌──────────────────────────────┐
│                              │
│  (espaço para escrever)      │
│                              │
└──────────────────────────── ↗│  ← pode arrastar para ampliar
```

> 💡 O usuário pode arrastar o canto inferior direito para deixar a área maior ou menor.

---

## 👁️ Visualizando o formulário pronto

Após configurar todos os campos, clique em **"Concluir"**:

```
ANTES (construtor)                   DEPOIS (prévia)
──────────────────                   ───────────────
Rótulo [Nome]   Tipo [Texto]         Nome
Rótulo [Cidade] Tipo [Texto]         [_______________]
                                     
[ Concluir ] ──────────────►         Cidade
                                     [_______________]
```

> ⚠️ A prévia é apenas para visualização. O formulário não envia dados a lugar nenhum — é um protótipo visual.

---

## 🚀 Exemplo completo passo a passo

Vamos criar um formulário de inscrição com nome, cidade, período preferido e observações.

**Passo 1 — Abra o arquivo**

Dê dois cliques em `index.html`. O navegador abre a ferramenta.

**Passo 2 — Preencha o primeiro campo**

- Rótulo: `Nome completo`
- Tipo: **Texto**

**Passo 3 — Adicione mais campos**

Clique em "Novo campo" e preencha assim:

| # | Rótulo            | Tipo            | Observação                       |
|---|-------------------|-----------------|----------------------------------|
| 1 | Nome completo     | Texto           | —                                |
| 2 | Cidade            | Texto           | —                                |
| 3 | Período preferido | Opções (Select) | Digitar: `Manhã,Tarde,Noite`     |
| 4 | Observações       | Área de texto   | —                                |

**Passo 4 — Visualize**

Clique em **"Concluir"** e veja o formulário aparecer:

```
Nome completo
[_________________________________]

Cidade
[_________________________________]

Período preferido
[ Manhã ▾ ]

Observações
┌─────────────────────────────────┐
│                                 │
└─────────────────────────────────┘
```

---

## 🧩 Como o código funciona (resumo simples)

Você não precisa mexer no código para usar a ferramenta. Mas se quiser entender:

| Ação do usuário              | O que o código faz                                                      |
|------------------------------|-------------------------------------------------------------------------|
| Página abre                  | Mostra o campo inicial que já está no HTML                              |
| Clica em "Novo campo"        | A função `novoCampo()` cria uma nova linha de inputs                    |
| Escolhe "Opções (Select)"    | A função `toggleOpcoes()` exibe o campo extra de opções                 |
| Clica em "Concluir"          | A função `visualizarForm()` lê tudo e monta o formulário no `<iframe>` |

---

*Construtor de Formulários · Guia do Usuário*
