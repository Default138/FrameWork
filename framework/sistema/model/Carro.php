<?php
class Carro {
   private int $id;
   private string $Marca;
   private string $Modelo;
   private int $Ano;
   private string $Cor;
   private int $Km;

function getId() : int{
return $this->id;
 }
function setId($arg){
 $this->id=$arg;
 }
function getMarca() : string{
return $this->Marca;
 }
function setMarca($arg){
 $this->Marca=$arg;
 }
function getModelo() : string{
return $this->Modelo;
 }
function setModelo($arg){
 $this->Modelo=$arg;
 }
function getAno() : int{
return $this->Ano;
 }
function setAno($arg){
 $this->Ano=$arg;
 }
function getCor() : string{
return $this->Cor;
 }
function setCor($arg){
 $this->Cor=$arg;
 }
function getKm() : int{
return $this->Km;
 }
function setKm($arg){
 $this->Km=$arg;
 }

function __toString(){
return  "Marca :".$this->Marca."<br>".
 "Modelo :".$this->Modelo."<br>".
 "Ano :".$this->Ano."<br>".
 "Cor :".$this->Cor."<br>".
 "Km :".$this->Km."<br>";
}
}