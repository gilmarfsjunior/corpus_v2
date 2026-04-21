<?php

namespace App\Application\Comanda;

use App\Domain\Comanda\ComandaRepositoryInterface;

class ListarItensComandaUseCase
{
    public function __construct(private ComandaRepositoryInterface $repository)
    {
    }

    public function execute(int $comandaId): array
    {
        return $this->repository->listarItens($comandaId);
    }
}
