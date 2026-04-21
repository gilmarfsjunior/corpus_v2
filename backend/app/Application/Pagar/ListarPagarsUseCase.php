<?php

namespace App\Application\Pagar;

use App\Domain\Pagar\PagarRepositoryInterface;

class ListarPagarsUseCase
{
    public function __construct(private PagarRepositoryInterface $repository)
    {
    }

    public function execute(?int $codNotaCompra = null, ?string $status = null, ?string $empresa = null): array
    {
        return $this->repository->listar($codNotaCompra, $status, $empresa);
    }
}