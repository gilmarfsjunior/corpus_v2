<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Venda\ListarVendasUseCase;
use App\Application\Venda\ObterVendaUseCase;
use App\Application\Venda\SalvarVendaUseCase;
use App\Domain\Venda\Venda;
use App\Infrastructure\Database\Connection;
use App\Infrastructure\Persistence\VendaRepository;
use App\Shared\Http\Response;

class VendaController
{
    private Connection $connection;
    private VendaRepository $repository;

    public function __construct()
    {
        $config = require dirname(__DIR__, 4) . '/config/database.php';
        $this->connection = new Connection($config);
        $this->repository = new VendaRepository($this->connection);
    }

    public function index(): Response
    {
        $dataVenda = trim($_GET['dataVenda'] ?? '') ?: null;
        $status = trim($_GET['status'] ?? '') ?: null;
        $empresa = trim($_GET['empresa'] ?? '') ?: null;

        $useCase = new ListarVendasUseCase($this->repository);
        $vendas = array_map(static function ($venda) {
            return $venda->toArray();
        }, $useCase->execute($dataVenda, $status, $empresa));

        return Response::json(['data' => $vendas]);
    }

    public function show(int $id): Response
    {
        $useCase = new ObterVendaUseCase($this->repository);
        $venda = $useCase->execute($id);

        if ($venda === null) {
            return Response::json(['message' => 'Venda não encontrada'], 404);
        }

        return Response::json(['data' => $venda->toArray()]);
    }

    public function store(): Response
    {
        $data = $this->getRequestData();
        $venda = $this->createVendaFromData($data);

        $useCase = new SalvarVendaUseCase($this->repository);
        $id = $useCase->execute($venda);

        return Response::json(['data' => ['id' => $id]], 201);
    }

    public function update(int $id): Response
    {
        $data = $this->getRequestData();
        $data['id'] = $id;
        $venda = $this->createVendaFromData($data);

        $useCase = new SalvarVendaUseCase($this->repository);
        $id = $useCase->execute($venda);

        return Response::json(['data' => ['id' => $id]]);
    }

    private function createVendaFromData(array $data): Venda
    {
        return new Venda(
            isset($data['id']) ? (int) $data['id'] : 0,
            isset($data['CodCliente']) ? (int) $data['CodCliente'] : null,
            isset($data['CodVendedor']) ? (int) $data['CodVendedor'] : null,
            $data['DataVenda'] ?? null,
            $data['Usuario'] ?? null,
            $data['Empresa'] ?? null,
            $data['Data'] ?? null,
            $data['Hora'] ?? null,
            isset($data['TotalVenda']) ? (float) $data['TotalVenda'] : null,
            isset($data['Desconto']) ? (float) $data['Desconto'] : null,
            isset($data['DespesasViagem']) ? (float) $data['DespesasViagem'] : null,
            $data['Status'] ?? null,
            isset($data['CodMovimento']) ? (int) $data['CodMovimento'] : null
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
