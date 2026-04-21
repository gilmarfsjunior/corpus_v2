<?php

namespace App\Domain\Pago;

interface PagoRepositoryInterface
{
    /**
     * @return Pago[]
     */
    public function listar(?int $codParcela = null, ?string $empresa = null): array;

    public function buscarPorId(int $id): ?Pago;

    public function salvar(Pago $pago): int;
}