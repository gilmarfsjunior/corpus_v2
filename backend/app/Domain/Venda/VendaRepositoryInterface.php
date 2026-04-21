<?php

namespace App\Domain\Venda;

interface VendaRepositoryInterface
{
    /**
     * @return Venda[]
     */
    public function listar(?string $dataVenda = null, ?string $status = null, ?string $empresa = null): array;

    public function buscarPorId(int $id): ?Venda;

    public function salvar(Venda $venda): int;
}
