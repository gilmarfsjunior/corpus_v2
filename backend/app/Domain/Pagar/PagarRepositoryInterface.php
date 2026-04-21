<?php

namespace App\Domain\Pagar;

interface PagarRepositoryInterface
{
    /**
     * @return Pagar[]
     */
    public function listar(?int $codNotaCompra = null, ?string $status = null, ?string $empresa = null): array;

    public function buscarPorId(int $id): ?Pagar;

    public function salvar(Pagar $pagar): int;

    public function pagar(int $id): bool;
}