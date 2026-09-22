<html>
    <head>
        <title>Editar livros</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
    <div class="container mt-5">
    <h2 class="mb-4">Editar livros</h2>
    <form action="../control/livrosControl.php" method="POST">
        <input type="hidden" name="acao" value="5">
        <input type="hidden" name="id" value="<?php echo $obj->getId(); ?>">
        <div class="mb-3"><label class="form-label">titulo</label><input type='text' name='titulo' class="form-control" value="<?php echo $obj->getTitulo(); ?>"></div>
	<div class="mb-3"><label class="form-label">autor</label><input type='text' name='autor' class="form-control" value="<?php echo $obj->getAutor(); ?>"></div>
	<div class="mb-3"><label class="form-label">editora</label><input type='text' name='editora' class="form-control" value="<?php echo $obj->getEditora(); ?>"></div>
	<div class="mb-3"><label class="form-label">ano_publicacao</label><input type='number' name='ano_publicacao' class="form-control" value="<?php echo $obj->getAno_publicacao(); ?>"></div>
	<div class="mb-3"><label class="form-label">genero</label><input type='text' name='genero' class="form-control" value="<?php echo $obj->getGenero(); ?>"></div>
	<div class="mb-3"><label class="form-label">preco</label><input type='text' name='preco' class="form-control" value="<?php echo $obj->getPreco(); ?>"></div>
	<div class="mb-3"><label class="form-label">estoque</label><input type='number' name='estoque' class="form-control" value="<?php echo $obj->getEstoque(); ?>"></div>
	<div class="mb-3"><label class="form-label">data_cadastro</label><input type='date' name='data_cadastro' class="form-control" value="<?php echo $obj->getData_cadastro(); ?>"></div>
	<div class="mb-3"><label class="form-label">created_at</label><input type='date' name='created_at' class="form-control" value="<?php echo $obj->getCreated_at(); ?>"></div>
	<div class="mb-3"><label class="form-label">updated_at</label><input type='date' name='updated_at' class="form-control" value="<?php echo $obj->getUpdated_at(); ?>"></div>
	
        <button type="submit" class="btn btn-success">Salvar Alterações</button>
        <a href="../view/lista_livros.php" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
    </div>
    </body>
</html>