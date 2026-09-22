<?php
  require_once (__DIR__.'/../control/livrosControl.php');
  $_REQUEST['acao'] = 2;
  $control = new LivrosControl();
  $dados = $control->executaAcao();
?>
<html>
    <head>
        <title>Lista de livros</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                font-family: "Segoe UI", Arial, sans-serif;
                background: linear-gradient(135deg, #0f172a, #1e293b);
                min-height: 100vh;
                padding: 40px 20px;
            }
            .card-lista {
                background: #ffffff;
                border-radius: 12px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.25);
                padding: 30px;
                max-width: 1100px;
                margin: 0 auto;
            }
            .card-lista h2 { color: #1e293b; margin-bottom: 20px; }
            .table thead { background-color: #1e293b; color: #ffffff; }
            .table tbody tr:hover { background-color: #f1f5f9; }
        </style>
    </head>
    <body>
    <div class="card-lista">
        <h2>Lista de livros</h2>
        <table class="table table-bordered table-striped align-middle">
            <thead><tr><th>id</th>
<th>titulo</th>
<th>autor</th>
<th>editora</th>
<th>ano_publicacao</th>
<th>genero</th>
<th>preco</th>
<th>estoque</th>
<th>data_cadastro</th>
<th>created_at</th>
<th>updated_at</th>
<th colspan='2'>Gerenciamento</th>
</tr></thead>
            <tbody>
            <?php foreach ($dados as $dado): ?>
                <tr><td><?php echo $dado['id']?></td>
<td><?php echo $dado['titulo']?></td>
<td><?php echo $dado['autor']?></td>
<td><?php echo $dado['editora']?></td>
<td><?php echo $dado['ano_publicacao']?></td>
<td><?php echo $dado['genero']?></td>
<td><?php echo $dado['preco']?></td>
<td><?php echo $dado['estoque']?></td>
<td><?php echo $dado['data_cadastro']?></td>
<td><?php echo $dado['created_at']?></td>
<td><?php echo $dado['updated_at']?></td>
<td><a class='btn btn-danger btn-sm' href='../control/livrosControl.php?acao=3&id=<?php echo $dado["id"] ?>'>Excluir</a></td>
<td><a class='btn btn-success btn-sm' href='../control/livrosControl.php?acao=4&id=<?php echo $dado["id"] ?>'>Atualizar</a></td>
</tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <a class='btn btn-primary mt-2' href='index.php'>Voltar ao início</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>