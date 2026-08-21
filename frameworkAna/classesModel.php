<?php
require_once ("utils.php");
class ClassesModel{
    private array $entidades;
    private string $caminho = "sistema/model/";
    function __construct(array $e) {
        if (!is_dir($this->caminho)) {
            mkdir($this->caminho, 0777, true);
        }
        $this->entidades = $e;
        $this->criaClasses();
    }
    function criaClasses() {
        $util = new Utils();
        $listaEntidades = array_keys($this->entidades);
        foreach ($listaEntidades as $entidade) {
            $listaAtributos = $this->entidades[$entidade];
            $attr = "";
            $metodos = "";
            $magico = "";

            $construtorParams = "";
            $construtorBody = "";
            foreach ($listaAtributos as $key => $atributo) {
                $tipoPHP = $util->converterTipoPHP($atributo["tipo"]);
                $nullable = $atributo["nullable"] ?? false; 
                $tipoDecl = $nullable ? "?$tipoPHP" : $tipoPHP;
                $construtorParams .= "$tipoDecl \$$key, ";
                $construtorBody .= "        \$this->$key = \$$key;\n";
            }
            $construtorParams = rtrim($construtorParams, ", ");

            foreach ($listaAtributos as $key => $atributo) {
                $tipoPHP = $util->converterTipoPHP($atributo["tipo"]);
                $nullable = $atributo["nullable"] ?? false;
                $tipoDecl = $nullable ? "?$tipoPHP" : $tipoPHP;
                $attr .= "   private " . $tipoDecl . " $" . $key . ";\n";
                $metodos .= "function get" . ucfirst($key) . "() : " . $tipoDecl . "{\n";
                $metodos .= "return \$this->" . $key . ";\n }\n";
                if(!$atributo["primary"]) {
                    $metodos .= "function set" . ucfirst($key) . "($tipoDecl \$arg){\n";
                    $metodos .= " \$this->" . $key . "=\$arg;\n }\n";
                    $magico .= " \"$key: \" . (\$this->$key ?? 'N/A') . \"<br>\".\n"; 
                }
            }
            $magico = substr($magico, 0, -2);
            $nomeClasse = ucfirst($entidade);
            $conteudo = <<<CLASS
            <?php
            class $nomeClasse {
            $attr
            function __construct($construtorParams){
            $construtorBody
            }
            $metodos
            function __toString(){
            return $magico;
            }
            }
            CLASS;
            file_put_contents("$this->caminho" . $entidade . ".php", $conteudo);
        }
    }
}
?>