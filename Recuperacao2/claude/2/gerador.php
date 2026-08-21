<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$classes = array(
    "Aluno" => array(
        "atributos" => array("id", "nome", "idade", "matricula", "curso"),
        "metodos" => array("matricular", "estudar", "visualizarBoletim")
    ),
    "Professor" => array(
        "atributos" => array("id", "nome", "formacao", "salario", "disciplina"),
        "metodos" => array("ministrarAula", "lancarNotas", "registrarFrequencia")
    )
);

foreach ($classes as $nomeClasse => $definicao) {

    $atributos = "";
    foreach ($definicao["atributos"] as $atributo) {
        $atributos .= "    private \$$atributo;\n";
    }

    $metodos = "";
    foreach ($definicao["metodos"] as $metodo) {
        $metodos .= "    public function $metodo() {\n";
        $metodos .= "        // Implementar\n";
        $metodos .= "    }\n\n";
    }

    $conteudo = <<<CLASSE
<?php

class $nomeClasse {

$atributos
$metodos
}
CLASSE;

    $arquivo = "$nomeClasse.php";
    if (file_put_contents($arquivo, $conteudo)) {
        echo "<p>Arquivo <strong>$arquivo</strong> gerado com sucesso!</p>";
    } else {
        echo "<p>Erro ao gerar <strong>$arquivo</strong>.</p>";
    }
}
