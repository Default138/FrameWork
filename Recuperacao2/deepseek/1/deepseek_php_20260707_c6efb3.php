<?php
// Exercício 1: Processamento de Arquivos e Expressões Regulares

// Verifica se o arquivo foi enviado corretamente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['arquivo'])) {
    
    $arquivo = $_FILES['arquivo'];
    
    // Validações de segurança
    if ($arquivo['error'] !== UPLOAD_ERR_OK) {
        die('<p style="color: red; font-weight: bold;">Erro no upload do arquivo.</p>');
    }
    
    // Verifica o tipo do arquivo (permitindo apenas HTML)
    $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
    $tiposPermitidos = ['html', 'htm'];
    if (!in_array(strtolower($extensao), $tiposPermitidos)) {
        die('<p style="color: red; font-weight: bold;">Erro: Apenas arquivos HTML são permitidos.</p>');
    }
    
    // Verifica o tamanho do arquivo (máximo 5MB)
    if ($arquivo['size'] > 5 * 1024 * 1024) {
        die('<p style="color: red; font-weight: bold;">Erro: O arquivo excede o tamanho máximo permitido (5MB).</p>');
    }
    
    // Lê o conteúdo do arquivo de forma segura
    $conteudo = file_get_contents($arquivo['tmp_name']);
    
    if ($conteudo === false) {
        die('<p style="color: red; font-weight: bold;">Erro ao ler o conteúdo do arquivo.</p>');
    }
    
    // Expressão Regular para extrair os cursos da lista <ul>
    // Padrão: captura todos os itens <li> dentro de uma <ul>
    preg_match_all('/<ul[^>]*>.*?<li[^>]*>(.*?)<\/li>.*?<\/ul>/is', $conteudo, $matches);
    
    // Se não encontrar os itens <li> com <ul>, tenta capturar apenas <li>
    if (empty($matches[1])) {
        preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $conteudo, $matches);
    }
    
    // Limpa os nomes dos cursos removendo tags HTML extras e espaços em branco
    $cursos = array();
    if (!empty($matches[1])) {
        foreach ($matches[1] as $curso) {
            // Remove tags HTML que possam ter sobrado
            $cursoLimpo = strip_tags($curso);
            // Remove espaços extras
            $cursoLimpo = trim($cursoLimpo);
            // Remove espaços múltiplos
            $cursoLimpo = preg_replace('/\s+/', ' ', $cursoLimpo);
            if (!empty($cursoLimpo)) {
                $cursos[] = $cursoLimpo;
            }
        }
    }
    
    // Exibe o resultado formatado em uma tabela
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Processamento de Cursos</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 40px;
                background-color: #f4f7f6;
                max-width: 800px;
                margin: 40px auto;
            }
            .container {
                background: white;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            h1 {
                color: #0056b3;
                margin-top: 0;
                padding-bottom: 15px;
                border-bottom: 2px solid #0056b3;
            }
            .info {
                background-color: #e7f3ff;
                padding: 15px;
                border-radius: 5px;
                margin: 20px 0;
                border-left: 4px solid #0056b3;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            }
            th {
                background-color: #0056b3;
                color: white;
                font-weight: bold;
                padding: 15px 20px;
                text-align: left;
            }
            td {
                padding: 12px 20px;
                border-bottom: 1px solid #ddd;
            }
            tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            tr:hover {
                background-color: #f0f0f0;
            }
            .total {
                font-weight: bold;
                color: #0056b3;
                text-align: right;
                padding: 15px 20px;
                background-color: #f8f9fa;
                border-top: 2px solid #ddd;
            }
            .btn-voltar {
                display: inline-block;
                background-color: #6c757d;
                color: white;
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 5px;
                margin-top: 10px;
            }
            .btn-voltar:hover {
                background-color: #5a6268;
            }
            .erro {
                color: #dc3545;
                font-weight: bold;
                padding: 15px;
                background-color: #f8d7da;
                border-radius: 5px;
                border: 1px solid #f5c6cb;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>📚 Cursos Extraídos</h1>
            
            <?php if (empty($cursos)): ?>
                <div class="erro">
                    ⚠️ Nenhum curso foi encontrado no arquivo. Verifique se o arquivo contém uma lista <strong>&lt;ul&gt;</strong> com itens <strong>&lt;li&gt;</strong>.
                </div>
            <?php else: ?>
                <div class="info">
                    <strong>Arquivo processado:</strong> <?php echo htmlspecialchars($arquivo['name']); ?>
                    <br>
                    <strong>Total de cursos encontrados:</strong> <?php echo count($cursos); ?>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome do Curso</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cursos as $index => $curso): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($curso); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="total">
                                Total: <?php echo count($cursos); ?> cursos
                            </td>
                        </tr>
                    </tfoot>
                </table>
            <?php endif; ?>
            
            <p>
                <a href="Exercicio1.html" class="btn-voltar">← Voltar para o upload</a>
            </p>
        </div>
    </body>
    </html>
    <?php
    
} else {
    // Se não for uma requisição POST ou não tiver o arquivo
    header('Location: Exercicio1.html');
    exit;
}
?>