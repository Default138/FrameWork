<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once ("classesModel.php");
require_once ("classesView.php");
require_once ("classesControl.php");
require_once ("classesDAO.php");
require_once ("classesConexao.php");

class LeitorSQL
{
    private string $conteudo;
    private array $tabelas = [];
    private array $banco = [];
    private array $host = [];

    public function receberArquivoSQL(string $arquivo)
    {
        if (!file_exists($arquivo)) {
            throw new Exception("Arquivo não encontrado.");
        }
        $this->conteudo = file_get_contents($arquivo);
        $this->processarTabelas();
    }

    private function processarTabelas(): void
    {
        preg_match(
            '/--\s*Host:\s*([^\r\n]+)/',
            $this->conteudo,
            $this->host
        );
        preg_match(
            '/Banco de dados:\s*`([^`]+)`/',
            $this->conteudo,
            $this->banco
        );
        preg_match_all(
            '/CREATE TABLE\s+`([^`]+)`\s*\((.*?)\)\s*ENGINE=/s',
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
                if (strpos($linha, '`') !== 0) {
                    continue;
                }
                preg_match('/`(.+?)`\s+([a-zA-Z0-9()]+)/', $linha, $campo);
                $nomeCampo = $campo[1] ?? '';
                $tipoCampo = $campo[2] ?? '';
                if ($nomeCampo === '') continue;
                $nullable = !str_contains($linha, 'NOT NULL');
                $this->tabelas[$nomeTabela][$nomeCampo] = [
                    'tipo' => $tipoCampo,
                    'primary' => false,
                    'nullable' => $nullable
                ];
            }
        }

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

        preg_match_all(
            '/ALTER TABLE `([^`]+)`\s+ADD CONSTRAINT `[^`]+`\s+
            FOREIGN KEY \(`([^`]+)`\)\s+REFERENCES `([^`]+)` \(`([^`]+)`\)/',
            $this->conteudo,
            $estrangeira,
            PREG_SET_ORDER
        );
    }

    function iniciar(): void
    {
        $arquivo = $_FILES['arquivo'];
        $arquivo_tmp = $arquivo['tmp_name'];
        $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        if ($extensao != "sql") {
            header("location: index.php?erro=0");
            exit;
        } else {
            $diretorioSql = __DIR__ . DIRECTORY_SEPARATOR . "sql";
            if (!is_dir($diretorioSql)) {
                mkdir($diretorioSql, 0777, true);
            }
            $destino = $diretorioSql . DIRECTORY_SEPARATOR . $arquivo['name'];
            move_uploaded_file($arquivo_tmp, $destino);
            $this->receberArquivoSQL($destino);
            new ClassesModel($this->tabelas);
            new ClassesView($this->tabelas);
            new ClassesControl($this->tabelas);
            new ClassesDAO($this->tabelas);
            new ClassesConexao($this->banco[1] ?? '', $this->host[1] ?? '');
            echo "Processamento concluído com sucesso!";
        }
    }
}
(new LeitorSQL())->iniciar();
?>
