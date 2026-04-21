<?php

namespace App\Application\Comanda;

use App\Domain\Comanda\ComandaItem;
use App\Domain\Comanda\ComandaRepositoryInterface;

class AdicionarItemComandaUseCase
{
    public function __construct(private ComandaRepositoryInterface $repository)
    {
    }

    public function execute(ComandaItem $item): int
    {
        return $this->repository->adicionarItem($item);
    }
}
