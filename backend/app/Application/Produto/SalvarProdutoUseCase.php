<?php

namespace App\Application\Produto;

use App\Domain\Produto\Produto;
use App\Domain\Produto\ProdutoRepositoryInterface;

class SalvarProdutoUseCase
{
    public function __construct(private ProdutoRepositoryInterface $repository)
    {
    }

    public function execute(Produto $produto): int
    {
        return $this->repository->salvar($produto);
    }
}
