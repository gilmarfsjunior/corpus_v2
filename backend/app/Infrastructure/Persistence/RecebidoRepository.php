<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Recebido\Recebido;
use App\Domain\Recebido\RecebidoRepositoryInterface;
use App\Infrastructure\Database\Connection;
use PDO;

class RecebidoRepository implements RecebidoRepositoryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function listar(?int $codParcela = null, ?string $empresa = null): array
    {
        $sql = 'SELECT trecebido.CodRecebimento AS id, trecebido.* FROM trecebido WHERE 1=1';
        $params = [];

        if ($codParcela !== null) {
            $sql .= ' AND CodParcela = :codParcela';
            $params[':codParcela'] = $codParcela;
        }

        if ($empresa !== null && trim($empresa) !== '') {
            $sql .= ' AND Empresa = :empresa';
            $params[':empresa'] = $empresa;
        }

        $sql .= ' ORDER BY CodRecebimento DESC LIMIT 100';

        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute($params);

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(static function (array $row): Recebido {
            return Recebido::fromDatabaseRow($row);
        }, $rows);
    }

    public function buscarPorId(int $id): ?Recebido
    {
        $sql = 'SELECT trecebido.CodRecebimento AS id, trecebido.* FROM trecebido WHERE CodRecebimento = :id LIMIT 1';
        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute([':id' => $id]);

        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }

        return Recebido::fromDatabaseRow($row);
    }

    public function salvar(Recebido $recebido): int
    {
        $params = [
            ':CodParcela' => $recebido->getCodParcela(),
            ':DataRecebimento' => $recebido->getDataRecebimento(),
            ':ValorRecebido' => $recebido->getValorRecebido(),
            ':DiasAtraso' => $recebido->getDiasAtraso(),
            ':MoraDiaria' => $recebido->getMoraDiaria(),
            ':ValorJuros' => $recebido->getValorJuros(),
            ':Amortizado' => $recebido->getAmortizado(),
            ':Banco' => $recebido->getBanco(),
            ':Obs' => $recebido->getObs(),
            ':FormaPagamento' => $recebido->getFormaPagamento(),
            ':ChequeComp' => $recebido->getChequeComp(),
            ':NumCheque' => $recebido->getNumCheque(),
            ':StatusTipo' => $recebido->getStatusTipo(),
            ':Empresa' => $recebido->getEmpresa(),
            ':CodCliente' => $recebido->getCodCliente(),
        ];

        if ($recebido->getId() > 0) {
            $params[':CodRecebimento'] = $recebido->getId();
            $sql = 'UPDATE trecebido SET CodParcela = :CodParcela, DataRecebimento = :DataRecebimento, ValorRecebido = :ValorRecebido, DiasAtraso = :DiasAtraso, MoraDiaria = :MoraDiaria, ValorJuros = :ValorJuros, Amortizado = :Amortizado, Banco = :Banco, Obs = :Obs, FormaPagamento = :FormaPagamento, ChequeComp = :ChequeComp, NumCheque = :NumCheque, StatusTipo = :StatusTipo, Empresa = :Empresa, CodCliente = :CodCliente WHERE CodRecebimento = :CodRecebimento';
            $statement = $this->connection->getPdo()->prepare($sql);
            $statement->execute($params);

            return $recebido->getId();
        }

        $sql = 'INSERT INTO trecebido (CodParcela, DataRecebimento, ValorRecebido, DiasAtraso, MoraDiaria, ValorJuros, Amortizado, Banco, Obs, FormaPagamento, ChequeComp, NumCheque, StatusTipo, Empresa, CodCliente) VALUES (:CodParcela, :DataRecebimento, :ValorRecebido, :DiasAtraso, :MoraDiaria, :ValorJuros, :Amortizado, :Banco, :Obs, :FormaPagamento, :ChequeComp, :NumCheque, :StatusTipo, :Empresa, :CodCliente)';
        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute($params);

        return (int) $this->connection->getPdo()->lastInsertId();
    }
}