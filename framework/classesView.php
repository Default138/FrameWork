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
                    <a href="../control/{$entidade}Control.php?acao=4" class="btn btn-secondary mt-3">Listar $entidade</a>
                    </div>
                    </body>
                </html>
                FORM;
                file_put_contents("{$this->caminho}form_" . $entidade . ".php", $conteudo);
        }
    }
    function criaListagem() {
            $listaEntidades = array_keys($this->entidades);
            foreach ($listaEntidades as $entidade) {
                $listaAtributos = $this->entidades[$entidade];
                $nomeClasse = ucfirst($entidade);

                $cabecalhos = "";
                foreach ($listaAtributos as $key => $atributo) {
                    $cabecalhos .= "<th>$key</th>\n\t\t\t\t";
                }

                $colunas = "";
                foreach ($listaAtributos as $key => $atributo) {
                    $colunas .= "<td><?php echo \$item->get" . ucfirst($key) . "(); ?></td>\n\t\t\t\t\t";
                }

                $conteudo = <<<LISTA
                <html>
                    <head>
                        <title>Lista $nomeClasse</title>
                        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
                    </head>
                    <body>
                    <div class="container mt-5">
                    <h2 class="mb-4">Lista de $nomeClasse</h2>
                    <a href="form_{$entidade}.php" class="btn btn-success mb-3">Novo</a>
                    <table border="1" cellpadding="10" class="table table-bordered">
                        <thead>
                            <tr>
                                $cabecalhos
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty(\$dados)): ?>
                            <?php foreach (\$dados as \$item): ?>
                            <tr>
                                $colunas
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?php echo count(\$dados[0] ?? []); ?>">Nenhum registro encontrado.</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                    <a href="form_$entidade.php" class="btn btn-primary mt-3">Voltar ao formulário</a>
                    </div>
                    </body>
                </html>
                LISTA;
                file_put_contents("{$this->caminho}listagem_" . $entidade . ".php", $conteudo);
        }
    }
}
?>
