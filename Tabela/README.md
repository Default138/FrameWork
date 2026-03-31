COMO FUNCIONA O FRAMEWORK DE TABELAS
=====================================

O QUE É ESTE FRAMEWORK?
----------------------

Este é um programa que ajuda você a criar tabelas de forma mais simples e rápida. 
Em vez de escrever várias linhas de código HTML para fazer uma tabela, você pode 
usar nossas tags personalizadas e o programa faz todo o trabalho pesado para você.

IMPORTANTE: Para que funcione, você precisa incluir o arquivo do framework 
(fwkTabela2.0.js) no final da sua página HTML, antes de fechar a tag </body>.

COMO CRIAR UMA TABELA SIMPLES?
-----------------------------

Para criar uma tabela, você usa a tag <tabela> com os seguintes atributos:

<tabela linha="4" coluna="2">
</tabela>

Isso cria uma tabela com 4 linhas e 2 colunas. A tabela será criada vazia.

COMO COLOCAR DENTRO DA TABELA?
-----------------------------

Você pode adicionar conteúdo usando a tag <dados>. Cada linha de dados é separada 
por uma quebra de linha, e cada coluna dentro da linha é separada pelo símbolo | 
(pipe). Exemplo:

<tabela linha="3" coluna="2">
    <dados>
        Nome | Idade
        João | 25
        Maria | 30
    </dados>
</tabela>

Isso criará uma tabela assim:
[ Nome  ] [ Idade ]
[ João  ] [ 25    ]
[ Maria ] [ 30    ]

Onde os dados são lidos na ordem: primeira linha da tabela recebe a primeira 
linha de dados, e assim por diante.

COMO MISTURAR CÉLULAS (JUNTAR CÉLULAS)?
--------------------------------------

Às vezes você quer que uma célula ocupe mais de um espaço, seja na horizontal 
(juntar células ao lado) ou na vertical (juntar células em cima e embaixo). 
Para isso usamos a tag <expand>.

Juntar células na horizontal (colspan):
---------------------------------------
<expand linha="0" coluna="0" tamanho="2" tipo="coluna"></expand>

Isso significa: na primeira linha (linha 0), na primeira coluna (coluna 0), 
a célula vai ocupar o espaço de 2 colunas.

Juntar células na vertical (rowspan):
-------------------------------------
<expand linha="0" coluna="0" tamanho="2" tipo="linha"></expand>

Isso significa: na primeira linha, na primeira coluna, a célula vai ocupar 
o espaço de 2 linhas.

ATENÇÃO: As posições começam no 0! Então:
- linha="0" = primeira linha
- linha="1" = segunda linha
- coluna="0" = primeira coluna
- coluna="1" = segunda coluna

COMO COLOCAR BORDA NA TABELA?
----------------------------

Você pode estilizar a borda da tabela usando o atributo borda:

<tabela linha="4" coluna="2" borda="1px solid black">
</tabela>

Os valores são exatamente como no CSS:
- A grossura: 1px, 2px, 3px, etc.
- O tipo: solid (linha contínua), dotted (pontilhado), dashed (tracejado)
- A cor: red, blue, black, #FF0000, etc.

Exemplos:
borda="2px dotted blue"   = borda azul pontilhada de 2 pixels
borda="1px solid red"     = borda vermelha contínua de 1 pixel

EXEMPLO COMPLETO
---------------

<tabela linha="4" coluna="3" borda="1px solid black">
    
    <!-- Esta célula vai ocupar 2 colunas na primeira linha -->
    <expand linha="0" coluna="0" tamanho="2" tipo="coluna"></expand>
    
    <!-- Esta célula vai ocupar 2 linhas na segunda coluna -->
    <expand linha="1" coluna="1" tamanho="2" tipo="linha"></expand>
    
    <dados>
        Título Principal | Extra
        Dado 1 | Dado 2
        Dado 3 | Dado 4
        Dado 5 | Dado 6
    </dados>
    
</tabela>

O QUE ACONTECE SE ALGO DAR ERRADO?
--------------------------------

O programa verifica alguns erros comuns:
- Se você não colocar linha ou coluna, aparece um erro
- Se você colocar mais dados do que espaço na tabela, aparece um erro
- Se uma expansão ultrapassar os limites da tabela, aparece um erro

Esses erros aparecem no console do navegador (pressione F12 e clique na aba 
"Console" para ver).

RESUMO DOS ATRIBUTOS
-------------------

Tag <tabela>:
- linha: número de linhas (obrigatório)
- coluna: número de colunas (obrigatório)
- borda: estilo da borda (opcional)

Tag <expand>:
- linha: posição da linha onde começa (0 = primeira)
- coluna: posição da coluna onde começa (0 = primeira)
- tamanho: quantos espaços vai ocupar
- tipo: "coluna" (horizontal) ou "linha" (vertical)

Tag <dados>:
- Texto livre, separando colunas com | e linhas com quebra de linha

PARA QUE SERVE ISSO?
-------------------

Este framework serve para facilitar a criação de tabelas em HTML. Em vez de 
escrever código complicado com <table>, <tr>, <td>, você usa tags simples e 
o programa cria toda a estrutura para você automaticamente.

É especialmente útil para quem está aprendendo ou para quem precisa criar 
muitas tabelas de forma rápida e organizada.