<?php

namespace App\Application\Venda;

use App\Domain\Venda\Venda;
use App\Domain\Venda\VendaRepositoryInterface;

class SalvarVendaUseCase
{
    public function __construct(private VendaRepositoryInterface $repository)
    {
    }

    public function execute(Venda $venda): int
    {
        return $this->repository->salvar($venda);
    }
}
