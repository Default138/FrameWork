<?php
class Livros {
   private int $id;
   private string $titulo;
   private string $autor;
   private string $editora;
   private int $ano_publicacao;
   private string $genero;
   private float $preco;
   private int $estoque;
   private string $data_cadastro;
   private string $created_at;
   private string $updated_at;

function getId() : int{
return $this->id;
 }
function setId($arg){
 $this->id=$arg;
 }
function getTitulo() : string{
return $this->titulo;
 }
function setTitulo($arg){
 $this->titulo=$arg;
 }
function getAutor() : string{
return $this->autor;
 }
function setAutor($arg){
 $this->autor=$arg;
 }
function getEditora() : string{
return $this->editora;
 }
function setEditora($arg){
 $this->editora=$arg;
 }
function getAno_publicacao() : int{
return $this->ano_publicacao;
 }
function setAno_publicacao($arg){
 $this->ano_publicacao=$arg;
 }
function getGenero() : string{
return $this->genero;
 }
function setGenero($arg){
 $this->genero=$arg;
 }
function getPreco() : float{
return $this->preco;
 }
function setPreco($arg){
 $this->preco=$arg;
 }
function getEstoque() : int{
return $this->estoque;
 }
function setEstoque($arg){
 $this->estoque=$arg;
 }
function getData_cadastro() : string{
return $this->data_cadastro;
 }
function setData_cadastro($arg){
 $this->data_cadastro=$arg;
 }
function getCreated_at() : string{
return $this->created_at;
 }
function setCreated_at($arg){
 $this->created_at=$arg;
 }
function getUpdated_at() : string{
return $this->updated_at;
 }
function setUpdated_at($arg){
 $this->updated_at=$arg;
 }

function __toString(){
return  "titulo :".$this->titulo."<br>".
 "autor :".$this->autor."<br>".
 "editora :".$this->editora."<br>".
 "ano_publicacao :".$this->ano_publicacao."<br>".
 "genero :".$this->genero."<br>".
 "preco :".$this->preco."<br>".
 "estoque :".$this->estoque."<br>".
 "data_cadastro :".$this->data_cadastro."<br>".
 "created_at :".$this->created_at."<br>".
 "updated_at :".$this->updated_at."<br>";
}
}