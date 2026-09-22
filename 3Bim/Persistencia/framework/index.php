<?php
require_once('atributos/Tabela.php');
require_once('atributos/Coluna.php');
require_once('entidades/Usuarios.php');
require_once('entidades/Produtos.php');
require_once('framework/fwkPersist.php');

$pdo = new PDO("mysql:host=localhost;dbname=sistema_teste;charset=utf8mb4", "root", "");
$fwk = new fwkPersist($pdo);

echo "<h1>Testando o Framework</h1>";

// ==========================================
// TESTE 1: SALVAR (CREATE)
// ==========================================
$produto = new Produto();
$produto->nome = "Monitor Gamer";
$produto->preco = "1200";

$fwk->save($produto);
echo "<p>✅ <b>Create:</b> 'Monitor Gamer' foi salvo no banco.</p>";


// ==========================================
// TESTE 2: LISTAR TODOS PARA VER OS IDs
// ==========================================
$todosProdutos = $fwk->listAll(Produto::class);

// Vamos pegar o ID do último produto cadastrado para usar nos próximos testes
$ultimoProduto = end($todosProdutos);
$idParaTestar = $ultimoProduto->id;

echo "<p>📊 Último ID cadastrado é o <b>{$idParaTestar}</b>.</p>";


// ==========================================
// TESTE 3: LISTAR APENAS UM (READ)
// ==========================================
// Busca no banco EXATAMENTE o produto que acabamos de descobrir o ID
$produtoBuscado = $fwk->listOne(Produto::class, $idParaTestar);

if ($produtoBuscado) {
    echo "<p>✅ <b>Read (listOne):</b> Produto encontrado! Nome: {$produtoBuscado->nome}, Preço: {$produtoBuscado->preco}</p>";
    
    // ==========================================
    // TESTE 4: ATUALIZAR (UPDATE)
    // ==========================================
    // Trocamos o valor da propriedade e mandamos o framework atualizar
    $produtoBuscado->preco = "1500"; 
    $produtoBuscado->nome = "Monitor Gamer 4K"; 
    
    $fwk->update($produtoBuscado);
    echo "<p>✅ <b>Update:</b> O preço mudou para 1500 e o nome para 'Monitor Gamer 4K'. Verifique no seu phpMyAdmin!</p>";
}


// ==========================================
// TESTE 5: DELETAR (DELETE)
// ==========================================
// Passamos o nome da classe e o ID que queremos apagar
$fwk->delete(Produto::class, $idParaTestar);
echo "<p>✅ <b>Delete:</b> O produto de ID {$idParaTestar} foi apagado do banco de dados.</p>";


// ==========================================
// TESTE 6: TENTAR BUSCAR O DELETADO (PROVAR QUE SUMIU)
// ==========================================
$verificarSeDeletou = $fwk->listOne(Produto::class, $idParaTestar);
if (!$verificarSeDeletou) {
    echo "<p>✅ <b>Sucesso:</b> Tentamos buscar o ID {$idParaTestar} novamente e ele não existe mais!</p>";
}

?>