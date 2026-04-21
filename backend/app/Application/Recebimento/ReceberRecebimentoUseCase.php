<?php

namespace App\Application\Recebimento;

use App\Domain\Recebimento\RecebimentoRepositoryInterface;

class ReceberRecebimentoUseCase
{
    public function __construct(private RecebimentoRepositoryInterface $repository)
    {
    }

    public function execute(int $id, float $valorRecebido): bool
    {
        return $this->repository->receber($id, $valorRecebido);
    }
}