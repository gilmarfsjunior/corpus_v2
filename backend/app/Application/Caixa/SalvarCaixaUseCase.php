<?php

namespace App\Application\Caixa;

use App\Domain\Caixa\Caixa;
use App\Domain\Caixa\CaixaRepositoryInterface;

class SalvarCaixaUseCase
{
    public function __construct(private CaixaRepositoryInterface $repository)
    {
    }

    public function execute(Caixa $caixa): int
    {
        return $this->repository->salvar($caixa);
    }
}