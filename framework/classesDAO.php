<?php
require_once ("utils.php");
class ClassesDAO{
private $entidades;
private $caminho = "sistema/dao/";
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
                $campoPK = "id";
                $atributos = "";
                $placeholders = "";
                $bindings = "";
                $setClauses = "";
                $bindAlterar = "";
                $construtorArgs = "";
                $i = 1;
                $j = 1;

                foreach ($listaAtributos as $key => $atributo) {
                    if ($atributo["primary"]) {
                        $campoPK = $key;
                    } else {
                        $atributos .= "$key,";
                        $placeholders .= "?,";
                        $bindings .= "\$stmt->bindValue($i, \$obj->get" . ucfirst($key) . "());\n\t\t";
                        $setClauses .= "$key = ?, ";
                        $bindAlterar .= "\$stmt->bindValue($j, \$obj->get" . ucfirst($key) . "());\n\t\t";
                        $i++;
                        $j++;
                    }
                }

                foreach ($listaAtributos as $key => $atributo) {
                    $construtorArgs .= "\$dados['$key'], ";
                }

                $atributos = rtrim($atributos, ",");
                $placeholders = rtrim($placeholders, ",");
                $setClauses = rtrim($setClauses, ", ");
                $construtorArgs = rtrim($construtorArgs, ", ");
                $camelPK = ucfirst($campoPK);
                $bindAlterar .= "\$stmt->bindValue($j, \$obj->get{$camelPK}());\n\t\t";
                $nomeClasse = ucfirst($entidade);

                $conteudo = <<<CLASS
                <?php
                require_once("../model/{$entidade}.php");
                require_once("../model/Conexao.php");
                class {$nomeClasse}DAO
                {
                    private PDO \$conexao;
                    public function __construct()
                    {
                        \$this->conexao = Conexao::conectar();
                    }
                    public function inserir($nomeClasse \$obj): bool
                    {
                        try {
                            \$sql = "INSERT INTO $entidade ($atributos) VALUES ($placeholders)";
                            \$stmt = \$this->conexao->prepare(\$sql);
                            $bindings
                            \$stmt->execute();
                            return true;
                        } catch (PDOException \$e) {
                            echo \$e->getMessage();
                            return false;
                        }
                    }
                    public function alterar($nomeClasse \$obj): bool
                    {
                        try {
                            \$sql = "UPDATE $entidade SET $setClauses WHERE $campoPK = ?";
                            \$stmt = \$this->conexao->prepare(\$sql);
                            $bindAlterar
                            \$stmt->execute();
                            return true;
                        } catch (PDOException \$e) {
                            echo \$e->getMessage();
                            return false;
                        }
                    }
                    public function excluir(int \$id): bool
                    {
                        try {
                            \$sql = "DELETE FROM $entidade WHERE $campoPK = ?";
                            \$stmt = \$this->conexao->prepare(\$sql);
                            \$stmt->bindValue(1, \$id);
                            \$stmt->execute();
                            return true;
                        } catch (PDOException \$e) {
                            echo \$e->getMessage();
                            return false;
                        }
                    }
                    public function buscarPorId(int \$id): ?$nomeClasse
                    {
                        \$sql = "SELECT * FROM $entidade WHERE $campoPK = ?";
                        \$stmt = \$this->conexao->prepare(\$sql);
                        \$stmt->bindValue(1, \$id);
                        \$stmt->execute();
                        \$dados = \$stmt->fetch(PDO::FETCH_ASSOC);
                        return \$dados ? \$this->montarObjeto(\$dados) : null;
                    }
                    public function listar(): array
                    {
                        \$sql = "SELECT * FROM $entidade";
                        \$stmt = \$this->conexao->prepare(\$sql);
                        \$stmt->execute();
                        \$linhas = \$stmt->fetchAll(PDO::FETCH_ASSOC);
                        \$lista = [];
                        foreach (\$linhas as \$dados) {
                            \$lista[] = \$this->montarObjeto(\$dados);
                        }
                        return \$lista;
                    }
                    private function montarObjeto(array \$dados): $nomeClasse
                    {
                        return new $nomeClasse($construtorArgs);
                    }
                }
                ?>
                CLASS;
                file_put_contents("{$this->caminho}{$entidade}DAO.php", $conteudo);
        }
    }
}
?>
