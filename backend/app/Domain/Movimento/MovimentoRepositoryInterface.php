<?php

namespace App\Domain\Movimento;

interface MovimentoRepositoryInterface
{
    /**
     * @return Movimento[]
     */
    public function listar(?string $dataAbertura = null, ?string $status = null, ?string $empresa = null): array;

    public function buscarPorId(int $id): ?Movimento;

    public function abrir(Movimento $movimento): int;

    public function fechar(int $id, ?string $observacao = null): bool;
}
