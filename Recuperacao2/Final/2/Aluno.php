<?php

/**
 * Classe Aluno
 * Gerada automaticamente em " . date('Y-m-d H:i:s') . "
 */
class Aluno
{
    private $id;
    private $nome;
    private $idade;
    private $matricula;
    private $curso;

    /**
     * Construtor da classe Aluno
     */
    public function __construct($id, $nome, $idade, $matricula, $curso)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->idade = $idade;
        $this->matricula = $matricula;
        $this->curso = $curso;

    }

    public function get" . ucfirst(id) . "() {
        return $this->id;
    }
    public function get" . ucfirst(nome) . "() {
        return $this->nome;
    }
    public function get" . ucfirst(idade) . "() {
        return $this->idade;
    }
    public function get" . ucfirst(matricula) . "() {
        return $this->matricula;
    }
    public function get" . ucfirst(curso) . "() {
        return $this->curso;
    }

    public function set" . ucfirst(id) . "($id) {
        $this->id = $id;
        return $this;
    }
    public function set" . ucfirst(nome) . "($nome) {
        $this->nome = $nome;
        return $this;
    }
    public function set" . ucfirst(idade) . "($idade) {
        $this->idade = $idade;
        return $this;
    }
    public function set" . ucfirst(matricula) . "($matricula) {
        $this->matricula = $matricula;
        return $this;
    }
    public function set" . ucfirst(curso) . "($curso) {
        $this->curso = $curso;
        return $this;
    }

    public function matricular() {
        // TODO: Implementar método matricular
        echo "Método matricular() chamado para Aluno\n";
    }
    public function estudar() {
        // TODO: Implementar método estudar
        echo "Método estudar() chamado para Aluno\n";
    }
    public function visualizarBoletim() {
        // TODO: Implementar método visualizarBoletim
        echo "Método visualizarBoletim() chamado para Aluno\n";
    }

    /**
     * Representação em string da classe
     */
    public function __toString()
    {
        return "Aluno [id: " . $this->id . "]";
    }
}

?>