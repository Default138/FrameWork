<?php
class Professor {
   private int $id_professor;
   private string $nome;
   private string $email;
   private string $especialidade;
   private string $data_admissao;

function getId_professor() : int{
return $this->id_professor;
 }
function setId_professor($arg){
 $this->id_professor=$arg;
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
function getEspecialidade() : string{
return $this->especialidade;
 }
function setEspecialidade($arg){
 $this->especialidade=$arg;
 }
function getData_admissao() : string{
return $this->data_admissao;
 }
function setData_admissao($arg){
 $this->data_admissao=$arg;
 }

function __toString(){
return  "nome :".$this->nome."<br>".
 "email :".$this->email."<br>".
 "especialidade :".$this->especialidade."<br>".
 "data_admissao :".$this->data_admissao."<br>";
}
}