<?php

namespace App\Application\Recebido;

use App\Domain\Recebido\RecebidoRepositoryInterface;

class ObterRecebidoUseCase
{
    public function __construct(private RecebidoRepositoryInterface $repository)
    {
    }

    public function execute(int $id)
    {
        return $this->repository->buscarPorId($id);
    }
}