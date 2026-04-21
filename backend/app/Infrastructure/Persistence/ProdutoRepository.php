<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Produto\Produto;
use App\Domain\Produto\ProdutoRepositoryInterface;
use App\Infrastructure\Database\Connection;
use PDO;

class ProdutoRepository implements ProdutoRepositoryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function listar(?string $filtro = null): array
    {
        $sql = 'SELECT
                    tp.CodProduto AS id,
                    tp.Descricao AS descricao,
                    tp.Marca AS marca,
                    tp.Uni AS unidade,
                    tp.EstoqueMinimo AS estoqueMinimo,
                    tp.Precovenda AS precoVenda,
                    tp.TipoP AS tipoP,
                    tp.Ativo AS ativo,
                    tp.CodCategoria AS categoriaId,
                    tpc.Categoria AS categoria,
                    tp.CodBarra AS codBarra,
                    COALESCE(tec.Estoque, 0) AS estoque
                FROM tproduto tp
                LEFT JOIN tproduto_categoria tpc ON tp.CodCategoria = tpc.CodCategoria
                LEFT JOIN (
                    SELECT CodProduto, SUM(Estoque) AS Estoque
                    FROM testoqcompras
                    GROUP BY CodProduto
                ) tec ON tp.CodProduto = tec.CodProduto
                WHERE 1=1';

        $params = [];
        if ($filtro !== null && trim($filtro) !== '') {
            $sql .= ' AND tp.Descricao LIKE :filtro';
            $params[':filtro'] = '%' . $filtro . '%';
        }

        $sql .= ' ORDER BY tp.Ativo DESC, tp.Descricao ASC';

        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute($params);
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(static function (array $row): Produto {
            return Produto::fromDatabaseRow($row);
        }, $rows);
    }

    public function buscarPorId(int $id): ?Produto
    {
        $sql = 'SELECT
                    tp.CodProduto AS id,
                    tp.Descricao AS descricao,
                    tp.Marca AS marca,
                    tp.Uni AS unidade,
                    tp.EstoqueMinimo AS estoqueMinimo,
                    tp.Precovenda AS precoVenda,
                    tp.TipoP AS tipoP,
                    tp.Ativo AS ativo,
                    tp.CodCategoria AS categoriaId,
                    tpc.Categoria AS categoria,
                    tp.CodBarra AS codBarra,
                    COALESCE(tec.Estoque, 0) AS estoque
                FROM tproduto tp
                LEFT JOIN tproduto_categoria tpc ON tp.CodCategoria = tpc.CodCategoria
                LEFT JOIN (
                    SELECT CodProduto, SUM(Estoque) AS Estoque
                    FROM testoqcompras
                    GROUP BY CodProduto
                ) tec ON tp.CodProduto = tec.CodProduto
                WHERE tp.CodProduto = :id
                LIMIT 1';

        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute([':id' => $id]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return Produto::fromDatabaseRow($row);
    }

    public function salvar(Produto $produto): int
    {
        $data = [
            'Descricao' => $produto->getDescricao(),
            'Marca' => $produto->getMarca(),
            'Uni' => $produto->getUnidade(),
            'EstoqueMinimo' => $produto->getEstoqueMinimo(),
            'Precovenda' => $produto->getPrecoVenda(),
            'TipoP' => $produto->getTipoP(),
            'CodCategoria' => $produto->getCategoriaId(),
            'CodBarra' => $produto->getCodBarra(),
            'Ativo' => $produto->getAtivoChar(),
        ];

        if ($produto->getId() > 0) {
            $sql = 'UPDATE tproduto SET Descricao = :Descricao, Marca = :Marca, Uni = :Uni, EstoqueMinimo = :EstoqueMinimo, Precovenda = :Precovenda, TipoP = :TipoP, CodCategoria = :CodCategoria, CodBarra = :CodBarra, Ativo = :Ativo WHERE CodProduto = :CodProduto';
            $data['CodProduto'] = $produto->getId();
            $statement = $this->connection->getPdo()->prepare($sql);
            $statement->execute($data);

            return $produto->getId();
        }

        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = sprintf('INSERT INTO tproduto (%s) VALUES (%s)', $columns, $placeholders);
        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute($data);

        return (int) $this->connection->getPdo()->lastInsertId();
    }

    public function ativarDesativar(int $id, bool $ativo): bool
    {
        $sql = 'UPDATE tproduto SET Ativo = :ativo WHERE CodProduto = :id';
        $statement = $this->connection->getPdo()->prepare($sql);

        return $statement->execute([
            ':ativo' => $ativo ? 'S' : 'N',
            ':id' => $id,
        ]);
    }
}
