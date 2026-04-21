<?php

namespace App\Application\Produto;

use App\Domain\Produto\ProdutoRepositoryInterface;

class AlterarStatusProdutoUseCase
{
    public function __construct(private ProdutoRepositoryInterface $repository)
    {
    }

    public function execute(int $id, bool $ativo): bool
    {
        return $this->repository->ativarDesativar($id, $ativo);
    }
}
