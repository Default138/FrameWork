<?php
class Conexao{
    public static function conectar():PDO{
        try {
            $host = "localhost";
            $banco = "carrinho";
            $usuario = "";
            $senha = "";
            $pdo = new PDO(
                "mysql:host=$host;dbname=$banco;charset=utf8",
                $usuario,
                $senha
            );
            return $pdo;
        } catch (PDOException $e) {
           echo "Erro na conexão: " . $e->getMessage();
        }
    }
}
?>