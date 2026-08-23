<?PHP
require_once(__DIR__.'/../model/professor.php');
 require_once(__DIR__.'/../dao/professorDAO.php');
class ProfessorControl {
   private $obj;
   private $dao;
   private $acao;
   public function __construct() {
       $this->obj=new Professor();
       $this->dao=new ProfessorDAO();
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
	$this->obj->setEspecialidade($_POST["especialidade"]);
	$this->obj->setData_admissao($_POST["data_admissao"]);
	
   }
}
new ProfessorControl;
?>