<?php
class Curso {
   private int $id_curso;
   private string $nome;
   private int $carga_horaria;
   private string $descricao;
   private int $id_professor;

function getId_curso() : int{
return $this->id_curso;
 }
function setId_curso($arg){
 $this->id_curso=$arg;
 }
function getNome() : string{
return $this->nome;
 }
function setNome($arg){
 $this->nome=$arg;
 }
function getCarga_horaria() : int{
return $this->carga_horaria;
 }
function setCarga_horaria($arg){
 $this->carga_horaria=$arg;
 }
function getDescricao() : string{
return $this->descricao;
 }
function setDescricao($arg){
 $this->descricao=$arg;
 }
function getId_professor() : int{
return $this->id_professor;
 }
function setId_professor($arg){
 $this->id_professor=$arg;
 }

function __toString(){
return  "nome :".$this->nome."<br>".
 "carga_horaria :".$this->carga_horaria."<br>".
 "descricao :".$this->descricao."<br>".
 "id_professor :".$this->id_professor."<br>";
}
}