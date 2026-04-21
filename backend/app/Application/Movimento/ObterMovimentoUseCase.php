<?php

namespace App\Application\Movimento;

use App\Domain\Movimento\MovimentoRepositoryInterface;

class ObterMovimentoUseCase
{
    public function __construct(private MovimentoRepositoryInterface $repository)
    {
    }

    public function execute(int $id)
    {
        return $this->repository->buscarPorId($id);
    }
}
