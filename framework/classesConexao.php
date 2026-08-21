<?php
class ClassesConexao{
private $entidades;
    private $caminho = "sistema/model/";
    function __construct(string $banco,string $host) {
       $this->criaClasse($banco,$host);
    }
    function criaClasse(string $banco,string $host) : bool {
               $conteudo = <<<CLASS
                <?php
                class Conexao{
                    public static function conectar():PDO{
                        try {
                            \$host = "$host";
                            \$banco = "$banco";
                            \$usuario = "";
                            \$senha = "";
                            \$pdo = new PDO(
                                "mysql:host=\$host;dbname=\$banco;charset=utf8",
                                \$usuario,
                                \$senha
                            );
                            return \$pdo;
                        } catch (PDOException \$e) {
                           echo "Erro na conexão: " . \$e->getMessage();
                        }
                    }
                }
                ?>
                CLASS;
                return file_put_contents("{$this->caminho}Conexao.php", $conteudo);
        }
    }

?>