<?php

namespace App\Application\Produto;

use App\Domain\Produto\ProdutoRepositoryInterface;
use App\Domain\Produto\Produto;

class BuscarProdutoUseCase
{
    public function __construct(private ProdutoRepositoryInterface $repository)
    {
    }

    public function execute(int $id): ?Produto
    {
        return $this->repository->buscarPorId($id);
    }
}
