<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_FILES['arquivo']) || $_FILES['arquivo']['error'] !== UPLOAD_ERR_OK) {
    echo "<p>Erro ao receber o arquivo. Tente novamente.</p>";
    exit;
}

$arquivo = $_FILES['arquivo'];
$extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));

if ($extensao !== 'html' && $extensao !== 'htm') {
    echo "<p>Apenas arquivos HTML são permitidos.</p>";
    exit;
}

$conteudo = file_get_contents($arquivo['tmp_name']);

preg_match_all('/<li>(.*?)<\/li>/i', $conteudo, $matches);

$cursos = $matches[1];

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cursos Extraídos</title>
</head>
<body>

<h2>Cursos encontrados</h2>

<?php if (!empty($cursos)): ?>
<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>#</th>
            <th>Curso</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cursos as $i => $curso): ?>
        <tr>
            <td><?php echo $i + 1; ?></td>
            <td><?php echo htmlspecialchars(trim($curso)); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
<p>Nenhum curso encontrado no arquivo enviado.</p>
<?php endif; ?>

</body>
</html>
