// classesView.php
<?php
require_once ("utils.php");
class ClassesView{
    private array $entidades;
    private string $caminho = "sistema/view/";
    function __construct(array $e) {
        if (!is_dir($this->caminho)) {
            mkdir($this->caminho, 0777, true);
        }
        $this->entidades = $e;
        $this->criaFormulario();
        $this->criaListagem();
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
                    $campos .= "<input type='" . $tipoForm . "' name='" . $key . "' id='" . $key . "' class=\"form-control\">";
                    $campos .= "</div>\n\t";
                }
            }
            $conteudo = <<<FORM
            <html>
                <head>
                    <title>Cadastro - $entidade</title>
                      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
                </head>
                <body>
                <div class="container mt-5">
                <h2 class="mb-4">Cadastro - $entidade</h2>
                <form action="../control/{$entidade}Control.php" method="POST">
                <input type="hidden" name="acao" value="1">
                  $campos
                  <button type="submit" class="btn btn-primary">Salvar</button>
                </form>
                <a href="../control/{$entidade}Control.php?acao=4" class="btn btn-secondary mt-3">Listar $entidade</a>
                </div>
                </body>
            </html>
            FORM;
            file_put_contents("{$this->caminho}form_" . $entidade . ".php", $conteudo);
        }
    }

    //  gera o arquivo que exibe o resultado da busca em formato de tabela
    function criaListagem() {
        $listaEntidades = array_keys($this->entidades);
        foreach ($listaEntidades as $entidade) {
            $listaAtributos = $this->entidades[$entidade];
            $cabecalho = "";
            $colunas = "";
            foreach ($listaAtributos as $key => $atributo) {
                $cabecalho .= "<th>$key</th>\n\t\t\t";
                $colunas  .= "<td><?php echo \$item->get" . ucfirst($key) . "(); ?></td>\n\t\t\t\t";
            }
            $conteudo = <<<LISTA
            <html>
                <head>
                    <title>Listagem - $entidade</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
                </head>
                <body>
                <div class="container mt-5">
                <h2 class="mb-4">Listagem - $entidade</h2>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            $cabecalho
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (\$dados as \$item): ?>
                        <tr>
                            $colunas
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="form_$entidade.php" class="btn btn-primary">Voltar ao formulário</a>
                </div>
                </body>
            </html>
            LISTA;
            file_put_contents("{$this->caminho}listagem_" . $entidade . ".php", $conteudo);
        }
    }
}
?>