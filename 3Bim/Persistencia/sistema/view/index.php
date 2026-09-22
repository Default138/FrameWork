<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Biblioteca</title>
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

<!--
<nav class="navbar navbar-dark px-3">
    <div class="d-flex gap-3">
        <a class="nav-link text-white fw-bold" href="form_livros.php">➕ Cadastrar Livro</a>
        <a class="nav-link text-white fw-bold" href="lista_livros.php">📋 Consultar Livros</a>
    </div>
</nav>
-->

<div class="welcome">
    <h1>Sistema de Gestão de Biblioteca</h1>
    <p>Utilize os botões abaixo ou o menu superior para gerir o acervo de livros.</p>
    <div class="d-flex gap-3 mb-4">
        <a href="form_livros.php" class="btn btn-primary btn-lg">Novo Cadastro</a>
        <a href="lista_livros.php" class="btn btn-success btn-lg">Ver Lista de Livros</a>
    </div>
    <a href="download.php" class="btn btn-outline-light btn-sm">⬇ Baixar Sistema (.zip)</a>
</div>

<footer>
    &copy; <?php echo date('Y'); ?> Sistema MVC &mdash; Biblioteca &mdash; Diretios Reservados a Rafael de Camargo Gonçalves
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>