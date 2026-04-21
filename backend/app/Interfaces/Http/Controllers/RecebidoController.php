<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Recebido\ListarRecebidosUseCase;
use App\Application\Recebido\ObterRecebidoUseCase;
use App\Application\Recebido\SalvarRecebidoUseCase;
use App\Domain\Recebido\Recebido;
use App\Infrastructure\Database\Connection;
use App\Infrastructure\Persistence\RecebidoRepository;
use App\Shared\Http\Response;

class RecebidoController
{
    private Connection $connection;
    private RecebidoRepository $repository;

    public function __construct()
    {
        $config = require dirname(__DIR__, 4) . '/config/database.php';
        $this->connection = new Connection($config);
        $this->repository = new RecebidoRepository($this->connection);
    }

    public function index(): Response
    {
        $codParcela = trim($_GET['codParcela'] ?? '') ?: null;
        $empresa = trim($_GET['empresa'] ?? '') ?: null;

        $useCase = new ListarRecebidosUseCase($this->repository);
        $recebidos = array_map(static function ($recebido) {
            return $recebido->toArray();
        }, $useCase->execute($codParcela ? (int) $codParcela : null, $empresa));

        return Response::json(['data' => $recebidos]);
    }

    public function show(int $id): Response
    {
        $useCase = new ObterRecebidoUseCase($this->repository);
        $recebido = $useCase->execute($id);

        if ($recebido === null) {
            return Response::json(['message' => 'Recebido não encontrado'], 404);
        }

        return Response::json(['data' => $recebido->toArray()]);
    }

    public function store(): Response
    {
        $data = $this->getRequestData();
        $recebido = $this->createRecebidoFromData($data);

        $useCase = new SalvarRecebidoUseCase($this->repository);
        $id = $useCase->execute($recebido);

        return Response::json(['data' => ['id' => $id]], 201);
    }

    public function update(int $id): Response
    {
        $data = $this->getRequestData();
        $data['id'] = $id;
        $recebido = $this->createRecebidoFromData($data);

        $useCase = new SalvarRecebidoUseCase($this->repository);
        $id = $useCase->execute($recebido);

        return Response::json(['data' => ['id' => $id]]);
    }

    private function createRecebidoFromData(array $data): Recebido
    {
        return new Recebido(
            isset($data['id']) ? (int) $data['id'] : 0,
            isset($data['codParcela']) ? (int) $data['codParcela'] : null,
            $data['dataRecebimento'] ?? null,
            isset($data['valorRecebido']) ? (float) $data['valorRecebido'] : null,
            isset($data['diasAtraso']) ? (int) $data['diasAtraso'] : null,
            isset($data['moraDiaria']) ? (float) $data['moraDiaria'] : null,
            isset($data['valorJuros']) ? (float) $data['valorJuros'] : null,
            isset($data['amortizado']) ? (float) $data['amortizado'] : null,
            $data['banco'] ?? null,
            $data['obs'] ?? null,
            $data['formaPagamento'] ?? null,
            $data['chequeComp'] ?? null,
            $data['numCheque'] ?? null,
            $data['statusTipo'] ?? null,
            $data['empresa'] ?? null,
            isset($data['codCliente']) ? (int) $data['codCliente'] : null
        );
    }

    private function getRequestData(): array
    {
        $body = file_get_contents('php://input');
        $data = json_decode($body, true);

        if (is_array($data)) {
            return $data;
        }

        return $_POST;
    }
}
