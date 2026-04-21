<?php

namespace App\Application\Recebimento;

use App\Domain\Recebimento\RecebimentoRepositoryInterface;

class ObterRecebimentoUseCase
{
    public function __construct(private RecebimentoRepositoryInterface $repository)
    {
    }

    public function execute(int $id)
    {
        return $this->repository->buscarPorId($id);
    }
}