<html>
    <head>
        <title>Cadastro</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
    <div class="container mt-5">
    <h2 class="mb-4">Cadastro</h2>
    <form action="../control/livrosControl.php" method="POST">
    <input type="hidden" name="acao" value="1">
      <div class="mb-3"><label class="form-label">Título</label><input type='text' name='titulo' class="form-control" required></div>
      <div class="mb-3"><label class="form-label">Autor</label><input type='text' name='autor' class="form-control" required></div>
      <div class="mb-3"><label class="form-label">Editora</label><input type='text' name='editora' class="form-control" required></div>
      <div class="mb-3"><label class="form-label">Ano de Publicação</label><input type='number' name='ano_publicacao' class="form-control" required></div>
      <div class="mb-3"><label class="form-label">Gênero</label><input type='text' name='genero' class="form-control" required></div>
      <div class="mb-3"><label class="form-label">Preço</label><input type='number' step="0.01" name='preco' class="form-control" required></div>
      <div class="mb-3"><label class="form-label">Estoque</label><input type='number' name='estoque' class="form-control" required></div>
      <button type="submit" class="btn btn-primary">Salvar</button>
      <a href="index.php" class="btn btn-secondary ms-2">Voltar</a>
    </form>
    </div>
    </body>
</html>