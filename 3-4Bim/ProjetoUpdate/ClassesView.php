<?php
require_once ("utils.php");
class ClassesView{
private $entidades;
private $caminho = "sistema/view/";
    function __construct($e) {
        if (!is_dir($this->caminho)) {
            mkdir($this->caminho, 7777, true);
        }
        $this->entidades = $e;
        $this->criaFormulario();
        $this->criaFormularioEdicao();
        $this->criaLista();
        $this->criarIndex();
        $this->criarDownload();
    }
    function criaLista() {
        $listaEntidades = array_keys($this->entidades);
        foreach ($listaEntidades as $entidade) {
            $listaAtributos = $this->entidades[$entidade];
            $cabecalho = "";
            $dados = "";
            foreach ($listaAtributos as $key => $atributo) {
                if($atributo["primary"]) $chave = $key;
                $cabecalho .= "<th>$key</th>\n";
                $dados .= "<td><?php echo \$dado['{$key}']?></td>\n";
            }
            $cabecalho .= "<th colspan='2'>Gerenciamento</th>\n";
            $dados .= "<td><a class='btn btn-danger btn-sm' href='../control/{$entidade}Control.php?acao=3&id=<?php echo \$dado[\"{$chave}\"] ?>'>Excluir</a></td>\n";
            $dados .= "<td><a class='btn btn-success btn-sm' href='../control/{$entidade}Control.php?acao=4&id=<?php echo \$dado[\"{$chave}\"] ?>'>Atualizar</a></td>\n";
            $classe = ucfirst($entidade);
            $conteudo = <<<LISTA
                <?php
                  require_once (__DIR__.'/../control/{$entidade}Control.php');
                  \$_REQUEST['acao'] = 2;
                  \$control = new {$classe}Control();
                  \$dados = \$control->executaAcao();
                ?>
                <html>
                    <head>
                        <title>Lista de {$entidade}</title>
                        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
                        <style>
                            body {
                                font-family: "Segoe UI", Arial, sans-serif;
                                background: linear-gradient(135deg, #0f172a, #1e293b);
                                min-height: 100vh;
                                padding: 40px 20px;
                            }
                            .card-lista {
                                background: #ffffff;
                                border-radius: 12px;
                                box-shadow: 0 10px 25px rgba(0,0,0,0.25);
                                padding: 30px;
                                max-width: 1100px;
                                margin: 0 auto;
                            }
                            .card-lista h2 { color: #1e293b; margin-bottom: 20px; }
                            .table thead { background-color: #1e293b; color: #ffffff; }
                            .table tbody tr:hover { background-color: #f1f5f9; }
                        </style>
                    </head>
                    <body>
                    <div class="card-lista">
                        <h2>Lista de {$entidade}</h2>
                        <table class="table table-bordered table-striped align-middle">
                            <thead><tr>{$cabecalho}</tr></thead>
                            <tbody>
                            <?php foreach (\$dados as \$dado): ?>
                                <tr>{$dados}</tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <a class='btn btn-primary mt-2' href='index.php'>Voltar ao início</a>
                    </div>
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
                    </body>
                </html>
                LISTA;
            file_put_contents("{$this->caminho}lista_" . $entidade . ".php", $conteudo);
        }
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
                      <a href="index.php" class="btn btn-secondary ms-2">Voltar</a>
                    </form>
                    </div>
                    </body>
                </html>
                FORM;
                file_put_contents("{$this->caminho}form_" . $entidade . ".php", $conteudo);
        }
    }
    function criaFormularioEdicao() {
            $util = new Utils();
            $listaEntidades = array_keys($this->entidades);
            foreach ($listaEntidades as $entidade) {
                $listaAtributos = $this->entidades[$entidade];
                $campos = "";
                $chave = "id";
                foreach ($listaAtributos as $key => $atributo) {
                    if($atributo["primary"]) {
                        $chave = $key;
                        continue;
                    }
                    $tipoForm = $util->converterTipoPHPForm($atributo["tipo"]);
                    $nomeMetodo = ucfirst($key);
                    $campos .= "<div class=\"mb-3\">";
                    $campos .= "<label class=\"form-label\">$key</label>";
                    $campos .= "<input type='$tipoForm' name='$key' class=\"form-control\" value=\"<?php echo \$obj->get$nomeMetodo(); ?>\">";
                    $campos .= "</div>\n\t";
                }
                $nomeClasse = ucfirst($entidade);
                $getterChave = "get" . ucfirst($chave);
                $conteudo = <<<FORM
                <html>
                    <head>
                        <title>Editar $entidade</title>
                        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
                    </head>
                    <body>
                    <div class="container mt-5">
                    <h2 class="mb-4">Editar $entidade</h2>
                    <form action="../control/{$entidade}Control.php" method="POST">
                        <input type="hidden" name="acao" value="5">
                        <input type="hidden" name="{$chave}" value="<?php echo \$obj->{$getterChave}(); ?>">
                        $campos
                        <button type="submit" class="btn btn-success">Salvar Alterações</button>
                        <a href="../view/lista_{$entidade}.php" class="btn btn-secondary ms-2">Cancelar</a>
                    </form>
                    </div>
                    </body>
                </html>
                FORM;
                file_put_contents("{$this->caminho}form_edit_" . $entidade . ".php", $conteudo);
        }
    }
    function criarIndex() {
        $listaEntidades = array_keys($this->entidades);
        $listaMenuCadastro = "";
        $listaMenuConsulta = "";
        foreach ($listaEntidades as $entidade) {
            $listaMenuCadastro .= "<li><a class=\"dropdown-item\" href=\"form_{$entidade}.php\">{$entidade}</a></li>\n";
            $listaMenuConsulta .= "<li><a class=\"dropdown-item\" href=\"lista_{$entidade}.php\">{$entidade}</a></li>\n";
        }
        $conteudo = <<<INDEX
            <html>
                <head>
                    <title>Sistema</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        * { margin: 0; padding: 0; box-sizing: border-box; }
                        body {
                            font-family: "Segoe UI", Arial, sans-serif;
                            background: linear-gradient(135deg, #0f172a, #1e293b);
                            min-height: 100vh;
                            display: flex;
                            flex-direction: column;
                        }
                        nav { background-color: #111827; padding: 12px 24px; }
                        .welcome {
                            flex: 1;
                            display: flex;
                            flex-direction: column;
                            justify-content: center;
                            align-items: center;
                            text-align: center;
                            color: #fff;
                            padding: 40px;
                        }
                        .welcome h1 { font-size: 2.5rem; margin-bottom: 12px; }
                        .welcome p { font-size: 1.1rem; color: #94a3b8; max-width: 500px; margin-bottom: 24px; }
                        footer {
                            background-color: #111827;
                            color: #64748b;
                            text-align: center;
                            padding: 16px;
                            font-size: 0.875rem;
                        }
                    </style>
                </head>
                <body>
                <nav class="navbar navbar-expand-lg navbar-dark px-3">
                    <div class="d-flex gap-3">
                        <div class="dropdown">
                            <a class="navbar-brand dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Cadastro</a>
                            <ul class="dropdown-menu">$listaMenuCadastro</ul>
                        </div>
                        <div class="dropdown">
                            <a class="navbar-brand dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Consultar</a>
                            <ul class="dropdown-menu">$listaMenuConsulta</ul>
                        </div>
                    </div>
                </nav>
                <div class="welcome">
                    <h1>Bem-vindo ao Sistema</h1>
                    <p>Use o menu acima para cadastrar ou consultar os registros do sistema.</p>
                    <a href="download.php" class="btn btn-outline-light">⬇ Baixar Sistema (.zip)</a>
                </div>
                <footer>
                    &copy; <?php echo date('Y'); ?> Sistema MVC &mdash; Gerado automaticamente pelo Framework - Direitos Reservados a Rafael de Camargo Gonçalves.
                </footer>
                <?php if(isset(\$_GET['sucesso'])): ?>
                <div class="toast-container position-fixed bottom-0 end-0 p-3">
                    <div id="toastSucesso" class="toast align-items-center text-bg-success border-0 show" role="alert">
                        <div class="d-flex">
                            <div class="toast-body fw-bold">✅ Sistema criado com sucesso!</div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                </div>
                <script>
                    setTimeout(() => {
                        const toast = document.getElementById('toastSucesso');
                        if(toast) toast.style.display = 'none';
                    }, 4000);
                </script>
                <?php endif; ?>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
                </body>
            </html>
            INDEX;
        file_put_contents("{$this->caminho}index.php", $conteudo);
    }
    function criarDownload() {
        $conteudo = <<<DOWNLOAD
        <?php
        \$pastaOrigem = __DIR__ . '/../../sistema';
        \$arquivoZip  = sys_get_temp_dir() . '/sistema.zip';
        if (!is_dir(\$pastaOrigem)) { echo "Pasta sistema não encontrada."; exit; }
        \$zip = new ZipArchive();
        if (\$zip->open(\$arquivoZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) { echo "Erro ao criar o arquivo zip."; exit; }
        \$arquivos = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(\$pastaOrigem), RecursiveIteratorIterator::LEAVES_ONLY);
        foreach (\$arquivos as \$arquivo) {
            if (!\$arquivo->isDir()) {
                \$caminhoReal = \$arquivo->getRealPath();
                \$caminhoRelativo = 'sistema/' . substr(\$caminhoReal, strlen(\$pastaOrigem) + 1);
                \$zip->addFile(\$caminhoReal, \$caminhoRelativo);
            }
        }
        \$zip->close();
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="sistema.zip"');
        header('Content-Length: ' . filesize(\$arquivoZip));
        readfile(\$arquivoZip);
        unlink(\$arquivoZip);
        exit;
        DOWNLOAD;
        file_put_contents("{$this->caminho}download.php", $conteudo);
    }
}
?>
