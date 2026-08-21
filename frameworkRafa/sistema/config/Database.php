<?php
class Database {
    private static ?PDO $conexao = null;

    public static function getConexao(): PDO {
        if (self::$conexao === null) {
            $host = 'localhost';
            $banco = 'framework';
            $usuario = 'root';
            $senha = '';
            self::$conexao = new PDO(
                "mysql:host=$host;dbname=$banco;charset=utf8",
                $usuario,
                $senha,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        }
        return self::$conexao;
    }
}
?>
