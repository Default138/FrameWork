<?php
// Teste do framework sem banco de dados (dados do enunciado). Executar: php teste_framework.php
require_once(__DIR__ . '/framework/fwkEstatistica.php');

$turmaA = [7.5, 6.0, 8.0, 5.5, 7.0, 6.5, 8.5, 5.0, 7.5, 6.0, 8.0, 6.5, 7.0];
$turmaB = [8.5, 9.0, 7.5, 8.0, 9.5, 8.0, 7.0, 8.5, 9.0, 8.0, 7.5, 8.5, 9.0];
$turmaC = [5.0, 6.5, 5.5, 6.0, 4.5, 5.5, 7.0, 6.0, 5.0, 6.5, 5.5, 6.0, 5.0, 6.5];

// monta a matriz com uma turma por coluna (turma A e B completadas com null)
$matriz = [];
for ($i = 0; $i < 14; $i++) {
    $matriz[] = [$turmaA[$i] ?? null, $turmaB[$i] ?? null, $turmaC[$i]];
}

$fwk = new fwkEstatistica();
$r = $fwk->anova($matriz);
foreach ($r[fwkEstatistica::MEDIAS_GRUPOS] as $i => $m) { echo "Média da turma " . ($i + 1) . ": " . round($m, 2) . "\n"; }
echo "Média geral: " . round($r[fwkEstatistica::MEDIA_GERAL], 2) . "\n";
echo "SQ entre: " . round($r[fwkEstatistica::SQ_ENTRE], 2) . " | SQ dentro: " . round($r[fwkEstatistica::SQ_DENTRO], 2) . "\n";
echo "GL entre: {$r[fwkEstatistica::GL_ENTRE]} | GL dentro: {$r[fwkEstatistica::GL_DENTRO]}\n";
echo "QM entre: " . round($r[fwkEstatistica::QM_ENTRE], 4) . " | QM dentro: " . round($r[fwkEstatistica::QM_DENTRO], 4) . "\n";
echo "F: " . round($r[fwkEstatistica::F], 4) . " | F-crítico: " . round($r[fwkEstatistica::F_CRITICO], 4) . "\n\n";

$b = $fwk->estatisticaBasica($turmaA);
echo "Turma A -> média " . round($b[0], 2) . ", mediana {$b[1]}, moda " . implode('/', $b[2])
   . ", dp " . round($b[3], 2) . ", variância " . round($b[4], 2) . ", CV " . round($b[5], 2) . "%\n";
$z = $fwk->estatisticaBasica([-1, 1]);
echo "Média zero -> CV: " . var_export($z[fwkEstatistica::CV], true) . "\n";
try { $fwk->estatisticaBasica([1, "a"]); } catch (InvalidArgumentException $e) { echo "Erro esperado: " . $e->getMessage() . "\n"; }
