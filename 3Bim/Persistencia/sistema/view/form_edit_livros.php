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
        <input type="hidden" name="id" value="<?php echo $obj->id ?? ''; ?>">
        <div class="mb-3"><label class="form-label">Título</label><input type='text' name='titulo' class="form-control" value="<?php echo $obj->titulo ?? ''; ?>" required></div>
        <div class="mb-3"><label class="form-label">Autor</label><input type='text' name='autor' class="form-control" value="<?php echo $obj->autor ?? ''; ?>" required></div>
        <div class="mb-3"><label class="form-label">Editora</label><input type='text' name='editora' class="form-control" value="<?php echo $obj->editora ?? ''; ?>" required></div>
        <div class="mb-3"><label class="form-label">Ano de Publicação</label><input type='number' name='ano_publicacao' class="form-control" value="<?php echo $obj->ano_publicacao ?? ''; ?>" required></div>
        <div class="mb-3"><label class="form-label">Gênero</label><input type='text' name='genero' class="form-control" value="<?php echo $obj->genero ?? ''; ?>" required></div>
        <div class="mb-3"><label class="form-label">Preço</label><input type='number' step="0.01" name='preco' class="form-control" value="<?php echo $obj->preco ?? ''; ?>" required></div>
        <div class="mb-3"><label class="form-label">Estoque</label><input type='number' name='estoque' class="form-control" value="<?php echo $obj->estoque ?? ''; ?>" required></div>
        <button type="submit" class="btn btn-success">Salvar Alterações</button>
        <a href="../view/lista_livros.php" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
    </div>
    </body>
</html>