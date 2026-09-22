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
      <div class="mb-3"><label for="titulo" class="form-label">titulo</label><input type='text' name='titulo' class="form-control"></div>
	<div class="mb-3"><label for="autor" class="form-label">autor</label><input type='text' name='autor' class="form-control"></div>
	<div class="mb-3"><label for="editora" class="form-label">editora</label><input type='text' name='editora' class="form-control"></div>
	<div class="mb-3"><label for="ano_publicacao" class="form-label">ano_publicacao</label><input type='number' name='ano_publicacao' class="form-control"></div>
	<div class="mb-3"><label for="genero" class="form-label">genero</label><input type='text' name='genero' class="form-control"></div>
	<div class="mb-3"><label for="preco" class="form-label">preco</label><input type='text' name='preco' class="form-control"></div>
	<div class="mb-3"><label for="estoque" class="form-label">estoque</label><input type='number' name='estoque' class="form-control"></div>
	<div class="mb-3"><label for="data_cadastro" class="form-label">data_cadastro</label><input type='date' name='data_cadastro' class="form-control"></div>
	<div class="mb-3"><label for="created_at" class="form-label">created_at</label><input type='date' name='created_at' class="form-control"></div>
	<div class="mb-3"><label for="updated_at" class="form-label">updated_at</label><input type='date' name='updated_at' class="form-control"></div>
	
      <button type="submit" class="btn btn-primary">Salvar</button>
      <a href="index.php" class="btn btn-secondary ms-2">Voltar</a>
    </form>
    </div>
    </body>
</html>