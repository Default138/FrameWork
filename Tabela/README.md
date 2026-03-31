# Tabelas

Tabelas eh um framework criado em ambiente escolar, com o intuito de melhor aprender conceitos de criacao de frameworks e como eles funcionam alem da linguagem de javascript.

## Uso

```html
    <tabela linha='4' coluna='2' borda='1px dotted blue'>
     <!-- cria uma tabela de 4 linhas, com 2 colunas cada. Estilizada com contorno azul com 1 pixel de grossura, pontilhado -->

    <!-- valores aceitos:
        Linha -> Qualquer numero inteiro maior que 0
        Coluna -> Qualquer numero inteiro maior que 0
        Borda -> Grossura, tipo, cor; Padrao css para border
    -->

        <expand linha='3' coluna='0' tamanho='2' tipo='coluna'> </expand>
        <!-- Na quarta linha (veja: a posicao 0 em um array e sempre a primeira), na primeira coluna, uma celula toma o tamanho de duas, na orientacao horizontal  -->

        <!-- valores aceitos:
            Linha -> Um numero inteiro maior ou igual a 0 e menor que o numero de linhas da tabela
            Coluna -> Um numero inteiro maior ou igual 0 e menor que o numero de colunas na tabela
            Tipo -> Coluna (expansao horizontal) ou Linha (expansao vertical)
        -->

        <dados>
            Alunos | 3o TDS | Nome | Idade | Anacleto | 10
        </dados>
        <!-- insere na tabela os valores: 
        [Alunos][3o TDS]
        [Nome][Idade]
        [Anacleto][10]
        -->

        <!-- valores aceitos:
            Qualquer tipo de texto, contanto que entre uma coluna e outra se insira o caracter '|'
        -->
    </tabela>

```