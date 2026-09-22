<?php

#[Attribute(Attribute::TARGET_PROPERTY)]
class Coluna {
    public function __construct(public ?string $nome = null){}
}