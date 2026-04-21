<?php

namespace App\Application\Pagar;

use App\Domain\Pagar\PagarRepositoryInterface;

class PagarPagarUseCase
{
    public function __construct(private PagarRepositoryInterface $repository)
    {
    }

    public function execute(int $id): bool
    {
        return $this->repository->pagar($id);
    }
}