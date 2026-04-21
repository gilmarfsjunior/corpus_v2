<?php

namespace App\Application\Movimento;

use App\Domain\Movimento\MovimentoRepositoryInterface;

class ListarMovimentosUseCase
{
    public function __construct(private MovimentoRepositoryInterface $repository)
    {
    }

    public function execute(?string $dataAbertura = null, ?string $status = null, ?string $empresa = null): array
    {
        return $this->repository->listar($dataAbertura, $status, $empresa);
    }
}
