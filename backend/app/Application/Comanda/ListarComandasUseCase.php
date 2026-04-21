<?php

namespace App\Application\Comanda;

use App\Domain\Comanda\ComandaRepositoryInterface;

class ListarComandasUseCase
{
    public function __construct(private ComandaRepositoryInterface $repository)
    {
    }

    /**
     * @return array
     */
    public function execute(?string $dataInicial = null, ?string $dataFinal = null, ?string $codComanda = null, ?bool $concluido = null, ?string $empresa = null): array
    {
        return $this->repository->listar($dataInicial, $dataFinal, $codComanda, $concluido, $empresa);
    }
}
