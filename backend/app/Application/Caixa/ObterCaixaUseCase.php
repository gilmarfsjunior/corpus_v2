<?php

namespace App\Application\Caixa;

use App\Domain\Caixa\CaixaRepositoryInterface;

class ObterCaixaUseCase
{
    public function __construct(private CaixaRepositoryInterface $repository)
    {
    }

    public function execute(int $id)
    {
        return $this->repository->buscarPorId($id);
    }
}