<?php

namespace App\Application\Recebimento;

use App\Domain\Recebimento\RecebimentoRepositoryInterface;

class ListarRecebimentosUseCase
{
    public function __construct(private RecebimentoRepositoryInterface $repository)
    {
    }

    public function execute(?int $codPedido = null, ?string $status = null, ?string $empresa = null): array
    {
        return $this->repository->listar($codPedido, $status, $empresa);
    }
}