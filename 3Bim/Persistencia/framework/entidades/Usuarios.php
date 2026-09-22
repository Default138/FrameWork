<?php

#[Tabela(nome: 'usuarios')]
class Usuario
{
    #[Coluna]
    public ?int $id = null;

    #[Coluna]
    public string $nome;

    #[Coluna]
    public string $email;
}