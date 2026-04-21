<?php

namespace App\Application\Produto;

use App\Domain\Produto\ProdutoRepositoryInterface;

class ListarProdutosUseCase
{
    public function __construct(private ProdutoRepositoryInterface $repository)
    {
    }

    public function execute(?string $filtro = null): array
    {
        return $this->repository->listar($filtro);
    }
}
