<?php
// Exercício 2: Geração Dinâmica de Código com Heredoc

// Definição da matriz com as classes
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

// Função para gerar o código da classe usando Heredoc
function gerarCodigoClasse($nomeClasse, $atributos, $metodos) {
    
    // Formata os atributos como propriedades da classe
    $propriedades = "";
    foreach ($atributos as $atributo) {
        $propriedades .= "    private \$$atributo;\n";
    }
    
    // Construtor com todos os atributos
    $parametros = implode(", ", array_map(function($attr) {
        return "\$$attr";
    }, $atributos));
    
    $atribuicoes = "";
    foreach ($atributos as $atributo) {
        $atribuicoes .= "        \$this->$atributo = \$$atributo;\n";
    }
    
    // Getters para todos os atributos
    $getters = "";
    foreach ($atributos as $atributo) {
        $getters .= <<<EOD
    public function get" . ucfirst($atributo) . "() {
        return \$this->$atributo;
    }

EOD;
    }
    
    // Setters para todos os atributos
    $setters = "";
    foreach ($atributos as $atributo) {
        $setters .= <<<EOD
    public function set" . ucfirst($atributo) . "(\$$atributo) {
        \$this->$atributo = \$$atributo;
        return \$this;
    }

EOD;
    }
    
    // Métodos da classe (esqueleto)
    $metodosCodigo = "";
    foreach ($metodos as $metodo) {
        $metodosCodigo .= <<<EOD
    public function $metodo() {
        // TODO: Implementar método $metodo
        echo "Método $metodo() chamado para {$nomeClasse}\\n";
    }

EOD;
    }
    
    // Usando Heredoc para gerar o código completo da classe
    $codigo = <<<EOD
<?php

/**
 * Classe {$nomeClasse}
 * Gerada automaticamente em " . date('Y-m-d H:i:s') . "
 */
class {$nomeClasse}
{
{$propriedades}
    /**
     * Construtor da classe {$nomeClasse}
     */
    public function __construct({$parametros})
    {
{$atribuicoes}
    }

{$getters}
{$setters}
{$metodosCodigo}
    /**
     * Representação em string da classe
     */
    public function __toString()
    {
        return "{$nomeClasse} [id: " . \$this->id . "]";
    }
}

?>
EOD;
    
    return $codigo;
}

// Função para criar os arquivos
function criarArquivosClasses($classes) {
    $arquivosCriados = array();
    
    foreach ($classes as $nomeClasse => $dados) {
        $codigo = gerarCodigoClasse(
            $nomeClasse,
            $dados['atributos'],
            $dados['metodos']
        );
        
        $nomeArquivo = $nomeClasse . '.php';
        
        // Escreve o arquivo usando funções nativas
        if (file_put_contents($nomeArquivo, $codigo) !== false) {
            $arquivosCriados[] = $nomeArquivo;
        } else {
            echo "Erro ao criar o arquivo: $nomeArquivo\n";
        }
    }
    
    return $arquivosCriados;
}

// Executa a criação dos arquivos
$arquivosCriados = criarArquivosClasses($classes);

// Exibe uma página HTML com o resultado
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geração de Classes - Exercício 2</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            margin: 40px;
            background-color: #f8f9fa;
            max-width: 1000px;
            margin: 40px auto;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            margin-top: 0;
            padding-bottom: 15px;
            border-bottom: 3px solid #3498db;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #c3e6cb;
            margin: 20px 0;
        }
        .arquivo {
            background-color: #e9ecef;
            padding: 10px 15px;
            margin: 5px 0;
            border-radius: 4px;
            font-weight: bold;
            border-left: 4px solid #3498db;
        }
        .codigo {
            background-color: #1e1e1e;
            color: #d4d4d4;
            padding: 20px;
            border-radius: 5px;
            overflow-x: auto;
            margin: 15px 0;
            max-height: 400px;
            overflow-y: auto;
            font-size: 13px;
            line-height: 1.6;
        }
        .codigo .keyword {
            color: #569cd6;
        }
        .codigo .comment {
            color: #6a9955;
        }
        .codigo .string {
            color: #ce9178;
        }
        .codigo .function {
            color: #dcdcaa;
        }
        .codigo .variable {
            color: #9cdcfe;
        }
        .btn {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .tabs {
            display: flex;
            gap: 10px;
            margin: 15px 0;
        }
        .tab-btn {
            padding: 8px 16px;
            border: 2px solid #3498db;
            background: white;
            color: #3498db;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        .tab-btn.active {
            background: #3498db;
            color: white;
        }
        .tab-btn:hover {
            background: #3498db;
            color: white;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>⚡ Geração Automática de Classes</h1>
        
        <div class="success">
            ✅ <strong>Arquivos gerados com sucesso!</strong>
            <br>
            <?php echo count($arquivosCriados); ?> classes foram criadas no diretório atual.
        </div>
        
        <h2>📁 Arquivos Criados</h2>
        <?php foreach ($arquivosCriados as $arquivo): ?>
            <div class="arquivo">
                📄 <?php echo htmlspecialchars($arquivo); ?>
                (<?php echo round(filesize($arquivo) / 1024, 2); ?> KB)
                <a href="<?php echo $arquivo; ?>" target="_blank" style="margin-left: 15px;">🔍 Visualizar</a>
            </div>
        <?php endforeach; ?>
        
        <h2>📝 Código Gerado</h2>
        <div class="tabs">
            <?php foreach ($arquivosCriados as $index => $arquivo): ?>
                <button class="tab-btn <?php echo $index === 0 ? 'active' : ''; ?>" onclick="mostrarTab('tab-<?php echo $index; ?>', this)">
                    <?php echo str_replace('.php', '', $arquivo); ?>
                </button>
            <?php endforeach; ?>
        </div>
        
        <?php foreach ($arquivosCriados as $index => $arquivo): ?>
            <div id="tab-<?php echo $index; ?>" class="tab-content <?php echo $index === 0 ? 'active' : ''; ?>">
                <h3><?php echo $arquivo; ?></h3>
                <div class="codigo">
                    <pre><?php echo htmlspecialchars(file_get_contents($arquivo)); ?></pre>
                </div>
            </div>
        <?php endforeach; ?>
        
        <p>
            <a href="#" onclick="window.location.reload();" class="btn">🔄 Regenerar Classes</a>
        </p>
    </div>
    
    <script>
        function mostrarTab(tabId, btn) {
            // Esconde todas as tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('active');
            });
            
            // Mostra a tab selecionada
            document.getElementById(tabId).classList.add('active');
            btn.classList.add('active');
        }
    </script>
</body>
</html>