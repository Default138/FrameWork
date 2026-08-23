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
                $placeholders="";
                $i=1;
                foreach ($listaAtributos as $key => $atributo) {
                    if(!$atributo["primary"]) {
                        $bindings .= "\$val$i = \$obj->get" . ucfirst($key) . "();\n\t\t";
                        $bindings .= "\$stmt->bindValue($i, (\$val$i === '' || \$val$i === null) ? null : \$val$i);\n\t\t";
                        $atributos .= $key . ",";
                        $placeholders .= "?,";
                        $i++;
                    }
                    else {
                        $this->chave = $key;
                    }
                 }
                $atributos=substr($atributos,0,-1);
                $placeholders=substr($placeholders,0,-1);
                $nomeClasse=ucfirst($entidade);
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
                        // Implementar
                    }
                    public function excluir(int \$id): bool
                    {
                        \$sql = "delete from {$entidade} where {$this->chave} = :id";
                        \$stmt = \$this->conexao->prepare(\$sql);
                        \$stmt->bindValue(':id', \$id, PDO::PARAM_INT);
                        \$stmt->execute();
                        header("Location: ../view/lista_{$entidade}.php");
                        exit();
                    }
                    public function buscarPorId(int \$id): ?$nomeClasse
                    {
                        // Implementar
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
                        // Implementar
                    }
                }
                ?>
                CLASS;
                file_put_contents("{$this->caminho}{$entidade}DAO.php", $conteudo);
        }
    }
}
?>