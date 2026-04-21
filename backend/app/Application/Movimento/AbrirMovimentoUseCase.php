<?php

namespace App\Application\Movimento;

use App\Domain\Movimento\Movimento;
use App\Domain\Movimento\MovimentoRepositoryInterface;

class AbrirMovimentoUseCase
{
    public function __construct(private MovimentoRepositoryInterface $repository)
    {
    }

    public function execute(Movimento $movimento): int
    {
        return $this->repository->abrir($movimento);
    }
}
