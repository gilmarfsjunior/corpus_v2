<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Pago\ListarPagosUseCase;
use App\Application\Pago\ObterPagoUseCase;
use App\Application\Pago\SalvarPagoUseCase;
use App\Domain\Pago\Pago;
use App\Infrastructure\Database\Connection;
use App\Infrastructure\Persistence\PagoRepository;
use App\Shared\Http\Response;

class PagoController
{
    private Connection $connection;
    private PagoRepository $repository;

    public function __construct()
    {
        $config = require dirname(__DIR__, 4) . '/config/database.php';
        $this->connection = new Connection($config);
        $this->repository = new PagoRepository($this->connection);
    }

    public function index(): Response
    {
        $codParcela = trim($_GET['codParcela'] ?? '') ?: null;
        $empresa = trim($_GET['empresa'] ?? '') ?: null;

        $useCase = new ListarPagosUseCase($this->repository);
        $pagos = array_map(static function ($pago) {
            return $pago->toArray();
        }, $useCase->execute($codParcela ? (int) $codParcela : null, $empresa));

        return Response::json(['data' => $pagos]);
    }

    public function show(int $id): Response
    {
        $useCase = new ObterPagoUseCase($this->repository);
        $pago = $useCase->execute($id);

        if ($pago === null) {
            return Response::json(['message' => 'Pago não encontrado'], 404);
        }

        return Response::json(['data' => $pago->toArray()]);
    }

    public function store(): Response
    {
        $data = $this->getRequestData();
        $pago = $this->createPagoFromData($data);

        $useCase = new SalvarPagoUseCase($this->repository);
        $id = $useCase->execute($pago);

        return Response::json(['data' => ['id' => $id]], 201);
    }

    public function update(int $id): Response
    {
        $data = $this->getRequestData();
        $data['id'] = $id;
        $pago = $this->createPagoFromData($data);

        $useCase = new SalvarPagoUseCase($this->repository);
        $id = $useCase->execute($pago);

        return Response::json(['data' => ['id' => $id]]);
    }

    private function createPagoFromData(array $data): Pago
    {
        return new Pago(
            isset($data['id']) ? (int) $data['id'] : 0,
            isset($data['codParcela']) ? (int) $data['codParcela'] : null,
            $data['dataPagamento'] ?? null,
            isset($data['valorPago']) ? (float) $data['valorPago'] : null,
            isset($data['diasAtraso']) ? (int) $data['diasAtraso'] : null,
            isset($data['moraDiaria']) ? (float) $data['moraDiaria'] : null,
            isset($data['valorJuros']) ? (float) $data['valorJuros'] : null,
            isset($data['amortizado']) ? (float) $data['amortizado'] : null,
            $data['banco'] ?? null,
            $data['obs'] ?? null,
            $data['formaPagamento'] ?? null,
            $data['chequeComp'] ?? null,
            $data['numCheque'] ?? null,
            $data['empresa'] ?? null,
            $data['statusTipo'] ?? null
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