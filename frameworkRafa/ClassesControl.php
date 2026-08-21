<?php
require_once ("utils.php");
class ClassesControl{
private $entidades;
    private $caminho = "sistema/control/";
    function __construct($e) {
       if (!is_dir($this->caminho)) {
           mkdir($this->caminho, 0777, true);
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
                require_once('../model/$entidade.php');
                require_once('../dao/{$entidade}DAO.php');
                require_once('../config/Database.php');
                class {$nomeClasse}Control {
                   private \$obj;
                   private \$dao;
                   private \$acao;
                   public function __construct() {
                       \$this->obj=new {$nomeClasse}();
                       \$this->acao=\$_REQUEST["acao"];
                       \$pdo = Database::getConexao();
                       \$this->dao = new {$nomeClasse}DAO(\$pdo);
                       \$this->executaAcao();
                   }
                   public function executaAcao() {
                       switch(\$this->acao) {
                           case 1:
                               \$this->prepararObjeto();
                               break;
                           case 2:
                               \$this->listarTodos();
                               break;
                       }
                   }
                   public function prepararObjeto() {
                      $instancia
                   }
                   public function listarTodos() {
                       \$lista{$nomeClasse} = \$this->dao->listar();
                       include('../view/lista_{$entidade}.php');
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
