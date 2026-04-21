<?php

namespace App\Application\Venda;

use App\Domain\Venda\VendaRepositoryInterface;

class ObterVendaUseCase
{
    public function __construct(private VendaRepositoryInterface $repository)
    {
    }

    public function execute(int $id)
    {
        return $this->repository->buscarPorId($id);
    }
}
