<?php
class Aluno {
   private int $id_aluno;
   private string $nome;
   private string $email;
   private string $data_nascimento;
   private int $id_curso;

function getId_aluno() : int{
return $this->id_aluno;
 }
function setId_aluno($arg){
 $this->id_aluno=$arg;
 }
function getNome() : string{
return $this->nome;
 }
function setNome($arg){
 $this->nome=$arg;
 }
function getEmail() : string{
return $this->email;
 }
function setEmail($arg){
 $this->email=$arg;
 }
function getData_nascimento() : string{
return $this->data_nascimento;
 }
function setData_nascimento($arg){
 $this->data_nascimento=$arg;
 }
function getId_curso() : int{
return $this->id_curso;
 }
function setId_curso($arg){
 $this->id_curso=$arg;
 }

function __toString(){
return  "nome :".$this->nome."<br>".
 "email :".$this->email."<br>".
 "data_nascimento :".$this->data_nascimento."<br>".
 "id_curso :".$this->id_curso."<br>";
}
}