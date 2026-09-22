<?PHP
require_once(__DIR__.'/../model/livros.php');
require_once(__DIR__.'/../dao/livrosDAO.php');

class LivrosControl {
   private $obj;
   private $dao;
   private $acao;
   
   public function __construct() {
       $this->obj=new Livros();
       $this->dao=new LivrosDAO();
       $this->acao=$_REQUEST["acao"] ?? null;
       $this->executaAcao();
   }
   
   public function executaAcao() {
       switch($this->acao) {
           case 1:
               $this->prepararObjeto();
               $this->dao->inserir($this->obj);
               header("Location: ../view/index.php");
               exit;
               break;
           case 2:
               return $this->dao->listar();
           case 3:
               // Lógica de redirecionamento migrada do DAO para o Controle
               $this->dao->excluir((int)($_REQUEST["id"] ?? 0));
               header("Location: ../view/lista_livros.php");
               exit;
               break;
           case 4:
               $id = (int)($_REQUEST["id"] ?? 0);
               $obj = $this->dao->buscarPorId($id);
               include(__DIR__ . '/../view/form_edit_livros.php');
               break;
           case 5:
               $this->prepararObjeto();
               $this->obj->setId((int)($_POST["id"] ?? 0));
               $this->dao->alterar($this->obj);
               header("Location: ../view/lista_livros.php");
               exit;
               break;
       }
   }
   
   public function prepararObjeto() {
      $this->obj->setTitulo($_POST["titulo"] ?? null);
	$this->obj->setAutor($_POST["autor"] ?? null);
	$this->obj->setEditora($_POST["editora"] ?? null);
	$this->obj->setAno_publicacao($_POST["ano_publicacao"] ?? null);
	$this->obj->setGenero($_POST["genero"] ?? null);
	$this->obj->setPreco($_POST["preco"] ?? null);
	$this->obj->setEstoque($_POST["estoque"] ?? null);
	$this->obj->setData_cadastro($_POST["data_cadastro"] ?? null);
	$this->obj->setCreated_at($_POST["created_at"] ?? null);
	$this->obj->setUpdated_at($_POST["updated_at"] ?? null);
	
   }
}
new LivrosControl;
?>