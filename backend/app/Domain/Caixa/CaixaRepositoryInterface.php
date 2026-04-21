<?php

namespace App\Domain\Caixa;

interface CaixaRepositoryInterface
{
    /**
     * @return Caixa[]
     */
    public function listar(?string $dataCaixa = null, ?string $empresa = null): array;

    public function buscarPorId(int $id): ?Caixa;

    public function salvar(Caixa $caixa): int;

    public function fecharCaixa(int $id, float $saldoFinal, float $saldoFinalBanco): bool;
}