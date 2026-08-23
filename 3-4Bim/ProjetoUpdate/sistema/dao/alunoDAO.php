<?php
require_once(__DIR__."/../model/aluno.php");
require_once(__DIR__."/../model/Conexao.php");
class AlunoDAO
{
    private PDO $conexao;
    public function __construct()
    {
        $this->conexao = Conexao::conectar();
    }
    public function inserir(Aluno $obj): bool
    {
      try{
        $sql = "insert into aluno (nome,email,data_nascimento,id_curso) values(?,?,?,?)";
        $stmt = $this->conexao->prepare($sql);
        $val1 = $obj->getNome();
		$stmt->bindValue(1, ($val1 === '' || $val1 === null) ? null : $val1);
		$val2 = $obj->getEmail();
		$stmt->bindValue(2, ($val2 === '' || $val2 === null) ? null : $val2);
		$val3 = $obj->getData_nascimento();
		$stmt->bindValue(3, ($val3 === '' || $val3 === null) ? null : $val3);
		$val4 = $obj->getId_curso();
		$stmt->bindValue(4, ($val4 === '' || $val4 === null) ? null : $val4);
		
        $stmt->execute();
        return true;
        }catch (PDOException $e){
           echo $e->getMessage();
           return false;
        }
        
    }
    public function alterar(Aluno $objeto): bool
    {
        // Implementar
    }
    public function excluir(int $id): bool
    {
        $sql = "delete from aluno where id_aluno = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        header("Location: ../view/lista_aluno.php");
        exit();
    }
    public function buscarPorId(int $id): ?Aluno
    {
        // Implementar
    }
    public function listar(): array
    {
        $sql="select * from aluno";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
        
    }
    private function montarObjeto(array $dados): Aluno
    {
        // Implementar
    }
}
?>