<html>
    <head>
        <title>Cadastro</title>
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
    <div class="container mt-5">
    <h2 class="mb-4">Cadastro</h2>
    <form action="../control/alunoControl.php" method="POST">
    <input type="hidden" name="acao" value="1">
      <div class="mb-3"><label for="nome" class="form-label">nome</label><input type='text' name='nome' class="form-control"></div>
	<div class="mb-3"><label for="email" class="form-label">email</label><input type='text' name='email' class="form-control"></div>
	<div class="mb-3"><label for="data_nascimento" class="form-label">data_nascimento</label><input type='date' name='data_nascimento' class="form-control"></div>
	<div class="mb-3"><label for="id_curso" class="form-label">id_curso</label><input type='number' name='id_curso' class="form-control"></div>
	
      <button type="submit" class="btn btn-primary">Salvar</button>
      <a href="index.php" class="btn btn-secondary ms-2">Voltar</a>
    </form>
    </div>
    </body>
</html>