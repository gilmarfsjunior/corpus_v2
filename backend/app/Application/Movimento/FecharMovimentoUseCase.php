<?php

namespace App\Application\Movimento;

use App\Domain\Movimento\MovimentoRepositoryInterface;

class FecharMovimentoUseCase
{
    public function __construct(private MovimentoRepositoryInterface $repository)
    {
    }

    public function execute(int $id, ?string $observacao = null): bool
    {
        return $this->repository->fechar($id, $observacao);
    }
}
