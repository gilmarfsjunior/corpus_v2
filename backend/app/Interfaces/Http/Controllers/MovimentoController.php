<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Movimento\AbrirMovimentoUseCase;
use App\Application\Movimento\FecharMovimentoUseCase;
use App\Application\Movimento\ListarMovimentosUseCase;
use App\Application\Movimento\ObterMovimentoUseCase;
use App\Domain\Movimento\Movimento;
use App\Infrastructure\Database\Connection;
use App\Infrastructure\Persistence\MovimentoRepository;
use App\Shared\Http\Response;

class MovimentoController
{
    private Connection $connection;
    private MovimentoRepository $repository;

    public function __construct()
    {
        $config = require dirname(__DIR__, 4) . '/config/database.php';
        $this->connection = new Connection($config);
        $this->repository = new MovimentoRepository($this->connection);
    }

    public function index(): Response
    {
        $dataAbertura = trim($_GET['dataAbertura'] ?? '') ?: null;
        $status = trim($_GET['status'] ?? '') ?: null;
        $empresa = trim($_GET['empresa'] ?? '') ?: null;

        $useCase = new ListarMovimentosUseCase($this->repository);
        $movimentos = array_map(static function ($movimento) {
            return $movimento->toArray();
        }, $useCase->execute($dataAbertura, $status, $empresa));

        return Response::json(['data' => $movimentos]);
    }

    public function show(int $id): Response
    {
        $useCase = new ObterMovimentoUseCase($this->repository);
        $movimento = $useCase->execute($id);

        if ($movimento === null) {
            return Response::json(['message' => 'Movimento não encontrado'], 404);
        }

        return Response::json(['data' => $movimento->toArray()]);
    }

    public function store(): Response
    {
        $data = $this->getRequestData();
        $movimento = new Movimento(
            0,
            isset($data['CodUsuario']) ? (int) $data['CodUsuario'] : null,
            $data['Empresa'] ?? null,
            $data['DataAbertura'] ?? date('Y-m-d'),
            $data['HoraAbertura'] ?? date('H:i'),
            null,
            null,
            'A',
            $data['Observacao'] ?? null
        );

        $useCase = new AbrirMovimentoUseCase($this->repository);
        $id = $useCase->execute($movimento);

        return Response::json(['data' => ['id' => $id]], 201);
    }

    public function fechar(int $id): Response
    {
        $data = $this->getRequestData();
        $observacao = $data['Observacao'] ?? null;

        $useCase = new FecharMovimentoUseCase($this->repository);
        $success = $useCase->execute($id, $observacao);

        return Response::json(['data' => ['success' => $success]]);
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
