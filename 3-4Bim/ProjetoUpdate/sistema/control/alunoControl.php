<?PHP
require_once(__DIR__.'/../model/aluno.php');
 require_once(__DIR__.'/../dao/alunoDAO.php');
class AlunoControl {
   private $obj;
   private $dao;
   private $acao;
   public function __construct() {
       $this->obj=new Aluno();
       $this->dao=new AlunoDAO();
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
	$this->obj->setEmail($_POST["email"]);
	$this->obj->setData_nascimento($_POST["data_nascimento"]);
	$this->obj->setId_curso($_POST["id_curso"]);
	
   }
}
new AlunoControl;
?>