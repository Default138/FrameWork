<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once ("ClassesModel.php");
require_once ("ClassesView.php");
require_once ("ClassesControl.php");
require_once ("ClassesDAO.php");
require_once ("ClassesConexao.php");
class LeitorSQL
{
    private $conteudo;
    private $tabelas = [];
    private $banco;
    private $host;
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
                    //  if (!str_starts_with($linha, '`')) {
                    continue;
                }
                // linha=`id` int(11) NOT NULL,
                preg_match('/`(.+?)`\s+([a-zA-Z0-9()]+)/', $linha, $campo);
                $nomeCampo = $campo[1] ?? '';
                $tipoCampo = $campo[2] ?? '';
                $this->tabelas[$nomeTabela][$nomeCampo] = [
                    'tipo' => $tipoCampo,
                    'primary' => false
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

 function  iniciar()
  {
      $arquivo = $_FILES['arquivo'];
      $arquivo_tmp = $arquivo['tmp_name'];
      $arquivo_size = $arquivo['size'];
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
          new ClassesConexao($this->banco[1],$this->host[1]);
          header("location: sistema/view/index.php?sucesso=1");
          exit;
      }
  }
}
(new LeitorSQL())->iniciar();
?>