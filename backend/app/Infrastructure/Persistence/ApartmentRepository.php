<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Apartment\Apartment;
use App\Domain\Apartment\ApartmentRepositoryInterface;
use App\Infrastructure\Database\Connection;
use PDO;

class ApartmentRepository implements ApartmentRepositoryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function listarAtivos(): array
    {
        $sql = 'SELECT
                    ta.CodApartamento AS id,
                    ta.Num AS numero,
                    ta.Status AS status,
                    ta.Tipo AS tipoId,
                    tta.Descricao AS tipoDescricao,
                    ta.Ativo AS ativo,
                    ta.Empresa AS empresaId
                FROM tapartamentos ta
                LEFT JOIN ttipo_apartamento tta ON ta.Tipo = tta.CodigoTipo
                WHERE ta.Ativo = "S"
                ORDER BY tta.Descricao ASC, ta.Num ASC';

        $stmt = $this->connection->getPdo()->prepare($sql);
        $stmt->execute();

        $apartments = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $apartments[] = new Apartment(
                id: (int) $row['id'],
                numero: (int) $row['numero'],
                status: $row['status'],
                tipoId: (int) $row['tipoId'],
                tipoDescricao: $row['tipoDescricao'],
                ativo: $row['ativo'] === 'S',
                empresaId: (int) $row['empresaId']
            );
        }

        return $apartments;
    }

    public function buscarPorId(int $id): ?Apartment
    {
        $sql = 'SELECT
                    ta.CodApartamento AS id,
                    ta.Num AS numero,
                    ta.Status AS status,
                    ta.Tipo AS tipoId,
                    tta.Descricao AS tipoDescricao,
                    ta.Ativo AS ativo,
                    ta.Empresa AS empresaId
                FROM tapartamentos ta
                LEFT JOIN ttipo_apartamento tta ON ta.Tipo = tta.CodigoTipo
                WHERE ta.CodApartamento = :id';

        $stmt = $this->connection->getPdo()->prepare($sql);
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return new Apartment(
            id: (int) $row['id'],
            numero: (int) $row['numero'],
            status: $row['status'],
            tipoId: (int) $row['tipoId'],
            tipoDescricao: $row['tipoDescricao'],
            ativo: $row['ativo'] === 'S',
            empresaId: (int) $row['empresaId']
        );
    }

    public function buscarPorNumero(int $numero): ?Apartment
    {
        $sql = 'SELECT
                    ta.CodApartamento AS id,
                    ta.Num AS numero,
                    ta.Status AS status,
                    ta.Tipo AS tipoId,
                    tta.Descricao AS tipoDescricao,
                    ta.Ativo AS ativo,
                    ta.Empresa AS empresaId
                FROM tapartamentos ta
                LEFT JOIN ttipo_apartamento tta ON ta.Tipo = tta.CodigoTipo
                WHERE ta.Num = :numero AND ta.Ativo = "S"';

        $stmt = $this->connection->getPdo()->prepare($sql);
        $stmt->execute([':numero' => $numero]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return new Apartment(
            id: (int) $row['id'],
            numero: (int) $row['numero'],
            status: $row['status'],
            tipoId: (int) $row['tipoId'],
            tipoDescricao: $row['tipoDescricao'],
            ativo: $row['ativo'] === 'S',
            empresaId: (int) $row['empresaId']
        );
    }

    public function listarPorTipo(int $tipoId): array
    {
        $sql = 'SELECT
                    ta.CodApartamento AS id,
                    ta.Num AS numero,
                    ta.Status AS status,
                    ta.Tipo AS tipoId,
                    tta.Descricao AS tipoDescricao,
                    ta.Ativo AS ativo,
                    ta.Empresa AS empresaId
                FROM tapartamentos ta
                LEFT JOIN ttipo_apartamento tta ON ta.Tipo = tta.CodigoTipo
                WHERE ta.Tipo = :tipoId AND ta.Ativo = "S"
                ORDER BY ta.Num ASC';

        $stmt = $this->connection->getPdo()->prepare($sql);
        $stmt->execute([':tipoId' => $tipoId]);

        $apartments = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $apartments[] = new Apartment(
                id: (int) $row['id'],
                numero: (int) $row['numero'],
                status: $row['status'],
                tipoId: (int) $row['tipoId'],
                tipoDescricao: $row['tipoDescricao'],
                ativo: $row['ativo'] === 'S',
                empresaId: (int) $row['empresaId']
            );
        }

        return $apartments;
    }

    public function atualizarStatus(int $id, string $status): bool
    {
        $sql = 'UPDATE tapartamentos SET Status = :status WHERE CodApartamento = :id';

        $stmt = $this->connection->getPdo()->prepare($sql);
        return $stmt->execute([
            ':status' => $status,
            ':id' => $id
        ]);
    }
}