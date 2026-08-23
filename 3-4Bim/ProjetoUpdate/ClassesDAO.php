<?php
require_once ("utils.php");
class ClassesDAO{
private $entidades;
private $caminho = "sistema/dao/";
private $chave = "id";
    function __construct($e) {
        if (!is_dir($this->caminho)) {
              mkdir($this->caminho, 7777, true);
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
                $placeholders = "";
                $setClauses = "";
                $bindAlterar = "";
                $i = 1;
                $j = 1;
                foreach ($listaAtributos as $key => $atributo) {
                    if(!$atributo["primary"]) {
                        // inserir
                        $bindings .= "\$val$i = \$obj->get" . ucfirst($key) . "();\n\t\t";
                        $bindings .= "\$stmt->bindValue($i, (\$val$i === '' || \$val$i === null) ? null : \$val$i);\n\t\t";
                        $atributos .= $key . ",";
                        $placeholders .= "?,";
                        // alterar
                        $setClauses .= "$key = ?, ";
                        $bindAlterar .= "\$valA$j = \$objeto->get" . ucfirst($key) . "();\n\t\t";
                        $bindAlterar .= "\$stmt->bindValue($j, (\$valA$j === '' || \$valA$j === null) ? null : \$valA$j);\n\t\t";
                        $i++;
                        $j++;
                    }
                    else {
                        $this->chave = $key;
                    }
                 }
                $atributos = substr($atributos, 0, -1);
                $placeholders = substr($placeholders, 0, -1);
                $setClauses = rtrim($setClauses, ", ");
                $nomeClasse = ucfirst($entidade);
                $chave = $this->chave;
                $camelChave = ucfirst($chave);
                // ultimo binding do alterar é o id
                $bindAlterar .= "\$stmt->bindValue($j, \$objeto->get{$camelChave}());\n\t\t";

                $conteudo = <<<CLASS
                <?php
                require_once(__DIR__."/../model/{$entidade}.php");
                require_once(__DIR__."/../model/Conexao.php");
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
                    public function alterar($nomeClasse \$objeto): bool
                    {
                        try {
                            \$sql = "update {$entidade} set $setClauses where $chave = ?";
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
                        \$sql = "delete from {$entidade} where {$chave} = :id";
                        \$stmt = \$this->conexao->prepare(\$sql);
                        \$stmt->bindValue(':id', \$id, PDO::PARAM_INT);
                        \$stmt->execute();
                        header("Location: ../view/lista_{$entidade}.php");
                        exit();
                    }
                    public function buscarPorId(int \$id): ?$nomeClasse
                    {
                        \$sql = "select * from {$entidade} where {$chave} = ?";
                        \$stmt = \$this->conexao->prepare(\$sql);
                        \$stmt->bindValue(1, \$id);
                        \$stmt->execute();
                        \$dados = \$stmt->fetch(PDO::FETCH_ASSOC);
                        if (!\$dados) return null;
                        \$obj = new $nomeClasse();
                        foreach (\$dados as \$campo => \$valor) {
                            \$metodo = 'set' . ucfirst(\$campo);
                            if (method_exists(\$obj, \$metodo)) {
                                \$obj->\$metodo(\$valor);
                            }
                        }
                        return \$obj;
                    }
                    public function listar(): array
                    {
                        \$sql="select * from {$entidade}";
                        \$stmt = \$this->conexao->prepare(\$sql);
                        \$stmt->execute();
                        return \$stmt->fetchAll();
                    }
                    private function montarObjeto(array \$dados): $nomeClasse
                    {
                        \$obj = new $nomeClasse();
                        foreach (\$dados as \$campo => \$valor) {
                            \$metodo = 'set' . ucfirst(\$campo);
                            if (method_exists(\$obj, \$metodo)) {
                                \$obj->\$metodo(\$valor);
                            }
                        }
                        return \$obj;
                    }
                }
                ?>
                CLASS;
                file_put_contents("{$this->caminho}{$entidade}DAO.php", $conteudo);
        }
    }
}
?>
