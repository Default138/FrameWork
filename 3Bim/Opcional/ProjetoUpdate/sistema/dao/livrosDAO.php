<?php
require_once(__DIR__."/../model/livros.php");
require_once(__DIR__."/../model/Conexao.php");

class LivrosDAO
{
    private PDO $conexao;
    
    public function __construct()
    {
        $this->conexao = Conexao::conectar();
    }
    
    public function inserir(Livros $obj): bool
    {
        try{
            $sql = "insert into livros (titulo,autor,editora,ano_publicacao,genero,preco,estoque,data_cadastro,created_at,updated_at) values(?,?,?,?,?,?,?,?,?,?)";
            $stmt = $this->conexao->prepare($sql);
            $val1 = $obj->getTitulo();
		$stmt->bindValue(1, ($val1 === '' || $val1 === null) ? null : $val1);
		$val2 = $obj->getAutor();
		$stmt->bindValue(2, ($val2 === '' || $val2 === null) ? null : $val2);
		$val3 = $obj->getEditora();
		$stmt->bindValue(3, ($val3 === '' || $val3 === null) ? null : $val3);
		$val4 = $obj->getAno_publicacao();
		$stmt->bindValue(4, ($val4 === '' || $val4 === null) ? null : $val4);
		$val5 = $obj->getGenero();
		$stmt->bindValue(5, ($val5 === '' || $val5 === null) ? null : $val5);
		$val6 = $obj->getPreco();
		$stmt->bindValue(6, ($val6 === '' || $val6 === null) ? null : $val6);
		$val7 = $obj->getEstoque();
		$stmt->bindValue(7, ($val7 === '' || $val7 === null) ? null : $val7);
		$val8 = $obj->getData_cadastro();
		$stmt->bindValue(8, ($val8 === '' || $val8 === null) ? null : $val8);
		$val9 = $obj->getCreated_at();
		$stmt->bindValue(9, ($val9 === '' || $val9 === null) ? null : $val9);
		$val10 = $obj->getUpdated_at();
		$stmt->bindValue(10, ($val10 === '' || $val10 === null) ? null : $val10);
		
            $stmt->execute();
            return true;
        } catch (PDOException $e){
            echo $e->getMessage();
            return false;
        }
    }
    
    public function alterar(Livros $objeto): bool
    {
        try {
            $sql = "update livros set titulo = ?, autor = ?, editora = ?, ano_publicacao = ?, genero = ?, preco = ?, estoque = ?, data_cadastro = ?, created_at = ?, updated_at = ? where id = ?";
            $stmt = $this->conexao->prepare($sql);
            $valA1 = $objeto->getTitulo();
		$stmt->bindValue(1, ($valA1 === '' || $valA1 === null) ? null : $valA1);
		$valA2 = $objeto->getAutor();
		$stmt->bindValue(2, ($valA2 === '' || $valA2 === null) ? null : $valA2);
		$valA3 = $objeto->getEditora();
		$stmt->bindValue(3, ($valA3 === '' || $valA3 === null) ? null : $valA3);
		$valA4 = $objeto->getAno_publicacao();
		$stmt->bindValue(4, ($valA4 === '' || $valA4 === null) ? null : $valA4);
		$valA5 = $objeto->getGenero();
		$stmt->bindValue(5, ($valA5 === '' || $valA5 === null) ? null : $valA5);
		$valA6 = $objeto->getPreco();
		$stmt->bindValue(6, ($valA6 === '' || $valA6 === null) ? null : $valA6);
		$valA7 = $objeto->getEstoque();
		$stmt->bindValue(7, ($valA7 === '' || $valA7 === null) ? null : $valA7);
		$valA8 = $objeto->getData_cadastro();
		$stmt->bindValue(8, ($valA8 === '' || $valA8 === null) ? null : $valA8);
		$valA9 = $objeto->getCreated_at();
		$stmt->bindValue(9, ($valA9 === '' || $valA9 === null) ? null : $valA9);
		$valA10 = $objeto->getUpdated_at();
		$stmt->bindValue(10, ($valA10 === '' || $valA10 === null) ? null : $valA10);
		$stmt->bindValue(11, $objeto->getId());
		
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
    
    public function excluir(int $id): bool
    {
        try {
            $sql = "delete from livros where id = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
    
    public function buscarPorId(int $id): ?Livros
    {
        $sql = "select * from livros where id = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$dados) return null;
        
        $obj = new Livros();
        foreach ($dados as $campo => $valor) {
            $metodo = 'set' . ucfirst($campo);
            if (method_exists($obj, $metodo)) {
                $obj->$metodo($valor);
            }
        }
        return $obj;
    }
    
    public function listar(): array
    {
        $sql="select * from livros";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    private function montarObjeto(array $dados): Livros
    {
        $obj = new Livros();
        foreach ($dados as $campo => $valor) {
            $metodo = 'set' . ucfirst($campo);
            if (method_exists($obj, $metodo)) {
                $obj->$metodo($valor);
            }
        }
        return $obj;
    }
}
?>