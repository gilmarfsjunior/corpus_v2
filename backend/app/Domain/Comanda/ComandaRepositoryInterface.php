<?php

namespace App\Domain\Comanda;

use App\Domain\Comanda\ComandaItem;

interface ComandaRepositoryInterface
{
    /**
     * @return Comanda[]
     */
    public function listar(?string $dataInicial = null, ?string $dataFinal = null, ?string $codComanda = null, ?bool $concluido = null, ?string $empresa = null): array;

    public function buscarPorId(int $id): ?Comanda;

    /**
     * @return ComandaItem[]
     */
    public function listarItens(int $comandaId): array;

    public function adicionarItem(ComandaItem $item): int;

    public function alterarStatusItem(int $itemId, bool $ativo): bool;

    public function salvar(Comanda $comanda): int;
}
