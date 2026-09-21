<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("ClassesModel.php");
require_once("ClassesView.php");
require_once("ClassesControl.php");
require_once("ClassesDAO.php");
require_once("ClassesConexao.php");

class LeitorSQL
{
    private $conteudo;
    private $tabelas = [];
    private $banco = "framework";
    private $host = "localhost";

    public function receberArquivoSQL($arquivo)
    {
        if (!file_exists($arquivo)) {
            throw new Exception("Arquivo não encontrado.");
        }
        $this->conteudo = file_get_contents($arquivo);
        $this->processarTabelas();
    }

    private function processarTabelas()
    {
        if (preg_match('/(?:--\s*Host:|Host:\s*)\s*([^\r\n]+)/i', $this->conteudo, $m)) {
            $this->host = trim($m[1]);
        }
        if (preg_match('/(?:USE|Banco de dados:)\s*`?([a-zA-Z0-9_]+)`?/i', $this->conteudo, $m)) {
            $this->banco = trim($m[1]);
        }

        preg_match_all('/CREATE\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?`?([a-zA-Z0-9_]+)`?\s*\(([\s\S]*?)\n\s*\)/is', $this->conteudo, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $nomeTabela = $match[1];
            $camposTexto = $match[2];
            $this->tabelas[$nomeTabela] = [];

            $linhas = explode("\n", $camposTexto);

            foreach ($linhas as $linha) {
                $linha = trim($linha);

                if (empty($linha) || str_starts_with($linha, '--') || str_starts_with($linha, '/*')) {
                    continue;
                }

                if (preg_match('/PRIMARY\s+KEY\s*\(\s*`?([a-zA-Z0-9_]+)`?\s*\)/i', $linha, $pkMatch)) {
                    $pkCol = $pkMatch[1];
                    if (isset($this->tabelas[$nomeTabela][$pkCol])) {
                        $this->tabelas[$nomeTabela][$pkCol]['primary'] = true;
                    }
                    continue;
                }

                // CORREÇÃO: Ignora pedaços quebrados de constraints, como ON DELETE CASCADE e CHECK
                if (preg_match('/^(?:CONSTRAINT|KEY|UNIQUE|FOREIGN|PRIMARY|ON\s+DELETE|ON\s+UPDATE|REFERENCES|CHECK)\b/i', $linha)) {
                    continue;
                }

                if (preg_match('/^`?([a-zA-Z0-9_]+)`?\s+([a-zA-Z]+(?:\([^)]+\))?)/', $linha, $campo)) {
                    $nomeCampo = $campo[1];
                    $tipoCampo = $campo[2];
                    $isPrimary = false;

                    if (preg_match('/PRIMARY\s+KEY/i', $linha)) {
                        $isPrimary = true;
                    }

                    $this->tabelas[$nomeTabela][$nomeCampo] = [
                        'tipo' => $tipoCampo,
                        'primary' => $isPrimary
                    ];
                }
            }
        }

        preg_match_all('/ALTER\s+TABLE\s+`?([a-zA-Z0-9_]+)`?\s+ADD\s+PRIMARY\s+KEY\s*\(\s*`?([a-zA-Z0-9_]+)`?\s*\)/i', $this->conteudo, $primaryMatches, PREG_SET_ORDER);
        foreach ($primaryMatches as $match) {
            $tabela = $match[1];
            $campoPK = $match[2];
            if (isset($this->tabelas[$tabela][$campoPK])) {
                $this->tabelas[$tabela][$campoPK]['primary'] = true;
            }
        }
    }

    function iniciar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['arquivo'])) {
            $arquivo = $_FILES['arquivo'];
            $arquivo_tmp = $arquivo['tmp_name'];

            if (empty($arquivo_tmp)) {
                header("location: index.php?erro=0");
                exit;
            }

            $arquivo_name = explode('.', $arquivo['name']);
            $extensao = strtolower(end($arquivo_name));

            if ($extensao != "sql") {
                header("location: index.php?erro=0");
            } else {
                move_uploaded_file($arquivo_tmp, $arquivo["name"]);
                $this->receberArquivoSQL($arquivo["name"]);

                new ClassesModel($this->tabelas);
                new ClassesView($this->tabelas);
                new ClassesControl($this->tabelas);
                new ClassesDAO($this->tabelas);
                new ClassesConexao($this->banco, $this->host);

                header("location: sistema/view/index.php?sucesso=1");
                exit;
            }
        }
    }
}

(new LeitorSQL())->iniciar();
