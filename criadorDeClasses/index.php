<?php

include("banco.php");
include("criadorDeClasses.php");
$entidades = buscarTabelas($entidades);
  print_r($entidades);
 $atributos = buscarAtributos("aluno");
 print_r($atributos);
