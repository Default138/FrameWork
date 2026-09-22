<?php

#[Tabela(nome: 'livros')]
class Livro {
    #[Coluna]
    public ?int $id = null;

    #[Coluna]
    public ?string $titulo = null;

    #[Coluna]
    public ?string $autor = null;

    #[Coluna]
    public ?string $editora = null;

    #[Coluna]
    public ?int $ano_publicacao = null;

    #[Coluna]
    public ?string $genero = null;

    #[Coluna]
    public ?float $preco = null;

    #[Coluna]
    public ?int $estoque = null;
}