<?php
/**
 * Aquisição de amostras (fora do framework): converte tabela do MySQL em
 * vetor unidimensional (coluna) ou matriz associativa (grupo => vetor).
 */
class AmostraDados {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function listarTabelas(): array {
        return $this->pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    }

    public function listarColunas(string $tabela): array {
        $this->validarTabela($tabela);
        return $this->pdo->query("SHOW COLUMNS FROM `$tabela`")->fetchAll(PDO::FETCH_COLUMN);
    }

    /** @return array vetor de float (valores vazios/não numéricos são descartados) */
    public function vetor(string $tabela, string $colunaValor): array {
        $this->validarColuna($tabela, $colunaValor);
        $linhas = $this->pdo->query("SELECT `$colunaValor` FROM `$tabela`")->fetchAll(PDO::FETCH_COLUMN);
        $vetor = [];
        foreach ($linhas as $x) {
            if ($this->numerico($x)) $vetor[] = (float)$x;
        }
        if (count($vetor) == 0) {
            throw new RuntimeException("Nenhum valor numérico encontrado na coluna '$colunaValor'.");
        }
        return $vetor;
    }

    /** @return array matriz associativa ['grupo' => [float, ...]] (registros sem grupo ou sem valor numérico são descartados) */
    public function matriz(string $tabela, string $colunaValor, string $colunaGrupo): array {
        $this->validarColuna($tabela, $colunaValor);
        $this->validarColuna($tabela, $colunaGrupo);
        $stmt = $this->pdo->query("SELECT `$colunaGrupo` AS grupo, `$colunaValor` AS valor FROM `$tabela`");
        $matriz = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $linha) {
            $grupo = trim((string)$linha['grupo']);
            if ($grupo === '' || !$this->numerico($linha['valor'])) continue;
            $matriz[$grupo][] = (float)$linha['valor'];
        }
        if (count($matriz) == 0) {
            throw new RuntimeException("Nenhum registro válido encontrado para a ANOVA.");
        }
        return $matriz;
    }

    /**
     * Converte ['grupo' => [valores]] no formato esperado pelo framework:
     * matriz[linha][coluna], uma coluna por grupo, completando com null os grupos menores.
     */
    public function paraColunas(array $porGrupo): array {
        $linhas = max(array_map('count', $porGrupo));
        $grupos = array_values($porGrupo);
        $matriz = [];
        for ($i = 0; $i < $linhas; $i++) {
            $linha = [];
            foreach ($grupos as $valores) {
                $linha[] = $valores[$i] ?? null;
            }
            $matriz[] = $linha;
        }
        return $matriz;
    }

    private function numerico($x): bool {
        return $x !== null && trim((string)$x) !== '' && is_numeric($x);
    }

    private function validarTabela(string $tabela): void {
        if (!in_array($tabela, $this->listarTabelas(), true)) {
            throw new InvalidArgumentException("Tabela '$tabela' não existe.");
        }
    }

    private function validarColuna(string $tabela, string $coluna): void {
        if (!in_array($coluna, $this->listarColunas($tabela), true)) {
            throw new InvalidArgumentException("Coluna '$coluna' não existe na tabela '$tabela'.");
        }
    }
}
