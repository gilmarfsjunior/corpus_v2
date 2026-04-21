<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Recebimento\ListarRecebimentosUseCase;
use App\Application\Recebimento\ObterRecebimentoUseCase;
use App\Application\Recebimento\SalvarRecebimentoUseCase;
use App\Application\Recebimento\ReceberRecebimentoUseCase;
use App\Domain\Recebimento\Recebimento;
use App\Infrastructure\Database\Connection;
use App\Infrastructure\Persistence\RecebimentoRepository;
use App\Shared\Http\Response;

class RecebimentoController
{
    private Connection $connection;
    private RecebimentoRepository $repository;

    public function __construct()
    {
        $config = require dirname(__DIR__, 4) . '/config/database.php';
        $this->connection = new Connection($config);
        $this->repository = new RecebimentoRepository($this->connection);
    }

    public function index(): Response
    {
        $codPedido = trim($_GET['codPedido'] ?? '') ?: null;
        $status = trim($_GET['status'] ?? '') ?: null;
        $empresa = trim($_GET['empresa'] ?? '') ?: null;

        $useCase = new ListarRecebimentosUseCase($this->repository);
        $recebimentos = array_map(static function ($recebimento) {
            return $recebimento->toArray();
        }, $useCase->execute($codPedido ? (int) $codPedido : null, $status, $empresa));

        return Response::json(['data' => $recebimentos]);
    }

    public function show(int $id): Response
    {
        $useCase = new ObterRecebimentoUseCase($this->repository);
        $recebimento = $useCase->execute($id);

        if ($recebimento === null) {
            return Response::json(['message' => 'Recebimento não encontrado'], 404);
        }

        return Response::json(['data' => $recebimento->toArray()]);
    }

    public function store(): Response
    {
        $data = $this->getRequestData();
        $recebimento = $this->createRecebimentoFromData($data);

        $useCase = new SalvarRecebimentoUseCase($this->repository);
        $id = $useCase->execute($recebimento);

        return Response::json(['data' => ['id' => $id]], 201);
    }

    public function update(int $id): Response
    {
        $data = $this->getRequestData();
        $data['id'] = $id;
        $recebimento = $this->createRecebimentoFromData($data);

        $useCase = new SalvarRecebimentoUseCase($this->repository);
        $id = $useCase->execute($recebimento);

        return Response::json(['data' => ['id' => $id]]);
    }

    public function receber(int $id): Response
    {
        $data = $this->getRequestData();
        $valorRecebido = isset($data['valorRecebido']) ? (float) $data['valorRecebido'] : 0;

        $useCase = new ReceberRecebimentoUseCase($this->repository);
        $success = $useCase->execute($id, $valorRecebido);

        if (!$success) {
            return Response::json(['message' => 'Erro ao registrar recebimento'], 400);
        }

        return Response::json(['message' => 'Recebimento registrado com sucesso']);
    }

    private function createRecebimentoFromData(array $data): Recebimento
    {
        return new Recebimento(
            isset($data['id']) ? (int) $data['id'] : 0,
            isset($data['codPedido']) ? (int) $data['codPedido'] : null,
            $data['dataVencimento'] ?? null,
            isset($data['valorParcela']) ? (float) $data['valorParcela'] : null,
            isset($data['saldoParcela']) ? (float) $data['saldoParcela'] : null,
            isset($data['valorRecebido']) ? (float) $data['valorRecebido'] : null,
            $data['notaFParcela'] ?? null,
            $data['dataPrevReceb'] ?? null,
            $data['status'] ?? null,
            $data['empresa'] ?? null,
            isset($data['parcelaRef']) ? (int) $data['parcelaRef'] : null,
            $data['ativa'] ?? null
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