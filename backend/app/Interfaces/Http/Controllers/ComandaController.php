<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Comanda\ListarComandasUseCase;
use App\Application\Comanda\ObterComandaUseCase;
use App\Application\Comanda\SalvarComandaUseCase;
use App\Domain\Comanda\Comanda;
use App\Infrastructure\Database\Connection;
use App\Infrastructure\Persistence\ComandaRepository;
use App\Shared\Http\Response;

class ComandaController
{
    private Connection $connection;
    private ComandaRepository $repository;

    public function __construct()
    {
        $config = require dirname(__DIR__, 4) . '/config/database.php';
        $this->connection = new Connection($config);
        $this->repository = new ComandaRepository($this->connection);
    }

    public function index(): Response
    {
        $dataInicial = trim($_GET['dataInicial'] ?? '') ?: null;
        $dataFinal = trim($_GET['dataFinal'] ?? '') ?: null;
        $codComanda = trim($_GET['CodComanda'] ?? '') ?: null;
        $concluidoParam = trim($_GET['Concluido'] ?? '');
        $concluido = $concluidoParam === '' ? null : ($concluidoParam === 'S');
        $empresa = trim($_GET['Empresa'] ?? '') ?: null;

        $useCase = new ListarComandasUseCase($this->repository);
        $comandas = array_map(static function ($comanda) {
            return $comanda->toArray();
        }, $useCase->execute($dataInicial, $dataFinal, $codComanda, $concluido, $empresa));

        return Response::json(['data' => $comandas]);
    }

    public function show(int $id): Response
    {
        $useCase = new ObterComandaUseCase($this->repository);
        $comanda = $useCase->execute($id);

        if ($comanda === null) {
            return Response::json(['message' => 'Comanda não encontrada'], 404);
        }

        return Response::json(['data' => $comanda->toArray()]);
    }

    public function store(): Response
    {
        $data = $this->getRequestData();
        $comanda = $this->createComandaFromData($data);

        $useCase = new SalvarComandaUseCase($this->repository);
        $id = $useCase->execute($comanda);

        return Response::json(['data' => ['id' => $id]]);
    }

    public function update(int $id): Response
    {
        $data = $this->getRequestData();
        $data['id'] = $id;
        $comanda = $this->createComandaFromData($data);

        $useCase = new SalvarComandaUseCase($this->repository);
        $id = $useCase->execute($comanda);

        return Response::json(['data' => ['id' => $id]]);
    }

    private function createComandaFromData(array $data): Comanda
    {
        return new Comanda(
            isset($data['id']) ? (int) $data['id'] : 0,
            isset($data['CodApartamento']) ? (int) $data['CodApartamento'] : null,
            $data['DataEntrada'] ?? null,
            $data['HoraEntrada'] ?? null,
            $data['DataSaida'] ?? null,
            $data['HoraSaida'] ?? null,
            $data['Usuario'] ?? null,
            $data['Empresa'] ?? null,
            $data['Placa'] ?? null,
            isset($data['ValorTotal']) ? (float) $data['ValorTotal'] : null,
            isset($data['ValorTotalConf']) ? (float) $data['ValorTotalConf'] : null,
            $data['UsuarioSaida'] ?? null,
            isset($data['QuantHoras']) ? (int) $data['QuantHoras'] : null,
            isset($data['TotalHoras']) ? (float) $data['TotalHoras'] : null,
            isset($data['Concluido']) ? filter_var($data['Concluido'], FILTER_VALIDATE_BOOLEAN) : null,
            isset($data['CodMovimento']) ? (int) $data['CodMovimento'] : null,
            isset($data['Dinheiro']) ? (float) $data['Dinheiro'] : null,
            isset($data['Cheque']) ? (float) $data['Cheque'] : null,
            isset($data['Cartao']) ? (float) $data['Cartao'] : null,
            $data['ComandaTipo'] ?? null,
            $data['ClienteComanda'] ?? null
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
