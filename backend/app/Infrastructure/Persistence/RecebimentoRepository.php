<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Recebimento\Recebimento;
use App\Domain\Recebimento\RecebimentoRepositoryInterface;
use App\Infrastructure\Database\Connection;
use PDO;

class RecebimentoRepository implements RecebimentoRepositoryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function listar(?int $codPedido = null, ?string $status = null, ?string $empresa = null): array
    {
        $sql = 'SELECT tareceber.CodParcela AS id, tareceber.* FROM tareceber WHERE 1=1';
        $params = [];

        if ($codPedido !== null) {
            $sql .= ' AND CodPedido = :codPedido';
            $params[':codPedido'] = $codPedido;
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

        return array_map(static function (array $row): Recebimento {
            return Recebimento::fromDatabaseRow($row);
        }, $rows);
    }

    public function buscarPorId(int $id): ?Recebimento
    {
        $sql = 'SELECT tareceber.CodParcela AS id, tareceber.* FROM tareceber WHERE CodParcela = :id LIMIT 1';
        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute([':id' => $id]);

        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }

        return Recebimento::fromDatabaseRow($row);
    }

    public function salvar(Recebimento $recebimento): int
    {
        $params = [
            ':CodPedido' => $recebimento->getCodPedido(),
            ':DataVencimento' => $recebimento->getDataVencimento(),
            ':ValorParcela' => $recebimento->getValorParcela(),
            ':SaldoParcela' => $recebimento->getSaldoParcela(),
            ':ValorRecebido' => $recebimento->getValorRecebido(),
            ':NotaFParcela' => $recebimento->getNotaFParcela(),
            ':DataPrevReceb' => $recebimento->getDataPrevReceb(),
            ':Status' => $recebimento->getStatus(),
            ':Empresa' => $recebimento->getEmpresa(),
            ':ParcelaRef' => $recebimento->getParcelaRef(),
            ':Ativa' => $recebimento->getAtiva(),
        ];

        if ($recebimento->getId() > 0) {
            $params[':CodParcela'] = $recebimento->getId();
            $sql = 'UPDATE tareceber SET CodPedido = :CodPedido, DataVencimento = :DataVencimento, ValorParcela = :ValorParcela, SaldoParcela = :SaldoParcela, ValorRecebido = :ValorRecebido, NotaFParcela = :NotaFParcela, DataPrevReceb = :DataPrevReceb, Status = :Status, Empresa = :Empresa, ParcelaRef = :ParcelaRef, Ativa = :Ativa WHERE CodParcela = :CodParcela';
            $statement = $this->connection->getPdo()->prepare($sql);
            $statement->execute($params);

            return $recebimento->getId();
        }

        $sql = 'INSERT INTO tareceber (CodPedido, DataVencimento, ValorParcela, SaldoParcela, ValorRecebido, NotaFParcela, DataPrevReceb, Status, Empresa, ParcelaRef, Ativa) VALUES (:CodPedido, :DataVencimento, :ValorParcela, :SaldoParcela, :ValorRecebido, :NotaFParcela, :DataPrevReceb, :Status, :Empresa, :ParcelaRef, :Ativa)';
        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute($params);

        return (int) $this->connection->getPdo()->lastInsertId();
    }

    public function receber(int $id, float $valorRecebido): bool
    {
        $sql = 'UPDATE tareceber SET ValorRecebido = ValorRecebido + :valorRecebido, SaldoParcela = SaldoParcela - :valorRecebido WHERE CodParcela = :id';
        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute([
            ':valorRecebido' => $valorRecebido,
            ':id' => $id,
        ]);

        return $statement->rowCount() > 0;
    }
}