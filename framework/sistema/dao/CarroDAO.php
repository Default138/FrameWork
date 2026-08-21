<?php
require_once("../model/Carro.php");
require_once("../model/Conexao.php");
class CarroDAO
{
    private PDO $conexao;
    public function __construct()
    {
        $this->conexao = Conexao::conectar();
    }
    public function inserir(Carro $obj): bool
    {
        try {
            $sql = "INSERT INTO Carro (Marca,Modelo,Ano,Cor,Km) VALUES (?,?,?,?,?)";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(1, $obj->getMarca());
		$stmt->bindValue(2, $obj->getModelo());
		$stmt->bindValue(3, $obj->getAno());
		$stmt->bindValue(4, $obj->getCor());
		$stmt->bindValue(5, $obj->getKm());
		
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
    public function alterar(Carro $obj): bool
    {
        try {
            $sql = "UPDATE Carro SET Marca = ?, Modelo = ?, Ano = ?, Cor = ?, Km = ? WHERE id = ?";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(1, $obj->getMarca());
		$stmt->bindValue(2, $obj->getModelo());
		$stmt->bindValue(3, $obj->getAno());
		$stmt->bindValue(4, $obj->getCor());
		$stmt->bindValue(5, $obj->getKm());
		$stmt->bindValue(6, $obj->getId());
		
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
            $sql = "DELETE FROM Carro WHERE id = ?";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
    public function buscarPorId(int $id): ?Carro
    {
        $sql = "SELECT * FROM Carro WHERE id = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dados ? $this->montarObjeto($dados) : null;
    }
    public function listar(): array
    {
        $sql = "SELECT * FROM Carro";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();
        $linhas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $lista = [];
        foreach ($linhas as $dados) {
            $lista[] = $this->montarObjeto($dados);
        }
        return $lista;
    }
    private function montarObjeto(array $dados): Carro
    {
        return new Carro($dados['id'], $dados['Marca'], $dados['Modelo'], $dados['Ano'], $dados['Cor'], $dados['Km']);
    }
}
?>