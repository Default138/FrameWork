<?PHP
require_once('../model/Carro.php');
require_once('../dao/CarroDAO.php');
class CarroControl {
   private $obj;
   private $dao;
   private $acao;
   public function __construct() {
       $this->obj=new Carro();
       $this->dao=new CarroDAO();
       $this->acao=$_REQUEST["acao"] ?? 0;
       $this->executaAcao();
   }
   public function executaAcao() {
       $this->prepararObjeto();
   }
   public function prepararObjeto() {
      $this->obj->setMarca($_POST["Marca"] ?? null);
	$this->obj->setModelo($_POST["Modelo"] ?? null);
	$this->obj->setAno($_POST["Ano"] ?? null);
	$this->obj->setCor($_POST["Cor"] ?? null);
	$this->obj->setKm($_POST["Km"] ?? null);
	
      switch($this->acao) {
          case 1:
              $this->dao->inserir($this->obj);
              break;
          case 2:
              $this->dao->alterar($this->obj);
              break;
          case 3:
              $this->dao->excluir((int)($_POST["id"] ?? 0));
              break;
          case 4:
              $dados = $this->dao->listar();
              include(__DIR__ . '/../view/listagem_Carro.php');
              break;
      }
   }
}
new CarroControl;
?>