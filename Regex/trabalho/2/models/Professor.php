<?php

class Professor {

    private $id; // int(11) - PK
    private $nome; // varchar(30)
    private $titulacao; // varchar(20)

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getTitulacao() {
        return $this->titulacao;
    }

    public function setTitulacao($titulacao) {
        $this->titulacao = $titulacao;
    }

}
