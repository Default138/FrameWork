<?php
require_once(__DIR__ . '/utils.php');

/**
 * Framework de Estatística: Estatística Básica e ANOVA de um fator.
 * Não acessa banco, não escreve na tela e não devolve texto: recebe um vetor
 * ou uma matriz e devolve um vetor com os resultados em posições fixas.
 * Quem usa o framework decide o que fazer com os dados.
 */
class fwkEstatistica {

    // Posições do vetor devolvido por estatisticaBasica()
    const MEDIA = 0;
    const MEDIANA = 1;
    const MODA = 2;
    const DESVIO_PADRAO = 3;
    const VARIANCIA = 4;
    const CV = 5;

    // Posições do vetor devolvido por anova()
    const MEDIAS_GRUPOS = 0;
    const MEDIA_GERAL = 1;
    const SQ_ENTRE = 2;
    const SQ_DENTRO = 3;
    const GL_ENTRE = 4;
    const GL_DENTRO = 5;
    const QM_ENTRE = 6;
    const QM_DENTRO = 7;
    const F = 8;
    const F_CRITICO = 9;

    /**
     * Calcula os indicadores da Estatística Básica.
     *
     * @param array $v vetor unidimensional de números (int|float), mínimo 2 valores.
     * @return array vetor com 6 posições:
     *   [0] float   média aritmética
     *   [1] float   mediana
     *   [2] float[] modas em ordem crescente (array vazio se todos os valores se repetem igual)
     *   [3] float   desvio-padrão amostral (divisor n-1)
     *   [4] float   variância amostral (divisor n-1)
     *   [5] float|null coeficiente de variação em % (null quando a média é zero)
     * @throws InvalidArgumentException vetor com menos de 2 valores ou valor não numérico
     */
    public function estatisticaBasica(array $v): array {
        $this->validarVetor($v, 2, "vetor");
        $n = count($v);
        $media = array_sum($v) / $n;

        $soma = 0.0;
        foreach ($v as $x) {
            $soma += pow($x - $media, 2);
        }
        $variancia = $soma / ($n - 1);
        $desvio = sqrt($variancia);
        $cv = ($media == 0) ? null : ($desvio / abs($media)) * 100;

        return [$media, $this->mediana($v), $this->moda($v), $desvio, $variancia, $cv];
    }

    /**
     * ANOVA de um fator.
     *
     * @param array $matriz matriz (array de linhas) em que cada COLUNA é um grupo e cada LINHA é
     *   uma observação: $matriz[linha][coluna]. Grupos com menos observações que os outros devem
     *   ser completados com null no fim da coluna (null é ignorado).
     *   Exemplo: [[7.5, 8.5, 5.0], [6.0, 9.0, 6.5], ...] -> coluna 0 = turma A, 1 = turma B...
     *   Mínimo de 2 colunas e mais observações do que grupos.
     * @param float $alfa nível de significância (padrão 0.05)
     * @return array vetor com 10 posições:
     *   [0] float[] média de cada grupo, na ordem das colunas
     *   [1] float   média geral
     *   [2] float   soma dos quadrados entre grupos
     *   [3] float   soma dos quadrados dentro dos grupos
     *   [4] int     graus de liberdade entre grupos
     *   [5] int     graus de liberdade dentro dos grupos
     *   [6] float   quadrados médios entre grupos
     *   [7] float   quadrados médios dentro dos grupos
     *   [8] float|null estatística F (null quando o quadrado médio dentro é zero)
     *   [9] float   F-crítico para o nível de significância informado
     * @throws InvalidArgumentException dados insuficientes, linhas de tamanhos diferentes,
     *   coluna sem valores ou valor não numérico
     */
    public function anova(array $matriz, float $alfa = 0.05): array {
        $grupos = $this->colunas($matriz);
        $k = count($grupos);
        if ($k < 2) {
            throw new InvalidArgumentException("A ANOVA exige pelo menos 2 grupos (colunas).");
        }

        $medias = [];
        $soma = 0.0;
        $total = 0;
        foreach ($grupos as $i => $valores) {
            $this->validarVetor($valores, 1, "grupo (coluna) " . ($i + 1));
            $medias[$i] = array_sum($valores) / count($valores);
            $soma += array_sum($valores);
            $total += count($valores);
        }
        if ($total <= $k) {
            throw new InvalidArgumentException("Dados insuficientes: é preciso ter mais observações do que grupos.");
        }

        $mediaGeral = $soma / $total;
        $sqEntre = 0.0;
        $sqDentro = 0.0;
        foreach ($grupos as $i => $valores) {
            $sqEntre += count($valores) * pow($medias[$i] - $mediaGeral, 2);
            foreach ($valores as $x) {
                $sqDentro += pow($x - $medias[$i], 2);
            }
        }

        $glEntre = $k - 1;
        $glDentro = $total - $k;
        $qmEntre = $sqEntre / $glEntre;
        $qmDentro = $sqDentro / $glDentro;
        $f = ($qmDentro > 0) ? $qmEntre / $qmDentro : null;
        $fCritico = calcularFCritico($glEntre, $glDentro, $alfa);

        return [$medias, $mediaGeral, $sqEntre, $sqDentro, $glEntre, $glDentro, $qmEntre, $qmDentro, $f, $fCritico];
    }

    // ---------- Apoio ----------

    private function mediana(array $v): float {
        sort($v);
        $n = count($v);
        $meio = intdiv($n, 2);
        return ($n % 2 == 1) ? (float)$v[$meio] : ($v[$meio - 1] + $v[$meio]) / 2;
    }

    private function moda(array $v): array {
        $freq = [];
        foreach ($v as $x) {
            $chave = (string)(float)$x;
            $freq[$chave] = ($freq[$chave] ?? 0) + 1;
        }
        $max = max($freq);
        if ($max == min($freq)) {
            return [];
        }
        $modas = [];
        foreach ($freq as $valor => $qtd) {
            if ($qtd == $max) $modas[] = (float)$valor;
        }
        sort($modas);
        return $modas;
    }

    // transforma a matriz (colunas = grupos) em um vetor de vetores, um por grupo, sem os null
    private function colunas(array $matriz): array {
        if (count($matriz) == 0) {
            throw new InvalidArgumentException("Matriz vazia.");
        }
        $grupos = [];
        $tamanho = null;
        foreach ($matriz as $linha) {
            if (!is_array($linha)) {
                throw new InvalidArgumentException("A matriz deve ser um array de linhas (arrays).");
            }
            $linha = array_values($linha);
            if ($tamanho === null) {
                $tamanho = count($linha);
            } elseif (count($linha) != $tamanho) {
                throw new InvalidArgumentException("Todas as linhas da matriz devem ter o mesmo número de colunas.");
            }
            foreach ($linha as $c => $x) {
                if ($x !== null) $grupos[$c][] = $x;
                else $grupos[$c] = $grupos[$c] ?? [];
            }
        }
        return array_values($grupos);
    }

    private function validarVetor(array $v, int $minimo, string $rotulo): void {
        if (count($v) < $minimo) {
            throw new InvalidArgumentException("Dados insuficientes no $rotulo: mínimo de $minimo valor(es).");
        }
        foreach ($v as $x) {
            if (!is_int($x) && !is_float($x)) {
                throw new InvalidArgumentException("Valor não numérico encontrado no $rotulo.");
            }
        }
    }
}
