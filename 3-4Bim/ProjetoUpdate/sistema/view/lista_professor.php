<?php
  require_once (__DIR__.'/../control/professorControl.php');
  $_REQUEST['acao'] = 2;
  $control = new ProfessorControl();
  $dados = $control->executaAcao();
?>
<html>
    <head>
        <title>Lista de professor</title>
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
            .card-lista h2 {
                color: #1e293b;
                margin-bottom: 20px;
            }
            .table thead {
                background-color: #1e293b;
                color: #ffffff;
            }
            .table tbody tr:hover {
                background-color: #f1f5f9;
            }
        </style>
    </head>
    <body>
    <div class="card-lista">
        <h2>Lista de professor</h2>
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr><th>id_professor</th>
<th>nome</th>
<th>email</th>
<th>especialidade</th>
<th>data_admissao</th>
<th colspan='2'>Gerenciamento</th>
</tr>
            </thead>
            <tbody>
            <?php foreach ($dados as $dado): ?>
                <tr><td><?php echo $dado['id_professor']?></td>
<td><?php echo $dado['nome']?></td>
<td><?php echo $dado['email']?></td>
<td><?php echo $dado['especialidade']?></td>
<td><?php echo $dado['data_admissao']?></td>
<td><a class='btn btn-danger btn-sm' href='../control/professorControl.php?acao=3&id=<?php echo $dado["id_professor"] ?>'>Excluir</a></td>
<td><a class='btn btn-success btn-sm' href='../control/professorControl.php?acao=4&id=<?php echo $dado["id_professor"] ?>'>Atualizar</a></td>
</tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <a class='btn btn-primary mt-2' href='index.php'>Voltar ao início</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>