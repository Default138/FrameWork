<?php
require_once ("classesModel.php");
require_once ("classesView.php");
require_once ("classesControl.php");
require_once ("classesDAO.php");
class LeitorSQL
{
    private $conteudo;
    private $tabelas = [];
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
          new classesModel($this->tabelas);
          new classesView($this->tabelas);
          new classesControl($this->tabelas);
          new classesDAO($this->tabelas);

      }
  }
}
(new LeitorSQL())->iniciar();
?>