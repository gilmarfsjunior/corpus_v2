<?php

namespace App\Application\Recebido;

use App\Domain\Recebido\Recebido;
use App\Domain\Recebido\RecebidoRepositoryInterface;

class SalvarRecebidoUseCase
{
    public function __construct(private RecebidoRepositoryInterface $repository)
    {
    }

    public function execute(Recebido $recebido): int
    {
        return $this->repository->salvar($recebido);
    }
}