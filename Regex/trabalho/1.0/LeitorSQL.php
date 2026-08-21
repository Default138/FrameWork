<?php

class LeitorSQL
{
    private $conteudo;
    private $tabelas = [];

    public function __construct($arquivo)
    {
        if (!file_exists($arquivo)) {
            throw new Exception("Arquivo não encontrado.");
        }

        $this->conteudo = file_get_contents($arquivo);

        $this->processarTabelas();
    }

    /**
     * Processa as tabelas do arquivo SQL
     */
    private function processarTabelas()
    {
        preg_match_all(
            '/CREATE TABLE `(.+?)` \((.*?)\)\s*ENGINE=/s',
            $this->conteudo,
            $matches,
            PREG_SET_ORDER
        );

        foreach ($matches as $match) {

            $nomeTabela = $match[1];
            $camposTexto = $match[2];

            $this->tabelas[$nomeTabela] = [];

            $linhas = explode("\n", $camposTexto);

            foreach ($linhas as $linha) {

                $linha = trim($linha);

                // ignora linhas que não são atributos
                if (strpos($linha, '`') !== 0) {
              //  if (!str_starts_with($linha, '`')) {
                    continue;
                }

                preg_match('/`(.+?)`\s+([a-zA-Z0-9()]+)/', $linha, $campo);

                $nomeCampo = $campo[1] ?? '';
                $tipoCampo = $campo[2] ?? '';

                $this->tabelas[$nomeTabela][$nomeCampo] = [
                    'tipo' => $tipoCampo,
                    'primary' => false
                ];
            }
        }

        // procura as chaves primárias
        preg_match_all(
            '/ALTER TABLE `(.+?)`(.*?)ADD PRIMARY KEY \(`(.+?)`\)/s',
            $this->conteudo,
            $primaryMatches,
            PREG_SET_ORDER
        );

        foreach ($primaryMatches as $match) {

            $tabela = $match[1];
            $campoPK = $match[3];

            if (isset($this->tabelas[$tabela][$campoPK])) {
                $this->tabelas[$tabela][$campoPK]['primary'] = true;
            }
        }
    }

    /**
     * Retorna os nomes das tabelas
     */
    public function getTabelas()
    {
        return array_keys($this->tabelas);
    }

    /**
     * Retorna os atributos da tabela
     */
    public function getAtributos($tabela)
    {
        if (!isset($this->tabelas[$tabela])) {
            return [];
        }

        return $this->tabelas[$tabela];
    }
}

?>