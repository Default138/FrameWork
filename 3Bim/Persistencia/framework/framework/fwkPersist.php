<?php
class fwkPersist {
    public function __construct(private PDO $pdo){}

    function save(object $objeto):void
    {
        $tabela         = $this -> getNomeTabela($objeto);
        $dados          = $this -> getDadosColunas($objeto);
        $dadosFiltrados = array_filter($dados, fn($valor) => $valor !== null);
        $colunas        = array_keys($dadosFiltrados);
        $stringColunas  = implode(', ', $colunas);
        $placeholders   = ':' . implode(' , :', $colunas);
        $sql            = "INSERT INTO {$tabela} ({$stringColunas}) VALUES ({$placeholders})";
        $stmt           = $this -> pdo -> prepare($sql);
        $stmt           -> execute($dadosFiltrados);
    }

    // -------------------------------------------------------------
    // NOVOS MÉTODOS: UPDATE, DELETE e LISTONE
    // -------------------------------------------------------------

    function update(object $objeto): void 
    {
        $tabela = $this->getNomeTabela($objeto);
        $dados  = $this->getDadosColunas($objeto);

        // Verifica se o objeto tem um ID. Não podemos atualizar sem saber o ID.
        if (empty($dados['id'])) {
            throw new Exception("Não é possível atualizar um registro sem ID.");
        }

        $id = $dados['id'];
        unset($dados['id']); // Removemos o ID dos campos SET (não queremos atualizar a primary key)

        // Filtra nulos assim como no save()
        $dadosFiltrados = array_filter($dados, fn($valor) => $valor !== null);
        
        // Monta a string no formato: "coluna1 = :coluna1, coluna2 = :coluna2"
        $setParts = [];
        foreach (array_keys($dadosFiltrados) as $coluna) {
            $setParts[] = "{$coluna} = :{$coluna}";
        }
        $setString = implode(', ', $setParts);

        $sql = "UPDATE {$tabela} SET {$setString} WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        
        // Adiciona o ID de volta ao array de valores para substituir o placeholder :id no WHERE
        $dadosFiltrados['id'] = $id;
        $stmt->execute($dadosFiltrados);
    }

    function delete(string $classe, int $id): void 
    {
        $tabela = $this->getNomeTabela($classe);
        $sql    = "DELETE FROM {$tabela} WHERE id = :id";
        $stmt   = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    }

    function listOne(string $classe, int $id): ?object 
    {
        $tabela = $this->getNomeTabela($classe);
        $sql    = "SELECT * FROM {$tabela} WHERE id = :id LIMIT 1";
        $stmt   = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        // Define o modo de busca para criar uma instância da classe mapeada
        $stmt->setFetchMode(PDO::FETCH_CLASS, $classe);
        $resultado = $stmt->fetch();
        
        // Retorna o objeto se encontrar, ou null caso não exista
        return $resultado ?: null; 
    }

    // -------------------------------------------------------------
    // MÉTODOS AUXILIARES EXISTENTES
    // -------------------------------------------------------------

    function listAll(string $classe): array
    {
        $tabela = $this -> getNomeTabela($classe);
        $sql    = "SELECT * FROM {$tabela}";
        $stmt   = $this -> pdo -> query($sql);
        return $stmt -> fetchAll(PDO::FETCH_CLASS, $classe);
    }

    function getNomeTabela(object|string $objectOuString ): string
    {
        $espelho        = new ReflectionClass($objectOuString);
        $etiquetas      = $espelho -> getAttributes(Tabela::class);
        $etiquetaTabela = $etiquetas[0] -> newInstance();
        return $etiquetaTabela -> nome;
    }

    function getDadosColunas(object $objeto): array 
    {
        $espelho = new ReflectionClass($objeto);
        $dados   = [];
        foreach ($espelho -> getProperties() as $propriedade) {
            $etiquetas = $propriedade -> getAttributes(Coluna::class);
            if (empty($etiquetas)) {
                continue;
            }
            $nomeColuna         = $propriedade -> getName();
            $valor              = $propriedade -> getValue($objeto);
            $dados[$nomeColuna] = $valor;
        }
        return $dados;
    }
}