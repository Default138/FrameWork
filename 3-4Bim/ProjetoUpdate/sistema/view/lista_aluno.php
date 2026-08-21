<?php

  require_once (__DIR__.'/../control/alunoControl.php');
  $_REQUEST['acao'] = 2;
  $control = new AlunoControl();
  $dados = $control->executaAcao();
?>
<html>
    <head>
        <title>Lista de aluno</title>
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
    <table class="table table-striped">
    <tr> <td>id_aluno</td>
<td>nome</td>
<td>email</td>
<td>data_nascimento</td>
<td>id_curso</td>
<td colspan='2'>Gerenciamento</td>
</tr>
     <?php
        foreach ($dados as $dado) {
      ?>
    <tr><td><?php echo $dado['id_aluno']?></td>
<td><?php echo $dado['nome']?></td>
<td><?php echo $dado['email']?></td>
<td><?php echo $dado['data_nascimento']?></td>
<td><?php echo $dado['id_curso']?></td>
<td><a href=__DIR__.'/../control/alunoControl.php?acao=3&id=<?php echo $dado['id_aluno'] ?>>Excluir</a></td>
<td>
            <a href=__DIR__.'/../control/alunoControl.php?acao=4&id=<?php echo $dado['id_aluno'] ?>>Atualizar</a></td>
</tr>
    <?php
    }
    ?>
    </table>
    </body>
</html>