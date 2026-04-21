<?php

namespace App\Application\Caixa;

use App\Domain\Caixa\CaixaRepositoryInterface;

class FecharCaixaUseCase
{
    public function __construct(private CaixaRepositoryInterface $repository)
    {
    }

    public function execute(int $id, float $saldoFinal, float $saldoFinalBanco): bool
    {
        return $this->repository->fecharCaixa($id, $saldoFinal, $saldoFinalBanco);
    }
}