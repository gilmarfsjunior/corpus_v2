<?php

namespace App\Application\Pago;

use App\Domain\Pago\PagoRepositoryInterface;

class ListarPagosUseCase
{
    public function __construct(private PagoRepositoryInterface $repository)
    {
    }

    public function execute(?int $codParcela = null, ?string $empresa = null): array
    {
        return $this->repository->listar($codParcela, $empresa);
    }
}