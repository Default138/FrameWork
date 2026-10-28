<?php
require_once(__DIR__ . '/../../framework/fwkEstatistica.php');
require_once(__DIR__ . '/../dados/AmostraDados.php');

class EstatisticaControl {
    private PDO $pdo;
    private AmostraDados $dados;
    private fwkEstatistica $fwk;

    public function __construct() {
        $this->pdo = new PDO("mysql:host=localhost;dbname=estatistica;charset=utf8mb4", "root", ""); //conecta com o banco de dados
        $this->dados = new AmostraDados($this->pdo);
        $this->fwk = new fwkEstatistica();
    }

    public function tabelas(): array {
        return $this->dados->listarTabelas();
    }

    public function colunas(string $tabela): array {
        return $this->dados->listarColunas($tabela);
    }

    /** Executa somente o serviço escolhido ('basica' ou 'anova'). */
    public function executa(array $req): array {
        try {
            $servico = $req["servico"] ?? "";
            $tabela = $req["tabela"] ?? "";
            $valor = $req["coluna_valor"] ?? "";

            if ($servico == "basica") {
                $vetor = $this->dados->vetor($tabela, $valor);
                $r = $this->fwk->estatisticaBasica($vetor);
                return [
                    "servico" => "basica",
                    "n" => count($vetor),
                    "resultado" => $r,
                    "interpretacao" => $this->interpretarCV($r[fwkEstatistica::CV])
                ];
            }
            if ($servico == "anova") {
                $grupo = $req["coluna_grupo"] ?? "";
                $porGrupo = $this->dados->matriz($tabela, $valor, $grupo);
                $matriz = $this->dados->paraColunas($porGrupo);
                $r = $this->fwk->anova($matriz);
                return [
                    "servico" => "anova",
                    "nomes" => array_keys($porGrupo),
                    "n" => array_map('count', array_values($porGrupo)),
                    "resultado" => $r,
                    "conclusao" => $this->concluirAnova($r[fwkEstatistica::F], $r[fwkEstatistica::F_CRITICO])
                ];
            }
            return ["erro" => "Escolha um serviço."];
        } catch (Exception $e) {
            return ["erro" => $e->getMessage()];
        }
    }

    // Textos ficam no sistema (o framework só devolve números)
    private function interpretarCV(?float $cv): string {
        if ($cv === null) {
            return "O coeficiente de variação não pode ser calculado porque a média dos dados é zero.";
        }
        $cv = round($cv, 4);
        if ($cv < 30) {
            return "Os dados apresentam baixa variabilidade em relação à média, indicando que os valores são relativamente homogêneos.";
        }
        if ($cv > 30) {
            return "Os dados apresentam alta variabilidade em relação à média, indicando que os valores são relativamente heterogêneos.";
        }
        return "O coeficiente de variação está exatamente no limite de 30%, indicando uma variabilidade no limite estabelecido para esta análise.";
    }

    private function concluirAnova(?float $f, float $fCritico): string {
        if ($f === null) {
            return "Não foi possível calcular a estatística F, pois não há variação dentro dos grupos.";
        }
        if ($f > $fCritico) {
            return "Existem evidências estatísticas de diferença entre as médias de pelo menos dois grupos.";
        }
        return "Não foram encontradas evidências estatísticas suficientes para afirmar que as médias são diferentes.";
    }
}
