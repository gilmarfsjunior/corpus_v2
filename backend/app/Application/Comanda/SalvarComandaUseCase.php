<?php

namespace App\Application\Comanda;

use App\Domain\Comanda\Comanda;
use App\Domain\Comanda\ComandaRepositoryInterface;

class SalvarComandaUseCase
{
    public function __construct(private ComandaRepositoryInterface $repository)
    {
    }

    public function execute(Comanda $comanda): int
    {
        return $this->repository->salvar($comanda);
    }
}
