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
                <ul class="dropdown-menu"><li><a class="dropdown-item" href="form_livros.php">livros</a></li>
</ul>
            </div>
            <div class="dropdown">
                <a class="navbar-brand dropdown-toggle" href="#" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">Consultar</a>
                <ul class="dropdown-menu"><li><a class="dropdown-item" href="lista_livros.php">livros</a></li>
</ul>
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
    <?php if(isset($_GET['sucesso'])): ?>
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