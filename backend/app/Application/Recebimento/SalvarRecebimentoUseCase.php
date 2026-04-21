<?php

namespace App\Application\Recebimento;

use App\Domain\Recebimento\Recebimento;
use App\Domain\Recebimento\RecebimentoRepositoryInterface;

class SalvarRecebimentoUseCase
{
    public function __construct(private RecebimentoRepositoryInterface $repository)
    {
    }

    public function execute(Recebimento $recebimento): int
    {
        return $this->repository->salvar($recebimento);
    }
}