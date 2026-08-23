<?PHP
require_once(__DIR__.'/../model/curso.php');
 require_once(__DIR__.'/../dao/cursoDAO.php');
class CursoControl {
   private $obj;
   private $dao;
   private $acao;
   public function __construct() {
       $this->obj=new Curso();
       $this->dao=new CursoDAO();
       $this->acao=$_REQUEST["acao"] ?? null;
      $this->executaAcao();
   }
   public function executaAcao() {
   switch($this->acao) {
          case 1:
          $this->prepararObjeto();
          $this->dao->inserir( $this->obj);
          header("Location: ../view/index.php");
          exit;
          break;
          case 2:
          return $this->dao->listar();
          case 3:
          $this->dao->excluir($_REQUEST["id"]);
          break;
          case 4:
          $this->prepararObjeto();
      }
   }
   public function prepararObjeto() {
      $this->obj->setNome($_POST["nome"]);
	$this->obj->setCarga_horaria($_POST["carga_horaria"]);
	$this->obj->setDescricao($_POST["descricao"]);
	$this->obj->setId_professor($_POST["id_professor"]);
	
   }
}
new CursoControl;
?>