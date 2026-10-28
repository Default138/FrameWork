# Framework de Estatística (PHP)

Framework com dois serviços independentes: **Estatística Básica** e **ANOVA de um fator**.

## Estrutura

```
framework/fwkEstatistica.php   -> classe com os cálculos (o framework em si, sem texto/tela)
framework/utils.php            -> cálculo do F-crítico (arquivo do professor)
sistema/dados/AmostraDados.php -> aquisição dos dados: tabela -> vetor / matriz (fora do framework)
sistema/control/EstatisticaControl.php -> conexão PDO, escolha do serviço e textos de interpretação
sistema/view/index.php         -> tela (menu de serviço, tabela e colunas)
banco.sql                      -> criação e carga do banco de testes
teste_framework.php            -> teste sem banco, com os dados do enunciado
```

O framework **não acessa banco** nem conhece tabelas/colunas: recebe apenas um vetor
(coluna) ou uma matriz associativa (tabela agrupada).

## Como configurar e executar

1. Inicie Apache e MySQL no XAMPP e execute `banco.sql` (phpMyAdmin > Importar).
2. Ajuste usuário/senha/banco no construtor de `sistema/control/EstatisticaControl.php`, se necessário.
3. Acesse `http://localhost/Camargo/Framework/4Bim/ANOVA/Projeto/sistema/view/index.php`.
4. Escolha o serviço, a tabela e as colunas e clique em **Calcular**.
   - Estatística Básica: tabela + coluna de valores.
   - ANOVA: tabela + coluna de valores + coluna de grupos.

Teste sem banco: `php teste_framework.php`.

## Reutilização com outros dados

O `banco.sql` traz três tabelas: `notas` (turma, nota), `producao` (maquina, pecas — contém um NULL
para mostrar o tratamento de vazios) e `temperaturas` (sala, graus). Todas funcionam na mesma tela, sem
alterar o framework. Para analisar uma tabela nova, basta criá-la no banco: ela aparece na lista.

## Uso direto da classe

O framework **não escreve na tela nem devolve texto**: recebe um vetor ou uma matriz e devolve um
vetor com os resultados em posições fixas. Quem usa decide o que fazer com os números (inclusive os
textos de interpretação, que no exemplo ficam em `EstatisticaControl`).

```php
require_once 'framework/fwkEstatistica.php';
$fwk = new fwkEstatistica();
$r = $fwk->estatisticaBasica([7.5, 6.0, 8.0]);
echo $r[fwkEstatistica::MEDIA];          // ou $r[0]
$a = $fwk->anova([[1, 4], [2, 5], [3, 6]]);   // coluna 0 = grupo A, coluna 1 = grupo B
```

## Documentação das funções

Lançam `InvalidArgumentException` para dados insuficientes ou valor não numérico (aceita `int`/`float`).

### `estatisticaBasica(array $v): array`
- **Parâmetro:** vetor unidimensional de números, mínimo 2 valores.
- **Retorno:** vetor com 6 posições (constantes entre parênteses):

| Posição | Valor | Tipo |
|---|---|---|
| 0 (`MEDIA`) | média aritmética | float |
| 1 (`MEDIANA`) | mediana | float |
| 2 (`MODA`) | modas em ordem crescente; `[]` se amodal | float[] |
| 3 (`DESVIO_PADRAO`) | desvio-padrão amostral (n-1) | float |
| 4 (`VARIANCIA`) | variância amostral (n-1) | float |
| 5 (`CV`) | coeficiente de variação em %; `null` se a média for 0 | float\|null |

### `anova(array $matriz, float $alfa = 0.05): array`
- **Parâmetro `$matriz`:** `$matriz[linha][coluna]`. **Cada COLUNA é um grupo** e cada linha uma
  observação. Grupos menores devem ser completados com `null` no fim da coluna. Todas as linhas
  precisam ter o mesmo número de colunas. Mínimo 2 grupos e mais observações que grupos.
- **Parâmetro `$alfa`:** nível de significância (padrão 0.05).
- **Retorno:** vetor com 10 posições:

| Posição | Valor | Tipo |
|---|---|---|
| 0 (`MEDIAS_GRUPOS`) | média de cada grupo, na ordem das colunas | float[] |
| 1 (`MEDIA_GERAL`) | média geral | float |
| 2 (`SQ_ENTRE`) | soma dos quadrados entre grupos | float |
| 3 (`SQ_DENTRO`) | soma dos quadrados dentro dos grupos | float |
| 4 (`GL_ENTRE`) | graus de liberdade entre | int |
| 5 (`GL_DENTRO`) | graus de liberdade dentro | int |
| 6 (`QM_ENTRE`) | quadrados médios entre | float |
| 7 (`QM_DENTRO`) | quadrados médios dentro | float |
| 8 (`F`) | estatística F; `null` se `QM_DENTRO` = 0 | float\|null |
| 9 (`F_CRITICO`) | F-crítico para `$alfa` | float |

A comparação entre `F` e `F_CRITICO` (e o texto da conclusão) fica a cargo de quem usa o framework.

`calcularFCritico(int $gl1, int $gl2, float $alpha = 0.05)` (em `utils.php`) retorna `float`.

## Tratamento de dados (camada de aquisição)

`AmostraDados` descarta valores vazios, não numéricos e registros sem grupo; lança erro se a tabela ou
coluna não existir (nomes validados contra o banco, evitando SQL injection) ou se não sobrar nenhum dado.
