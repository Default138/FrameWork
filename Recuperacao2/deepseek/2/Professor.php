<?php

/**
 * Classe Professor
 * Gerada automaticamente em " . date('Y-m-d H:i:s') . "
 */
class Professor
{
    private $id;
    private $nome;
    private $formacao;
    private $salario;
    private $disciplina;

    /**
     * Construtor da classe Professor
     */
    public function __construct($id, $nome, $formacao, $salario, $disciplina)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->formacao = $formacao;
        $this->salario = $salario;
        $this->disciplina = $disciplina;

    }

    public function get" . ucfirst(id) . "() {
        return $this->id;
    }
    public function get" . ucfirst(nome) . "() {
        return $this->nome;
    }
    public function get" . ucfirst(formacao) . "() {
        return $this->formacao;
    }
    public function get" . ucfirst(salario) . "() {
        return $this->salario;
    }
    public function get" . ucfirst(disciplina) . "() {
        return $this->disciplina;
    }

    public function set" . ucfirst(id) . "($id) {
        $this->id = $id;
        return $this;
    }
    public function set" . ucfirst(nome) . "($nome) {
        $this->nome = $nome;
        return $this;
    }
    public function set" . ucfirst(formacao) . "($formacao) {
        $this->formacao = $formacao;
        return $this;
    }
    public function set" . ucfirst(salario) . "($salario) {
        $this->salario = $salario;
        return $this;
    }
    public function set" . ucfirst(disciplina) . "($disciplina) {
        $this->disciplina = $disciplina;
        return $this;
    }

    public function ministrarAula() {
        // TODO: Implementar método ministrarAula
        echo "Método ministrarAula() chamado para Professor\n";
    }
    public function lancarNotas() {
        // TODO: Implementar método lancarNotas
        echo "Método lancarNotas() chamado para Professor\n";
    }
    public function registrarFrequencia() {
        // TODO: Implementar método registrarFrequencia
        echo "Método registrarFrequencia() chamado para Professor\n";
    }

    /**
     * Representação em string da classe
     */
    public function __toString()
    {
        return "Professor [id: " . $this->id . "]";
    }
}

?>