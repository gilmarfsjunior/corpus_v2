<?php

namespace App\Application\Venda;

use App\Domain\Venda\VendaRepositoryInterface;

class ListarVendasUseCase
{
    public function __construct(private VendaRepositoryInterface $repository)
    {
    }

    public function execute(?string $dataVenda = null, ?string $status = null, ?string $empresa = null): array
    {
        return $this->repository->listar($dataVenda, $status, $empresa);
    }
}
