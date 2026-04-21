<?php

namespace App\Domain\Recebimento;

interface RecebimentoRepositoryInterface
{
    /**
     * @return Recebimento[]
     */
    public function listar(?int $codPedido = null, ?string $status = null, ?string $empresa = null): array;

    public function buscarPorId(int $id): ?Recebimento;

    public function salvar(Recebimento $recebimento): int;

    public function receber(int $id, float $valorRecebido): bool;
}