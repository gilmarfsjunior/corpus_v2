<?php

namespace App\Application\Pago;

use App\Domain\Pago\Pago;
use App\Domain\Pago\PagoRepositoryInterface;

class SalvarPagoUseCase
{
    public function __construct(private PagoRepositoryInterface $repository)
    {
    }

    public function execute(Pago $pago): int
    {
        return $this->repository->salvar($pago);
    }
}