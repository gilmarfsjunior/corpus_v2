<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Pagar\Pagar;
use App\Domain\Pagar\PagarRepositoryInterface;
use App\Infrastructure\Database\Connection;
use PDO;

class PagarRepository implements PagarRepositoryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function listar(?int $codNotaCompra = null, ?string $status = null, ?string $empresa = null): array
    {
        $sql = 'SELECT tpagar.CodParcela AS id, tpagar.* FROM tpagar WHERE 1=1';
        $params = [];

        if ($codNotaCompra !== null) {
            $sql .= ' AND CodNotaCompra = :codNotaCompra';
            $params[':codNotaCompra'] = $codNotaCompra;
        }

        if ($empresa !== null && trim($empresa) !== '') {
            $sql .= ' AND Empresa = :empresa';
            $params[':empresa'] = $empresa;
        }

        if ($status !== null && trim($status) !== '') {
            $sql .= ' AND Status = :status';
            $params[':status'] = $status;
        }

        $sql .= ' ORDER BY CodParcela DESC LIMIT 100';

        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute($params);

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(static function (array $row): Pagar {
            return Pagar::fromDatabaseRow($row);
        }, $rows);
    }

    public function buscarPorId(int $id): ?Pagar
    {
        $sql = 'SELECT tpagar.CodParcela AS id, tpagar.* FROM tpagar WHERE CodParcela = :id';
        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute([':id' => $id]);

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return Pagar::fromDatabaseRow($row);
    }

    public function salvar(Pagar $pagar): int
    {
        $data = $pagar->toArray();

        if ($pagar->getId() === 0) {
            // Insert
            $sql = 'INSERT INTO tpagar (CodNotaCompra, DataVencimento, ValorParcela, SaldoParcela, Status, Empresa, ParcelaRef) 
                    VALUES (:codNotaCompra, :dataVencimento, :valorParcela, :saldoParcela, :status, :empresa, :parcelaRef)';
            $statement = $this->connection->getPdo()->prepare($sql);
            $statement->execute([
                ':codNotaCompra' => $data['codNotaCompra'],
                ':dataVencimento' => $data['dataVencimento'],
                ':valorParcela' => $data['valorParcela'],
                ':saldoParcela' => $data['saldoParcela'],
                ':status' => $data['status'],
                ':empresa' => $data['empresa'],
                ':parcelaRef' => $data['parcelaRef'],
            ]);
            return (int) $this->connection->getPdo()->lastInsertId();
        } else {
            // Update
            $sql = 'UPDATE tpagar SET CodNotaCompra = :codNotaCompra, DataVencimento = :dataVencimento, 
                    ValorParcela = :valorParcela, SaldoParcela = :saldoParcela, Status = :status, 
                    Empresa = :empresa, ParcelaRef = :parcelaRef WHERE CodParcela = :id';
            $statement = $this->connection->getPdo()->prepare($sql);
            $statement->execute([
                ':id' => $data['id'],
                ':codNotaCompra' => $data['codNotaCompra'],
                ':dataVencimento' => $data['dataVencimento'],
                ':valorParcela' => $data['valorParcela'],
                ':saldoParcela' => $data['saldoParcela'],
                ':status' => $data['status'],
                ':empresa' => $data['empresa'],
                ':parcelaRef' => $data['parcelaRef'],
            ]);
            return $pagar->getId();
        }
    }

    public function pagar(int $id): bool
    {
        $sql = 'UPDATE tpagar SET Status = \'F\' WHERE CodParcela = :id';
        $statement = $this->connection->getPdo()->prepare($sql);
        return $statement->execute([':id' => $id]);
    }
}