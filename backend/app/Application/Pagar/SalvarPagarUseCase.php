<?php

namespace App\Application\Pagar;

use App\Domain\Pagar\Pagar;
use App\Domain\Pagar\PagarRepositoryInterface;

class SalvarPagarUseCase
{
    public function __construct(private PagarRepositoryInterface $repository)
    {
    }

    public function execute(Pagar $pagar): int
    {
        return $this->repository->salvar($pagar);
    }
}