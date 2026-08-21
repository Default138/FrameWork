<html>
    <head>
        <title>Cadastro</title>
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
    <div class="container mt-5">
    <h2 class="mb-4">Cadastro</h2>
    <form action="../control/CarroControl.php" method="POST">
    <input type="hidden" name="acao" value="1">
      <div class="mb-3"><label for="Marca" class="form-label">Marca</label><input type='text' name='Marca' class="form-control"></div>
	<div class="mb-3"><label for="Modelo" class="form-label">Modelo</label><input type='text' name='Modelo' class="form-control"></div>
	<div class="mb-3"><label for="Ano" class="form-label">Ano</label><input type='number' name='Ano' class="form-control"></div>
	<div class="mb-3"><label for="Cor" class="form-label">Cor</label><input type='text' name='Cor' class="form-control"></div>
	<div class="mb-3"><label for="Km" class="form-label">Km</label><input type='number' name='Km' class="form-control"></div>
	
      <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
    <a href="../control/CarroControl.php?acao=4" class="btn btn-secondary mt-3">Listar Carro</a>
    </div>
    </body>
</html>