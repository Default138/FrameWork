# FrameWork

Frameworks que construí em **PHP** durante o curso. Em cada um, o **framework** (que não conhece telas nem tabelas específicas) fica separado do **sistema** que o usa.

## O que tem aqui

| Pasta | O que é |
|---|---|
| `3Bim/Persistencia` | **Framework de persistência** (`fwkPersist`): a classe é marcada com atributos do PHP 8 (`#[Tabela]` e `#[Coluna]`) e o framework, usando Reflection, monta o SQL e faz `save`, `update`, `delete`, `listOne` e `listAll` por PDO. Tem entidades de exemplo e um sistema de biblioteca (livros) em MVC que usa o framework. |
| `3Bim/Opcional/ProjetoUpdate` | **Gerador de sistemas MVC**: você envia um arquivo `.sql` e ele interpreta as tabelas com expressões regulares e gera as classes **Model, View, Control e DAO**, com telas em Bootstrap e download do projeto em `.zip`. |
| `4Bim/Projeto` | **Framework de estatística**: Estatística Básica (média, mediana, moda, desvio-padrão, variância e coeficiente de variação) e ANOVA de um fator. O framework só recebe números; a tela e o acesso ao banco ficam no sistema. Tem README próprio. |
| `4Bim/ANOVA` | Cálculo de ANOVA passo a passo (`calculo.php`), com o cálculo do F crítico (`utils.php`) e o guia em PDF. |

## Como executar

Precisa do XAMPP (Apache e MySQL).

1. Copie a pasta do projeto que quer testar para `C:\xampp\htdocs\`.
2. Inicie o **Apache** e o **MySQL** no painel do XAMPP.
3. Importe o `.sql` do projeto no phpMyAdmin, quando houver (por exemplo, `4Bim/Projeto/banco.sql` ou `3Bim/Persistencia/sistema/biblioteca.sql`).
4. Abra o `index.php` do projeto pelo navegador.

Veja o README de `4Bim/Projeto` para as instruções do framework de estatística, e o `teste_framework.php` para testar sem banco de dados.

## Tecnologias

PHP 8 (atributos e Reflection), MySQL, PDO, MVC, DAO, Bootstrap
