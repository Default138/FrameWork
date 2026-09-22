<?php
#[Attribute(Attribute::TARGET_CLASS)]
class Tabela {
    public function __construct(public string $nome){}
}