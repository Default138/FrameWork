<?php
  require_once(__DIR__ . '/../control/EstatisticaControl.php');
  $saida = null;
  $erroConexao = null;
  $tabelas = [];
  $colunas = [];
  try {
      $control = new EstatisticaControl();
      $tabelas = $control->tabelas();
      $tabela = $_REQUEST["tabela"] ?? "";
      if ($tabela != "" && in_array($tabela, $tabelas)) {
          $colunas = $control->colunas($tabela);
      }
      if (($_POST["acao"] ?? "") == "calcular") {
          $saida = $control->executa($_POST);
      }
  } catch (Exception $e) {
      $erroConexao = $e->getMessage();
  }
  $sel = fn($campo) => htmlspecialchars($_REQUEST[$campo] ?? "");
  $f = fn($x, $d = 2) => number_format($x, $d, ',', '.');
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Framework de Estatística</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: "Segoe UI", Arial, sans-serif; background: linear-gradient(135deg, #0f172a, #1e293b); min-height: 100vh; padding: 40px 20px; }
        .card-box { background: #ffffff; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.25); padding: 30px; max-width: 900px; margin: 0 auto 24px; }
        .card-box h2 { color: #1e293b; margin-bottom: 20px; }
        .table thead { background-color: #1e293b; color: #ffffff; }
    </style>
</head>
<body>
<div class="card-box">
    <h2>Framework de Estatística</h2>
    <?php if ($erroConexao): ?>
        <div class="alert alert-danger">Erro: <?php echo htmlspecialchars($erroConexao); ?></div>
    <?php else: ?>
    <form method="post" id="form">
        <input type="hidden" name="acao" id="acao" value="calcular">
        <div class="mb-3">
            <label class="form-label">Serviço</label>
            <select name="servico" class="form-select" onchange="document.getElementById('grupo').style.display = this.value=='anova' ? 'block':'none'">
                <option value="basica" <?php echo $sel("servico")=="basica" ? "selected":""; ?>>Estatística Básica</option>
                <option value="anova" <?php echo $sel("servico")=="anova" ? "selected":""; ?>>ANOVA</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Tabela</label>
            <select name="tabela" class="form-select" onchange="document.getElementById('acao').value='';document.getElementById('form').submit()">
                <option value="">Selecione...</option>
                <?php foreach ($tabelas as $t): ?>
                    <option value="<?php echo htmlspecialchars($t); ?>" <?php echo $sel("tabela")==$t ? "selected":""; ?>><?php echo htmlspecialchars($t); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Coluna de valores (numérica)</label>
            <select name="coluna_valor" class="form-select">
                <?php foreach ($colunas as $c): ?>
                    <option value="<?php echo htmlspecialchars($c); ?>" <?php echo $sel("coluna_valor")==$c ? "selected":""; ?>><?php echo htmlspecialchars($c); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3" id="grupo" style="display: <?php echo $sel("servico")=="anova" ? "block":"none"; ?>">
            <label class="form-label">Coluna de grupos (somente ANOVA)</label>
            <select name="coluna_grupo" class="form-select">
                <?php foreach ($colunas as $c): ?>
                    <option value="<?php echo htmlspecialchars($c); ?>" <?php echo $sel("coluna_grupo")==$c ? "selected":""; ?>><?php echo htmlspecialchars($c); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button class="btn btn-primary" onclick="document.getElementById('acao').value='calcular'">Calcular</button>
    </form>
    <?php endif; ?>
</div>

<?php if ($saida): ?>
<div class="card-box">
    <?php if (isset($saida["erro"])): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($saida["erro"]); ?></div>
    <?php elseif ($saida["servico"] == "basica"): $r = $saida["resultado"]; ?>
        <h2>Estatística Básica</h2>
        <table class="table table-bordered table-striped">
            <thead><tr><th>Indicador</th><th>Valor</th></tr></thead>
            <tbody>
                <tr><td>Observações</td><td><?php echo $saida["n"]; ?></td></tr>
                <tr><td>Média</td><td><?php echo $f($r[fwkEstatistica::MEDIA]); ?></td></tr>
                <tr><td>Mediana</td><td><?php echo $f($r[fwkEstatistica::MEDIANA]); ?></td></tr>
                <tr><td>Moda</td><td><?php echo $r[fwkEstatistica::MODA] ? implode(", ", array_map($f, $r[fwkEstatistica::MODA])) : "Amodal"; ?></td></tr>
                <tr><td>Desvio-padrão</td><td><?php echo $f($r[fwkEstatistica::DESVIO_PADRAO]); ?></td></tr>
                <tr><td>Variância</td><td><?php echo $f($r[fwkEstatistica::VARIANCIA]); ?></td></tr>
                <tr><td>Coeficiente de variação</td><td><?php echo $r[fwkEstatistica::CV] === null ? "Indefinido" : $f($r[fwkEstatistica::CV]) . "%"; ?></td></tr>
            </tbody>
        </table>
        <div class="alert alert-info"><?php echo $saida["interpretacao"]; ?></div>
    <?php else: $r = $saida["resultado"]; ?>
        <h2>ANOVA de um fator</h2>
        <table class="table table-bordered table-striped">
            <thead><tr><th>Grupo</th><th>Observações</th><th>Média</th></tr></thead>
            <tbody>
            <?php foreach ($saida["nomes"] as $i => $nome): ?>
                <tr><td><?php echo htmlspecialchars($nome); ?></td><td><?php echo $saida["n"][$i]; ?></td><td><?php echo $f($r[fwkEstatistica::MEDIAS_GRUPOS][$i]); ?></td></tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <table class="table table-bordered table-striped">
            <thead><tr><th>Medida</th><th>Valor</th></tr></thead>
            <tbody>
                <tr><td>Média geral</td><td><?php echo $f($r[fwkEstatistica::MEDIA_GERAL]); ?></td></tr>
                <tr><td>Soma dos quadrados entre grupos</td><td><?php echo $f($r[fwkEstatistica::SQ_ENTRE]); ?></td></tr>
                <tr><td>Soma dos quadrados dentro dos grupos</td><td><?php echo $f($r[fwkEstatistica::SQ_DENTRO]); ?></td></tr>
                <tr><td>Graus de liberdade entre</td><td><?php echo $r[fwkEstatistica::GL_ENTRE]; ?></td></tr>
                <tr><td>Graus de liberdade dentro</td><td><?php echo $r[fwkEstatistica::GL_DENTRO]; ?></td></tr>
                <tr><td>Quadrados médios entre</td><td><?php echo $f($r[fwkEstatistica::QM_ENTRE], 4); ?></td></tr>
                <tr><td>Quadrados médios dentro</td><td><?php echo $f($r[fwkEstatistica::QM_DENTRO], 4); ?></td></tr>
                <tr><td>Estatística F</td><td><?php echo $r[fwkEstatistica::F] === null ? "Indefinida" : $f($r[fwkEstatistica::F], 4); ?></td></tr>
                <tr><td>F-crítico (5%)</td><td><?php echo $f($r[fwkEstatistica::F_CRITICO], 4); ?></td></tr>
            </tbody>
        </table>
        <div class="alert alert-info"><strong>Conclusão:</strong> <?php echo $saida["conclusao"]; ?></div>
    <?php endif; ?>
</div>
<?php endif; ?>
</body>
</html>
