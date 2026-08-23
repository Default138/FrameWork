<html>
    <head>
        <title>Cadastro</title>
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
    <div class="container mt-5">
    <h2 class="mb-4">Cadastro</h2>
    <form action="../control/cursoControl.php" method="POST">
    <input type="hidden" name="acao" value="1">
      <div class="mb-3"><label for="nome" class="form-label">nome</label><input type='text' name='nome' class="form-control"></div>
	<div class="mb-3"><label for="carga_horaria" class="form-label">carga_horaria</label><input type='number' name='carga_horaria' class="form-control"></div>
	<div class="mb-3"><label for="descricao" class="form-label">descricao</label><input type='text' name='descricao' class="form-control"></div>
	<div class="mb-3"><label for="id_professor" class="form-label">id_professor</label><input type='number' name='id_professor' class="form-control"></div>
	
      <button type="submit" class="btn btn-primary">Salvar</button>
      <a href="index.php" class="btn btn-secondary ms-2">Voltar</a>
    </form>
    </div>
    </body>
</html>