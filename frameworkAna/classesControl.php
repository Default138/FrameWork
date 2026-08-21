// classesControl.php
<?php
require_once ("utils.php");
class ClassesControl{
    private array $entidades;
    private string $caminho = "sistema/control/";
    function __construct(array $e) {
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
                $instancia.="\$this->obj->set".ucfirst($key)."(\$_POST[\"$key\"] ?? null);\n\t";
            }
            $nomeClasse=ucfirst($entidade);
            $conteudo = <<<CLASS
            <?PHP
            require_once('../model/$entidade.php');
             require_once('../dao/{$entidade}DAO.php');
            class {$nomeClasse}Control {
               private \$obj;
               private \$dao;
               private \$acao;
               public function __construct() {
                   \$this->obj=new {$nomeClasse}();
                   \$this->dao=new {$nomeClasse}DAO();
                   \$this->acao=\$_REQUEST["acao"] ?? 0;
                  \$this->executaAcao();
               }
               public function executaAcao() {
              \$this->prepararObjeto();
               }
               public function prepararObjeto() {
                  $instancia
                  switch(\$this->acao) {
                      case 1:
                      \$this->dao->inserir( \$this->obj);
                      break;
                      case 2:
                      \$this->dao->alterar( \$this->obj);
                      break;
                      case 3:
                      \$this->dao->excluir((int) (\$_POST["id"] ?? 0));
                      break;
                      case 4:
                      \$dados = \$this->dao->listar();
                      include(__DIR__ . '/../view/listagem_{$entidade}.php');
                      break;
                  }
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