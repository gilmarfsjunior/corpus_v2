<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Caixa\ListarCaixasUseCase;
use App\Application\Caixa\ObterCaixaUseCase;
use App\Application\Caixa\SalvarCaixaUseCase;
use App\Application\Caixa\FecharCaixaUseCase;
use App\Domain\Caixa\Caixa;
use App\Infrastructure\Database\Connection;
use App\Infrastructure\Persistence\CaixaRepository;
use App\Shared\Http\Response;

class CaixaController
{
    private Connection $connection;
    private CaixaRepository $repository;

    public function __construct()
    {
        $config = require dirname(__DIR__, 4) . '/config/database.php';
        $this->connection = new Connection($config);
        $this->repository = new CaixaRepository($this->connection);
    }

    public function index(): Response
    {
        $dataCaixa = trim($_GET['dataCaixa'] ?? '') ?: null;
        $empresa = trim($_GET['empresa'] ?? '') ?: null;

        $useCase = new ListarCaixasUseCase($this->repository);
        $caixas = array_map(static function ($caixa) {
            return $caixa->toArray();
        }, $useCase->execute($dataCaixa, $empresa));

        return Response::json(['data' => $caixas]);
    }

    public function show(int $id): Response
    {
        $useCase = new ObterCaixaUseCase($this->repository);
        $caixa = $useCase->execute($id);

        if ($caixa === null) {
            return Response::json(['message' => 'Caixa não encontrada'], 404);
        }

        return Response::json(['data' => $caixa->toArray()]);
    }

    public function store(): Response
    {
        $data = $this->getRequestData();
        $caixa = $this->createCaixaFromData($data);

        $useCase = new SalvarCaixaUseCase($this->repository);
        $id = $useCase->execute($caixa);

        return Response::json(['data' => ['id' => $id]], 201);
    }

    public function update(int $id): Response
    {
        $data = $this->getRequestData();
        $data['id'] = $id;
        $caixa = $this->createCaixaFromData($data);

        $useCase = new SalvarCaixaUseCase($this->repository);
        $id = $useCase->execute($caixa);

        return Response::json(['data' => ['id' => $id]]);
    }

    public function fechar(int $id): Response
    {
        $data = $this->getRequestData();
        $saldoFinal = isset($data['saldoFinal']) ? (float) $data['saldoFinal'] : 0;
        $saldoFinalBanco = isset($data['saldoFinalBanco']) ? (float) $data['saldoFinalBanco'] : 0;

        $useCase = new FecharCaixaUseCase($this->repository);
        $success = $useCase->execute($id, $saldoFinal, $saldoFinalBanco);

        if (!$success) {
            return Response::json(['message' => 'Erro ao fechar caixa'], 400);
        }

        return Response::json(['message' => 'Caixa fechada com sucesso']);
    }

    private function createCaixaFromData(array $data): Caixa
    {
        return new Caixa(
            isset($data['id']) ? (int) $data['id'] : 0,
            $data['dataCaixa'] ?? null,
            $data['dataPrev1'] ?? null,
            $data['dataPrev2'] ?? null,
            isset($data['saldoInicial']) ? (float) $data['saldoInicial'] : null,
            isset($data['saldoFinal']) ? (float) $data['saldoFinal'] : null,
            $data['empresa'] ?? null,
            isset($data['saldoInicialBanco']) ? (float) $data['saldoInicialBanco'] : null,
            isset($data['saldoFinalBanco']) ? (float) $data['saldoFinalBanco'] : null
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