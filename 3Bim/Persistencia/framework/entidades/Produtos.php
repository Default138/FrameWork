<?php


#[Tabela(nome: 'produtos')]
class Produto
{
    #[Coluna]
    public ?int $id = null;
    #[Coluna]
    public string $nome;
    #[Coluna]
    public string $preco;
}