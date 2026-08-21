<html>
    <head>
        <title>Lista Carro</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
    <div class="container mt-5">
    <h2 class="mb-4">Lista de Carro</h2>
    <a href="form_Carro.php" class="btn btn-success mb-3">Novo</a>
    <table border="1" cellpadding="10" class="table table-bordered">
        <thead>
            <tr>
                <th>id</th>
				<th>Marca</th>
				<th>Modelo</th>
				<th>Ano</th>
				<th>Cor</th>
				<th>Km</th>
				
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($dados)): ?>
            <?php foreach ($dados as $item): ?>
            <tr>
                <td><?php echo $item->getId(); ?></td>
					<td><?php echo $item->getMarca(); ?></td>
					<td><?php echo $item->getModelo(); ?></td>
					<td><?php echo $item->getAno(); ?></td>
					<td><?php echo $item->getCor(); ?></td>
					<td><?php echo $item->getKm(); ?></td>
					
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="<?php echo count($dados[0] ?? []); ?>">Nenhum registro encontrado.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
    <a href="form_Carro.php" class="btn btn-primary mt-3">Voltar ao formulário</a>
    </div>
    </body>
</html>