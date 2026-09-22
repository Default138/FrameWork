<?php
require_once(__DIR__ . '/../atributos/Tabela.php');
require_once(__DIR__ . '/../atributos/Coluna.php');
require_once(__DIR__ . '/../model/Livro.php');
require_once(__DIR__ . '/../framework/fwkPersist.php');

class LivrosControl {
    private fwkPersist $fwk;
    private $acao;

    public function __construct() {
        $pdo = new PDO("mysql:host=localhost;dbname=biblioteca;charset=utf8mb4", "root", ""); //conecta com o banco de dados
        $this->fwk = new fwkPersist($pdo);
        $this->acao = $_REQUEST["acao"] ?? null;
    }

    public function executaAcao() {
        switch($this->acao) {
            case 1:
                $obj = $this->prepararObjeto();
                $this->fwk->save($obj);
                header("Location: ../view/lista_livros.php");
                exit;

            case 2:
                return $this->fwk->listAll(Livro::class);

            case 3:
                $id = (int)($_REQUEST["id"] ?? 0);
                $this->fwk->delete(Livro::class, $id);
                header("Location: ../view/lista_livros.php");
                exit;

            case 4:
                $id = (int)($_REQUEST["id"] ?? 0);
                $obj = $this->fwk->listOne(Livro::class, $id);
                include(__DIR__ . '/../view/form_edit_livros.php');
                exit;

            case 5:
                $obj = $this->prepararObjeto();
                $obj->id = (int)($_POST["id"] ?? 0);
                $this->fwk->update($obj);
                header("Location: ../view/lista_livros.php");
                exit;
        }
    }

    private function prepararObjeto(): Livro {
        $obj = new Livro();
        $obj->titulo = $_POST["titulo"] ?? null;
        $obj->autor = $_POST["autor"] ?? null;
        $obj->editora = $_POST["editora"] ?? null;
        $obj->ano_publicacao = !empty($_POST["ano_publicacao"]) ? (int)$_POST["ano_publicacao"] : null;
        $obj->genero = $_POST["genero"] ?? null;
        $obj->preco = !empty($_POST["preco"]) ? (float)$_POST["preco"] : null;
        $obj->estoque = !empty($_POST["estoque"]) ? (int)$_POST["estoque"] : null;
        return $obj;
    }
}

if (basename($_SERVER['PHP_SELF']) === 'livrosControl.php') {
    $control = new LivrosControl();
    $control->executaAcao();
}