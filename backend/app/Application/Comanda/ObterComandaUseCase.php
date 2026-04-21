<?php

namespace App\Application\Comanda;

use App\Domain\Comanda\ComandaRepositoryInterface;
use App\Domain\Comanda\Comanda;

class ObterComandaUseCase
{
    public function __construct(private ComandaRepositoryInterface $repository)
    {
    }

    public function execute(int $id): ?Comanda
    {
        return $this->repository->buscarPorId($id);
    }
}
