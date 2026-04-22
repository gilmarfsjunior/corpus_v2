<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Apartment\ListarApartmentsUseCase;
use App\Application\Apartment\BuscarApartmentUseCase;
use App\Application\Apartment\AlterarStatusApartmentUseCase;
use App\Domain\Apartment\Apartment;
use App\Infrastructure\Database\Connection;
use App\Infrastructure\Persistence\ApartmentRepository;
use App\Shared\Http\Response;

class ApartmentController
{
    private Connection $connection;
    private ApartmentRepository $repository;

    public function __construct()
    {
        $config = require dirname(__DIR__, 4) . '/config/database.php';
        $this->connection = new Connection($config);
        $this->repository = new ApartmentRepository($this->connection);
    }

    public function index(): Response
    {
        $useCase = new ListarApartmentsUseCase($this->repository);
        $apartments = array_map(static function (Apartment $apartment): array {
            return [
                'id' => $apartment->getId(),
                'numero' => $apartment->getNumero(),
                'status' => $apartment->getStatus(),
                'statusLabel' => $apartment->getStatusLabel(),
                'statusColor' => $apartment->getStatusColor(),
                'tipoId' => $apartment->getTipoId(),
                'tipoDescricao' => $apartment->getTipoDescricao(),
                'ativo' => $apartment->isAtivo(),
                'empresaId' => $apartment->getEmpresaId(),
            ];
        }, $useCase->execute());

        return Response::json(['data' => $apartments]);
    }

    public function show(int $id): Response
    {
        $useCase = new BuscarApartmentUseCase($this->repository);
        $apartment = $useCase->execute($id);

        if ($apartment === null) {
            return Response::json(['message' => 'Apartamento não encontrado'], 404);
        }

        return Response::json(['data' => [
            'id' => $apartment->getId(),
            'numero' => $apartment->getNumero(),
            'status' => $apartment->getStatus(),
            'statusLabel' => $apartment->getStatusLabel(),
            'statusColor' => $apartment->getStatusColor(),
            'tipoId' => $apartment->getTipoId(),
            'tipoDescricao' => $apartment->getTipoDescricao(),
            'ativo' => $apartment->isAtivo(),
            'empresaId' => $apartment->getEmpresaId(),
        ]]);
    }

    public function updateStatus(int $id): Response
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $status = $input['status'] ?? null;

        if (!$status) {
            return Response::json(['message' => 'Status é obrigatório'], 400);
        }

        $useCase = new AlterarStatusApartmentUseCase($this->repository);
        $success = $useCase->execute($id, $status);

        if (!$success) {
            return Response::json(['message' => 'Erro ao atualizar status'], 500);
        }

        return Response::json(['message' => 'Status atualizado com sucesso']);
    }
}