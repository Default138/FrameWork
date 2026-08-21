<?php
require_once ("utils.php");
class ClassesDAO{
    private array $entidades;
    private string $caminho = "sistema/dao/";
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
            $bindings = "";
            $atributos = "";
            $placeholders="";
            $i=1;
            $campoPK = "id";
            foreach ($listaAtributos as $key => $atributo) {
                if($atributo["primary"]) {
                    $campoPK = $key;
                } else {
                    $bindings .= "\$stmt->bindValue($i,\$obj->get" . ucfirst($key) . "());\n\t\t";
                    $atributos .= $key . ",";
                    $placeholders .= "?,";
                    $i++;
                }
            }
            $atributos=substr($atributos,0,-1);
            $placeholders=substr($placeholders,0,-1);
            $camelPK = ucfirst($campoPK);
            $nomeClasse=ucfirst($entidade);

            // monta cláusula SET e bindings para o método alterar()
            $setClauses = "";
            $bindAlterar = "";
            $j = 1;
            foreach ($listaAtributos as $key => $atributo) {
                if(!$atributo["primary"]) {
                    $setClauses .= "$key = ?, ";
                    $bindAlterar .= "\$stmt->bindValue($j,\$obj->get" . ucfirst($key) . "());\n\t\t";
                    $j++;
                }
            }
            $setClauses = rtrim($setClauses, ", ");
            $bindAlterar .= "\$stmt->bindValue($j,\$obj->get{$camelPK}());\n\t\t";

            // monta argumentos do construtor usados em montarObjeto()
            $construtorArgs = "";
            foreach ($listaAtributos as $key => $atributo) {
                $construtorArgs .= "\$dados['$key'], ";
            }
            $construtorArgs = rtrim($construtorArgs, ", ");

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
                  try{
                    \$sql = "insert into {$entidade} ($atributos) values($placeholders)";
                    \$stmt = \$this->conexao->prepare(\$sql);
                    $bindings
                    \$stmt->execute();
                    return true;
                    }catch (PDOException \$e){
                       echo \$e->getMessage();
                       return false;
                    }
                }
                public function alterar($nomeClasse \$obj): bool
                {
                    try{
                        \$sql = "update {$entidade} set $setClauses where $campoPK = ?";
                        \$stmt = \$this->conexao->prepare(\$sql);
                        $bindAlterar
                        \$stmt->execute();
                        return true;
                    }catch (PDOException \$e){
                        echo \$e->getMessage();
                        return false;
                    }
                }
                public function excluir(int \$id): bool
                {
                    try{
                        \$sql = "delete from {$entidade} where $campoPK = ?";
                        \$stmt = \$this->conexao->prepare(\$sql);
                        \$stmt->bindValue(1, \$id);
                        \$stmt->execute();
                        return true;
                    }catch (PDOException \$e){
                        echo \$e->getMessage();
                        return false;
                    }
                }
                public function buscarPorId(int \$id): ?$nomeClasse
                {
                    \$sql = "select * from {$entidade} where $campoPK = ?";
                    \$stmt = \$this->conexao->prepare(\$sql);
                    \$stmt->bindValue(1, \$id);
                    \$stmt->execute();
                    \$dados = \$stmt->fetch(PDO::FETCH_ASSOC);
                    return \$dados ? \$this->montarObjeto(\$dados) : null;
                }
                public function listar(): array
                {
                    \$sql = "select * from {$entidade}";
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