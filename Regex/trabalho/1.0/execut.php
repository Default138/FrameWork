<?php

require_once 'LeitorSQL.php';

$leitor = new LeitorSQL('framework.sql');

echo "<h2>Estrutura das Tabelas</h2>";

print_r($leitor->getTabelas());

echo "<hr>";

echo "<h2>Estrutura da Tabela Alunos</h2>";

print_r($leitor->getAtributos('aluno'));