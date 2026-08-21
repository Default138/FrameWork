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
    </head>
    <body>
    <table class="table table-striped">
    <tr> <td>id_professor</td>
<td>nome</td>
<td>email</td>
<td>especialidade</td>
<td>data_admissao</td>
<td colspan='2'>Gerenciamento</td>
</tr>
     <?php
        foreach ($dados as $dado) {
      ?>
    <tr><td><?php echo $dado['id_professor']?></td>
<td><?php echo $dado['nome']?></td>
<td><?php echo $dado['email']?></td>
<td><?php echo $dado['especialidade']?></td>
<td><?php echo $dado['data_admissao']?></td>
<td><a href=__DIR__.'/../control/professorControl.php?acao=3&id=<?php echo $dado['id_professor'] ?>>Excluir</a></td>
<td>
            <a href=__DIR__.'/../control/professorControl.php?acao=4&id=<?php echo $dado['id_professor'] ?>>Atualizar</a></td>
</tr>
    <?php
    }
    ?>
    </table>
    </body>
</html>