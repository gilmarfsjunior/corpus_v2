<?php

namespace App\Domain\Recebido;

interface RecebidoRepositoryInterface
{
    /**
     * @return Recebido[]
     */
    public function listar(?int $codParcela = null, ?string $empresa = null): array;

    public function buscarPorId(int $id): ?Recebido;

    public function salvar(Recebido $recebido): int;
}