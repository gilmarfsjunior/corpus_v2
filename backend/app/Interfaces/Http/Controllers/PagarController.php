<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Pagar\ListarPagarsUseCase;
use App\Application\Pagar\ObterPagarUseCase;
use App\Application\Pagar\SalvarPagarUseCase;
use App\Application\Pagar\PagarPagarUseCase;
use App\Domain\Pagar\Pagar;
use App\Infrastructure\Database\Connection;
use App\Infrastructure\Persistence\PagarRepository;
use App\Shared\Http\Response;

class PagarController
{
    private Connection $connection;
    private PagarRepository $repository;

    public function __construct()
    {
        $config = require dirname(__DIR__, 4) . '/config/database.php';
        $this->connection = new Connection($config);
        $this->repository = new PagarRepository($this->connection);
    }

    public function index(): Response
    {
        $codNotaCompra = trim($_GET['codNotaCompra'] ?? '') ?: null;
        $status = trim($_GET['status'] ?? '') ?: null;
        $empresa = trim($_GET['empresa'] ?? '') ?: null;

        $useCase = new ListarPagarsUseCase($this->repository);
        $pagars = array_map(static function ($pagar) {
            return $pagar->toArray();
        }, $useCase->execute($codNotaCompra ? (int) $codNotaCompra : null, $status, $empresa));

        return Response::json(['data' => $pagars]);
    }

    public function show(int $id): Response
    {
        $useCase = new ObterPagarUseCase($this->repository);
        $pagar = $useCase->execute($id);

        if ($pagar === null) {
            return Response::json(['message' => 'Pagar não encontrado'], 404);
        }

        return Response::json(['data' => $pagar->toArray()]);
    }

    public function store(): Response
    {
        $data = $this->getRequestData();
        $pagar = $this->createPagarFromData($data);

        $useCase = new SalvarPagarUseCase($this->repository);
        $id = $useCase->execute($pagar);

        return Response::json(['data' => ['id' => $id]], 201);
    }

    public function update(int $id): Response
    {
        $data = $this->getRequestData();
        $data['id'] = $id;
        $pagar = $this->createPagarFromData($data);

        $useCase = new SalvarPagarUseCase($this->repository);
        $id = $useCase->execute($pagar);

        return Response::json(['data' => ['id' => $id]]);
    }

    public function pagar(int $id): Response
    {
        $useCase = new PagarPagarUseCase($this->repository);
        $success = $useCase->execute($id);

        if (!$success) {
            return Response::json(['message' => 'Erro ao registrar pagamento'], 400);
        }

        return Response::json(['message' => 'Pagamento registrado com sucesso']);
    }

    private function createPagarFromData(array $data): Pagar
    {
        return new Pagar(
            isset($data['id']) ? (int) $data['id'] : 0,
            isset($data['codNotaCompra']) ? (int) $data['codNotaCompra'] : null,
            $data['dataVencimento'] ?? null,
            isset($data['valorParcela']) ? (float) $data['valorParcela'] : null,
            isset($data['saldoParcela']) ? (float) $data['saldoParcela'] : null,
            $data['status'] ?? null,
            $data['empresa'] ?? null,
            isset($data['parcelaRef']) ? (int) $data['parcelaRef'] : null
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