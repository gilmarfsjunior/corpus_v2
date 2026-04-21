<?php

namespace App\Application\Pago;

use App\Domain\Pago\PagoRepositoryInterface;

class ObterPagoUseCase
{
    public function __construct(private PagoRepositoryInterface $repository)
    {
    }

    public function execute(int $id)
    {
        return $this->repository->buscarPorId($id);
    }
}