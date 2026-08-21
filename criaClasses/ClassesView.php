<?php
require_once ("utils.php");
class ClassesView{
private $entidades;
private $caminho = "sistema/view/";
    function __construct($e) {
        if (!is_dir($this->caminho)) {
            mkdir($this->caminho, 0777, true);
        }
        $this->entidades = $e;
        $this->criaFormulario();
    }
    function criaFormulario() {
            $util = new Utils();
            $listaEntidades = array_keys($this->entidades);
            foreach ($listaEntidades as $entidade) {
                $listaAtributos = $this->entidades[$entidade];
                $campos = "";
                foreach ($listaAtributos as $key => $atributo) {
                    if(!$atributo["primary"]) {
                        $tipoForm = $util->converterTipoPHPForm($atributo["tipo"]);
                        $campos .= "<div class=\"mb-3\">";
                        $campos .= "<label for=\"$key\" class=\"form-label\">$key</label>";
                        $campos .= "<input type='" . $tipoForm . "' name='" . $key . "' class=\"form-control\">";
                        $campos .= "</div>\n\t";
                    }
                }
                $conteudo = <<<FORM
                <html>
                    <head>
                        <title>Cadastro</title>
                          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
                    </head>
                    <body>
                    <div class="container mt-5">
                    <h2 class="mb-4">Cadastro</h2>
                    <form action="../control/{$entidade}Control.php" method="POST">
                    <input type="hidden" name="acao" value="1">
                      $campos
                      <button type="submit" class="btn btn-primary">Salvar</button>
                    </form>
                    </body>
                </html>
                FORM;
                file_put_contents("{$this->caminho}form_" . $entidade . ".php", $conteudo);
        }
    }
}
?>