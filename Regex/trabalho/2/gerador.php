<?php

//coloquei pq tava dando erro 500 e eu não sabia oq era
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'LeitorSQL.php';

$leitor = new LeitorSQL('framework.sql');

$tabelas = $leitor->getTabelas();

$pastaModels = 'models';
if (!is_dir($pastaModels)) {
    mkdir($pastaModels);
}

foreach ($tabelas as $tabela) {

    $atributos = $leitor->getAtributos($tabela);

    $nomeClasse = ucfirst($tabela);

    $codigo  = "<?php\n\n";
    $codigo .= "class $nomeClasse {\n\n";

    //Atributos privados
    foreach ($atributos as $campo => $info) {
        $comentario = "// {$info['tipo']}";
        if ($info['primary']) {
            $comentario .= " - PK";
        }
        $codigo .= "    private \$$campo; $comentario\n";
    }

    $codigo .= "\n";

    //Getters e Setters
    foreach ($atributos as $campo => $info) {
        $nomeMetodo = ucfirst($campo);

        // Getter
        $codigo .= "    public function get$nomeMetodo() {\n";
        $codigo .= "        return \$this->$campo;\n";
        $codigo .= "    }\n\n";

        // Setter
        $codigo .= "    public function set$nomeMetodo(\$$campo) {\n";
        $codigo .= "        \$this->$campo = \$$campo;\n";
        $codigo .= "    }\n\n";
    }

    $codigo .= "}\n";

    $caminhoArquivo = "$pastaModels/$nomeClasse.php";
    file_put_contents($caminhoArquivo, $codigo);

    echo "<p>Classe gerada: <strong>$caminhoArquivo</strong></p>";
}

echo "<p><strong>Concluído! " . count($tabelas) . " classe(s) gerada(s) na pasta '$pastaModels'.</strong></p>";
