<?php

namespace App\Application\Pagar;

use App\Domain\Pagar\PagarRepositoryInterface;

class ObterPagarUseCase
{
    public function __construct(private PagarRepositoryInterface $repository)
    {
    }

    public function execute(int $id)
    {
        return $this->repository->buscarPorId($id);
    }
}