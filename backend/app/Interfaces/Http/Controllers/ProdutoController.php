<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Produto\AlterarStatusProdutoUseCase;
use App\Application\Produto\BuscarProdutoUseCase;
use App\Application\Produto\ListarProdutosUseCase;
use App\Application\Produto\SalvarProdutoUseCase;
use App\Domain\Produto\Produto;
use App\Infrastructure\Database\Connection;
use App\Infrastructure\Persistence\ProdutoRepository;
use App\Shared\Http\Response;

class ProdutoController
{
    private Connection $connection;
    private ProdutoRepository $repository;

    public function __construct()
    {
        $config = require dirname(__DIR__, 4) . '/config/database.php';
        $this->connection = new Connection($config);
        $this->repository = new ProdutoRepository($this->connection);
    }

    public function index(): Response
    {
        $query = $_GET['q'] ?? null;
        $useCase = new ListarProdutosUseCase($this->repository);
        $produtos = array_map(static function (Produto $produto): array {
            return $produto->toArray();
        }, $useCase->execute($query));

        return Response::json(['data' => $produtos]);
    }

    public function show(int $id): Response
    {
        $useCase = new BuscarProdutoUseCase($this->repository);
        $produto = $useCase->execute($id);

        if ($produto === null) {
            return Response::json(['message' => 'Produto não encontrado'], 404);
        }

        return Response::json(['data' => $produto->toArray()]);
    }

    public function store(): Response
    {
        $data = $this->getRequestData();
        $produto = new Produto(
            isset($data['id']) ? (int) $data['id'] : 0,
            (string) ($data['descricao'] ?? ''),
            $data['marca'] ?? null,
            $data['unidade'] ?? null,
            isset($data['estoqueMinimo']) ? (float) $data['estoqueMinimo'] : null,
            isset($data['precoVenda']) ? (float) $data['precoVenda'] : null,
            $data['tipoP'] ?? null,
            $data['ativo'] ?? true,
            isset($data['categoriaId']) ? (int) $data['categoriaId'] : null,
            null,
            $data['codBarra'] ?? null,
            null
        );

        $useCase = new SalvarProdutoUseCase($this->repository);
        $id = $useCase->execute($produto);

        return Response::json(['data' => ['id' => $id]]);
    }

    public function toggleStatus(int $id): Response
    {
        $data = $this->getRequestData();
        $ativo = filter_var($data['ativo'] ?? true, FILTER_VALIDATE_BOOLEAN);

        $useCase = new AlterarStatusProdutoUseCase($this->repository);
        $success = $useCase->execute($id, $ativo);

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
