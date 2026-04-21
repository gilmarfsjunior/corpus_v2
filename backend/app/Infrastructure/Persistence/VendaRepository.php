<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Venda\Venda;
use App\Domain\Venda\VendaRepositoryInterface;
use App\Infrastructure\Database\Connection;
use PDO;

class VendaRepository implements VendaRepositoryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function listar(?string $dataVenda = null, ?string $status = null, ?string $empresa = null): array
    {
        $sql = 'SELECT tvendas.CodVenda AS id, tvendas.* FROM tvendas WHERE 1=1';
        $params = [];

        if ($empresa !== null && trim($empresa) !== '') {
            $sql .= ' AND Empresa = :empresa';
            $params[':empresa'] = $empresa;
        }

        if ($dataVenda !== null && trim($dataVenda) !== '') {
            $sql .= ' AND DataVenda = :dataVenda';
            $params[':dataVenda'] = $dataVenda;
        }

        if ($status !== null && trim($status) !== '') {
            $sql .= ' AND Status = :status';
            $params[':status'] = $status;
        }

        $sql .= ' ORDER BY CodVenda DESC LIMIT 100';

        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute($params);

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(static function (array $row): Venda {
            return Venda::fromDatabaseRow($row);
        }, $rows);
    }

    public function buscarPorId(int $id): ?Venda
    {
        $sql = 'SELECT tvendas.CodVenda AS id, tvendas.* FROM tvendas WHERE CodVenda = :id LIMIT 1';
        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute([':id' => $id]);

        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }

        return Venda::fromDatabaseRow($row);
    }

    public function salvar(Venda $venda): int
    {
        $params = [
            ':CodCliente' => $venda->getClienteId(),
            ':CodVendedor' => $venda->getVendedorId(),
            ':DataVenda' => $venda->getDataVenda(),
            ':Usuario' => $venda->getUsuario(),
            ':Empresa' => $venda->getEmpresa(),
            ':Data' => $venda->getData(),
            ':Hora' => $venda->getHora(),
            ':TotalVenda' => $venda->getTotalVenda(),
            ':Desconto' => $venda->getDesconto(),
            ':DespesasViagem' => $venda->getDespesasViagem(),
            ':Status' => $venda->getStatus(),
            ':CodMovimento' => $venda->getMovimentoId(),
        ];

        if ($venda->getId() > 0) {
            $params[':CodVenda'] = $venda->getId();
            $sql = 'UPDATE tvendas SET CodCliente = :CodCliente, CodVendedor = :CodVendedor, DataVenda = :DataVenda, Usuario = :Usuario, Empresa = :Empresa, Data = :Data, Hora = :Hora, TotalVenda = :TotalVenda, Desconto = :Desconto, DespesasViagem = :DespesasViagem, Status = :Status, CodMovimento = :CodMovimento WHERE CodVenda = :CodVenda';
            $statement = $this->connection->getPdo()->prepare($sql);
            $statement->execute($params);

            return $venda->getId();
        }

        $sql = 'INSERT INTO tvendas (CodCliente, CodVendedor, DataVenda, Usuario, Empresa, Data, Hora, TotalVenda, Desconto, DespesasViagem, Status, CodMovimento) VALUES (:CodCliente, :CodVendedor, :DataVenda, :Usuario, :Empresa, :Data, :Hora, :TotalVenda, :Desconto, :DespesasViagem, :Status, :CodMovimento)';
        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute($params);

        return (int) $this->connection->getPdo()->lastInsertId();
    }
}
