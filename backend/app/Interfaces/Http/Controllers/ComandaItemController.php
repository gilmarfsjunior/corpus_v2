<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Comanda\AdicionarItemComandaUseCase;
use App\Application\Comanda\AlterarStatusItemComandaUseCase;
use App\Application\Comanda\ListarItensComandaUseCase;
use App\Domain\Comanda\ComandaItem;
use App\Infrastructure\Database\Connection;
use App\Infrastructure\Persistence\ComandaRepository;
use App\Shared\Http\Response;

class ComandaItemController
{
    private Connection $connection;
    private ComandaRepository $repository;

    public function __construct()
    {
        $config = require dirname(__DIR__, 4) . '/config/database.php';
        $this->connection = new Connection($config);
        $this->repository = new ComandaRepository($this->connection);
    }

    public function index(int $comandaId): Response
    {
        $useCase = new ListarItensComandaUseCase($this->repository);
        $itens = array_map(static function ($item) {
            return $item->toArray();
        }, $useCase->execute($comandaId));

        return Response::json(['data' => $itens]);
    }

    public function store(int $comandaId): Response
    {
        $data = $this->getRequestData();
        $item = new ComandaItem(
            0,
            $comandaId,
            isset($data['CodProduto']) ? (int) $data['CodProduto'] : 0,
            $data['Descricao'] ?? '',
            isset($data['Quantidade']) ? (float) $data['Quantidade'] : 0.0,
            isset($data['Valor']) ? (float) $data['Valor'] : 0.0,
            true,
            null
        );

        $useCase = new AdicionarItemComandaUseCase($this->repository);
        $id = $useCase->execute($item);

        return Response::json(['data' => ['id' => $id]], 201);
    }

    public function toggleStatus(int $comandaId, int $itemId): Response
    {
        $data = $this->getRequestData();
        $ativo = filter_var($data['ativo'] ?? true, FILTER_VALIDATE_BOOLEAN);

        $useCase = new AlterarStatusItemComandaUseCase($this->repository);
        $success = $useCase->execute($itemId, $ativo);

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
