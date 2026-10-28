<?php
// Arquivo útil fornecido pelo professor: cálculo do F-crítico (distribuição F via beta incompleta)
function lgammaPura($x) {
    if ($x <= 0) return 0;
    $p = [
        0.99999999999980993,
        676.5203681218851,
        -1259.1392167234025,
        771.32342877765313,
        -176.61502916214059,
        12.507343278686905,
        -0.13857109526572012,
        9.9843695780195716e-6,
        1.5056327351493116e-7
    ];

    $z = $x - 1;
    $x_sum = $p[0];
    for ($i = 1; $i < count($p); $i++) {
        $x_sum += $p[$i] / ($z + $i);
    }

    $t = $z + 7.5;
    return 0.5 * log(2 * M_PI) + ($z + 0.5) * log($t) - $t + log($x_sum);
}

function betaIncompleta($x, $a, $b) {
    if ($x <= 0) return 0.0;
    if ($x >= 1) return 1.0;

    $maxIter = 100;
    $eps = 3.0e-7;
    $lbeta = lgammaPura($a) + lgammaPura($b) - lgammaPura($a + $b);
    $front = exp(log($x) * $a + log(1.0 - $x) * $b - $lbeta) / $a;

    $f = 1.0;
    $c = 1.0;
    $d = 0.0;

    for ($i = 1; $i <= $maxIter; $i++) {
        $m = $i / 2;
        if ($i % 2 == 0) {
            $num = $m * ($b - $m) * $x / (($a + 2 * $m - 1) * ($a + 2 * $m));
        } else {
            $m = ($i - 1) / 2;
            $num = -($a + $m) * ($a + $b + $m) * $x / (($a + 2 * $m) * ($a + 2 * $m + 1));
        }

        $d = 1.0 + $num * $d;
        if (abs($d) < 1e-30) $d = 1e-30;
        $d = 1.0 / $d;

        $c = 1.0 + $num / $c;
        if (abs($c) < 1e-30) $c = 1e-30;

        $cd = $c * $d;
        $f *= $cd;

        if (abs($cd - 1.0) < $eps) break;
    }

    return $front / $f;
}

function calcularFCritico($gl1, $gl2, $alpha = 0.05) {
    $pTarget = 1.0 - $alpha;
    $low = 0.0;
    $high = 100.0;
    $fCritico = 0.0;

    for ($i = 0; $i < 60; $i++) {
        $mid = ($low + $high) / 2.0;
        $x = ($gl1 * $mid) / ($gl1 * $mid + $gl2);
        $p = betaIncompleta($x, $gl1 / 2.0, $gl2 / 2.0);

        if ($p < $pTarget) {
            $low = $mid;
        } else {
            $high = $mid;
        }
        $fCritico = $mid;
    }

    return $fCritico;
}