<?php
include "utils.php";
$medias=[];
$media_geral=0;
$soma_total=0;
$t=1;
$c=0;
$sqE=0;
$sqD=0;
$glE=0;
$glD=0;
$qmE=0;
$qmD=0;
$EstF=0;
foreach ($turmas as $turma) {
    $media=0;
 foreach ($turma as $nota) {
     $media+=$nota/count($turma);
     $soma_total+=$nota;
     $c++;
 }
 array_push($medias,$media);
 echo "Media da turma {$t}: ".round($media,2)." <br>";
 $t++;
}
    $media_geral=$soma_total/$c;
    $media_geral=round($media_geral,2);
echo "Media geral : {$media_geral} <br>";

$i=0;
foreach ($turmas as $turma) {
    $sqE+=count($turma)*pow(($medias[$i]-$media_geral),2);
    $i++;
}
echo "Soma dos quadrados entre grupos: ".round($sqE,2)."<br>";
$i=0;
foreach ($turmas as $turma) {
    foreach ($turma as $nota) {
       $sqD+=pow($nota-$medias[$i],2);
    }
    $i++;
}
echo "Soma dos quadrados interna dos grupos: ".round($sqD,2)."<br>";
$glE=count($turmas)-1;
$glD= $c-count($turmas);
$qmE= $sqE/$glE;
$qmD= $sqD/$glD;
$estF=$qmE/$qmD;
$critF=calcularFCritico($glE,$glD);
echo "Grau de liberdade entre: ".$glE."<br>";
echo "Grau de liberdade dentro: ".$glD."<br>";
echo "Quadrados médios entre: ".$qmE."<br>";
echo "Quadrados médios dentro: ".$qmD."<br>";
echo "Estatística F: ".$estF."<br>";
echo "F-crítico: ".$critF."<br>";

if ($estF > $critF) {
    echo "<strong>Conclusão:</strong> As turmas têm desempenhos DIFERENTES de verdade!";
} else {
    echo "<strong>Conclusão:</strong> As diferenças entre as turmas foram apenas coincidência.";
}