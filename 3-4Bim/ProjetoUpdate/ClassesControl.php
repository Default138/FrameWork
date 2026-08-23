<?php
require_once ("utils.php");
class ClassesControl{
private $entidades;
    private $caminho = "sistema/control/";
    function __construct($e) {
       if (!is_dir($this->caminho)) {
           mkdir($this->caminho, 7777, true);
        }
        $this->entidades = $e;
        $this->criaClasse();
    }
    function criaClasse() {
            $util = new Utils();
            $listaEntidades = array_keys($this->entidades);
            foreach ($listaEntidades as $entidade) {
                $listaAtributos = $this->entidades[$entidade];
                $instancia = "";
                foreach ($listaAtributos as $key => $atributo) {
                    if(!$atributo["primary"])
                    $instancia.="\$this->obj->set".ucfirst($key)."(\$_POST[\"$key\"]);\n\t";
               }
                $nomeClasse=ucfirst($entidade);
                $conteudo = <<<CLASS
                <?PHP
                require_once(__DIR__.'/../model/$entidade.php');
                 require_once(__DIR__.'/../dao/{$entidade}DAO.php');
                class {$nomeClasse}Control {
                   private \$obj;
                   private \$dao;
                   private \$acao;
                   public function __construct() {
                       \$this->obj=new {$nomeClasse}();
                       \$this->dao=new {$nomeClasse}DAO();
                       \$this->acao=\$_REQUEST["acao"] ?? null;
                      \$this->executaAcao();
                   }
                   public function executaAcao() {
                   switch(\$this->acao) {
                          case 1:
                          \$this->prepararObjeto();
                          \$this->dao->inserir( \$this->obj);
                          header("Location: ../view/index.php");
                          exit;
                          break;
                          case 2:
                          return \$this->dao->listar();
                          case 3:
                          \$this->dao->excluir(\$_REQUEST["id"]);
                          break;
                          case 4:
                          \$this->prepararObjeto();
                      }
                   }
                   public function prepararObjeto() {
                      $instancia
                   }
                }
                new {$nomeClasse}Control;
                ?>
                CLASS;
                file_put_contents("{$this->caminho}{$entidade}Control.php", $conteudo);
        }
    }
}
?>