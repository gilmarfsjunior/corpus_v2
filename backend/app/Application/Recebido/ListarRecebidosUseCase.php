<?php

namespace App\Application\Recebido;

use App\Domain\Recebido\RecebidoRepositoryInterface;

class ListarRecebidosUseCase
{
    public function __construct(private RecebidoRepositoryInterface $repository)
    {
    }

    public function execute(?int $codParcela = null, ?string $empresa = null): array
    {
        return $this->repository->listar($codParcela, $empresa);
    }
}