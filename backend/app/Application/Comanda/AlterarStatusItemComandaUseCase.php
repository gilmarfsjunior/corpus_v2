<?php

namespace App\Application\Comanda;

use App\Domain\Comanda\ComandaRepositoryInterface;

class AlterarStatusItemComandaUseCase
{
    public function __construct(private ComandaRepositoryInterface $repository)
    {
    }

    public function execute(int $itemId, bool $ativo): bool
    {
        return $this->repository->alterarStatusItem($itemId, $ativo);
    }
}
