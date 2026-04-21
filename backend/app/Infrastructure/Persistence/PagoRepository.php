<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Pago\Pago;
use App\Domain\Pago\PagoRepositoryInterface;
use App\Infrastructure\Database\Connection;
use PDO;

class PagoRepository implements PagoRepositoryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function listar(?int $codParcela = null, ?string $empresa = null): array
    {
        $sql = 'SELECT tpago.CodPagamento AS id, tpago.* FROM tpago WHERE 1=1';
        $params = [];

        if ($codParcela !== null) {
            $sql .= ' AND CodParcela = :codParcela';
            $params[':codParcela'] = $codParcela;
        }

        if ($empresa !== null && trim($empresa) !== '') {
            $sql .= ' AND Empresa = :empresa';
            $params[':empresa'] = $empresa;
        }

        $sql .= ' ORDER BY CodPagamento DESC LIMIT 100';

        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute($params);

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(static function (array $row): Pago {
            return Pago::fromDatabaseRow($row);
        }, $rows);
    }

    public function buscarPorId(int $id): ?Pago
    {
        $sql = 'SELECT tpago.CodPagamento AS id, tpago.* FROM tpago WHERE CodPagamento = :id';
        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute([':id' => $id]);

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return Pago::fromDatabaseRow($row);
    }

    public function salvar(Pago $pago): int
    {
        $data = $pago->toArray();

        if ($pago->getId() === 0) {
            // Insert
            $sql = 'INSERT INTO tpago (CodParcela, DataPagamento, ValorPago, DiasAtraso, MoraDiaria, ValorJuros, Amortizado, Banco, Obs, FormaPagamento, ChequeComp, NumCheque, Empresa, StatusTipo) 
                    VALUES (:codParcela, :dataPagamento, :valorPago, :diasAtraso, :moraDiaria, :valorJuros, :amortizado, :banco, :obs, :formaPagamento, :chequeComp, :numCheque, :empresa, :statusTipo)';
            $statement = $this->connection->getPdo()->prepare($sql);
            $statement->execute([
                ':codParcela' => $data['codParcela'],
                ':dataPagamento' => $data['dataPagamento'],
                ':valorPago' => $data['valorPago'],
                ':diasAtraso' => $data['diasAtraso'],
                ':moraDiaria' => $data['moraDiaria'],
                ':valorJuros' => $data['valorJuros'],
                ':amortizado' => $data['amortizado'],
                ':banco' => $data['banco'],
                ':obs' => $data['obs'],
                ':formaPagamento' => $data['formaPagamento'],
                ':chequeComp' => $data['chequeComp'],
                ':numCheque' => $data['numCheque'],
                ':empresa' => $data['empresa'],
                ':statusTipo' => $data['statusTipo'],
            ]);
            return (int) $this->connection->getPdo()->lastInsertId();
        } else {
            // Update
            $sql = 'UPDATE tpago SET CodParcela = :codParcela, DataPagamento = :dataPagamento, 
                    ValorPago = :valorPago, DiasAtraso = :diasAtraso, MoraDiaria = :moraDiaria, 
                    ValorJuros = :valorJuros, Amortizado = :amortizado, Banco = :banco, Obs = :obs, 
                    FormaPagamento = :formaPagamento, ChequeComp = :chequeComp, NumCheque = :numCheque, 
                    Empresa = :empresa, StatusTipo = :statusTipo WHERE CodPagamento = :id';
            $statement = $this->connection->getPdo()->prepare($sql);
            $statement->execute([
                ':id' => $data['id'],
                ':codParcela' => $data['codParcela'],
                ':dataPagamento' => $data['dataPagamento'],
                ':valorPago' => $data['valorPago'],
                ':diasAtraso' => $data['diasAtraso'],
                ':moraDiaria' => $data['moraDiaria'],
                ':valorJuros' => $data['valorJuros'],
                ':amortizado' => $data['amortizado'],
                ':banco' => $data['banco'],
                ':obs' => $data['obs'],
                ':formaPagamento' => $data['formaPagamento'],
                ':chequeComp' => $data['chequeComp'],
                ':numCheque' => $data['numCheque'],
                ':empresa' => $data['empresa'],
                ':statusTipo' => $data['statusTipo'],
            ]);
            return $pago->getId();
        }
    }
}