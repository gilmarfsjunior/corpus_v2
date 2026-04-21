<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Movimento\Movimento;
use App\Domain\Movimento\MovimentoRepositoryInterface;
use App\Infrastructure\Database\Connection;
use PDO;

class MovimentoRepository implements MovimentoRepositoryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function listar(?string $dataAbertura = null, ?string $status = null, ?string $empresa = null): array
    {
        $sql = 'SELECT tmovimento.CodMovimento AS id, tmovimento.* FROM tmovimento WHERE 1=1';
        $params = [];

        if ($empresa !== null && trim($empresa) !== '') {
            $sql .= ' AND Empresa = :empresa';
            $params[':empresa'] = $empresa;
        }

        if ($dataAbertura !== null && trim($dataAbertura) !== '') {
            $sql .= ' AND DataAbertura = :dataAbertura';
            $params[':dataAbertura'] = $dataAbertura;
        }

        if ($status !== null && trim($status) !== '') {
            $sql .= ' AND Status = :status';
            $params[':status'] = $status;
        }

        $sql .= ' ORDER BY CodMovimento DESC LIMIT 100';

        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute($params);

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(static function (array $row): Movimento {
            return Movimento::fromDatabaseRow($row);
        }, $rows);
    }

    public function buscarPorId(int $id): ?Movimento
    {
        $sql = 'SELECT tmovimento.CodMovimento AS id, tmovimento.* FROM tmovimento WHERE CodMovimento = :id LIMIT 1';
        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute([':id' => $id]);

        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }

        return Movimento::fromDatabaseRow($row);
    }

    public function abrir(Movimento $movimento): int
    {
        $sql = 'INSERT INTO tmovimento (CodUsuario, DataAbertura, HoraAbertura, Empresa, Status, Observacao) VALUES (:CodUsuario, :DataAbertura, :HoraAbertura, :Empresa, :Status, :Observacao)';
        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute([
            ':CodUsuario' => $movimento->getUsuarioId(),
            ':DataAbertura' => $movimento->getDataAbertura(),
            ':HoraAbertura' => $movimento->getHoraAbertura(),
            ':Empresa' => $movimento->getEmpresa(),
            ':Status' => $movimento->getStatus() ?? 'A',
            ':Observacao' => $movimento->getObservacao(),
        ]);

        return (int) $this->connection->getPdo()->lastInsertId();
    }

    public function fechar(int $id, ?string $observacao = null): bool
    {
        $dataFechamento = date('Y-m-d');
        $horaFechamento = date('H:i');

        $sql = 'UPDATE tmovimento SET DataFechamento = :DataFechamento, HoraFechamento = :HoraFechamento, Status = :Status';
        if ($observacao !== null) {
            $sql .= ', Observacao = :Observacao';
        }
        $sql .= ' WHERE CodMovimento = :CodMovimento';

        $params = [
            ':DataFechamento' => $dataFechamento,
            ':HoraFechamento' => $horaFechamento,
            ':Status' => 'F',
            ':CodMovimento' => $id,
        ];

        if ($observacao !== null) {
            $params[':Observacao'] = $observacao;
        }

        $statement = $this->connection->getPdo()->prepare($sql);
        return $statement->execute($params);
    }
}
