<?php

namespace App\Application\Caixa;

use App\Domain\Caixa\CaixaRepositoryInterface;

class ListarCaixasUseCase
{
    public function __construct(private CaixaRepositoryInterface $repository)
    {
    }

    public function execute(?string $dataCaixa = null, ?string $empresa = null): array
    {
        return $this->repository->listar($dataCaixa, $empresa);
    }
}