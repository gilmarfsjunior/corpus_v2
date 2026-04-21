<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Caixa\Caixa;
use App\Domain\Caixa\CaixaRepositoryInterface;
use App\Infrastructure\Database\Connection;
use PDO;

class CaixaRepository implements CaixaRepositoryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function listar(?string $dataCaixa = null, ?string $empresa = null): array
    {
        $sql = 'SELECT tcaixa.CodCaixa AS id, tcaixa.* FROM tcaixa WHERE 1=1';
        $params = [];

        if ($empresa !== null && trim($empresa) !== '') {
            $sql .= ' AND Empresa = :empresa';
            $params[':empresa'] = $empresa;
        }

        if ($dataCaixa !== null && trim($dataCaixa) !== '') {
            $sql .= ' AND DataCaixa = :dataCaixa';
            $params[':dataCaixa'] = $dataCaixa;
        }

        $sql .= ' ORDER BY CodCaixa DESC LIMIT 100';

        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute($params);

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(static function (array $row): Caixa {
            return Caixa::fromDatabaseRow($row);
        }, $rows);
    }

    public function buscarPorId(int $id): ?Caixa
    {
        $sql = 'SELECT tcaixa.CodCaixa AS id, tcaixa.* FROM tcaixa WHERE CodCaixa = :id LIMIT 1';
        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute([':id' => $id]);

        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }

        return Caixa::fromDatabaseRow($row);
    }

    public function salvar(Caixa $caixa): int
    {
        $params = [
            ':DataCaixa' => $caixa->getDataCaixa(),
            ':DataPrev1' => $caixa->getDataPrev1(),
            ':DataPrev2' => $caixa->getDataPrev2(),
            ':SaldoInicial' => $caixa->getSaldoInicial(),
            ':SaldoFinal' => $caixa->getSaldoFinal(),
            ':Empresa' => $caixa->getEmpresa(),
            ':SaldoInicialBanco' => $caixa->getSaldoInicialBanco(),
            ':SaldoFinalBanco' => $caixa->getSaldoFinalBanco(),
        ];

        if ($caixa->getId() > 0) {
            $params[':CodCaixa'] = $caixa->getId();
            $sql = 'UPDATE tcaixa SET DataCaixa = :DataCaixa, DataPrev1 = :DataPrev1, DataPrev2 = :DataPrev2, SaldoInicial = :SaldoInicial, SaldoFinal = :SaldoFinal, Empresa = :Empresa, SaldoInicialBanco = :SaldoInicialBanco, SaldoFinalBanco = :SaldoFinalBanco WHERE CodCaixa = :CodCaixa';
            $statement = $this->connection->getPdo()->prepare($sql);
            $statement->execute($params);

            return $caixa->getId();
        }

        $sql = 'INSERT INTO tcaixa (DataCaixa, DataPrev1, DataPrev2, SaldoInicial, SaldoFinal, Empresa, SaldoInicialBanco, SaldoFinalBanco) VALUES (:DataCaixa, :DataPrev1, :DataPrev2, :SaldoInicial, :SaldoFinal, :Empresa, :SaldoInicialBanco, :SaldoFinalBanco)';
        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute($params);

        return (int) $this->connection->getPdo()->lastInsertId();
    }

    public function fecharCaixa(int $id, float $saldoFinal, float $saldoFinalBanco): bool
    {
        $sql = 'UPDATE tcaixa SET SaldoFinal = :saldoFinal, SaldoFinalBanco = :saldoFinalBanco WHERE CodCaixa = :id';
        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute([
            ':saldoFinal' => $saldoFinal,
            ':saldoFinalBanco' => $saldoFinalBanco,
            ':id' => $id,
        ]);

        return $statement->rowCount() > 0;
    }
}