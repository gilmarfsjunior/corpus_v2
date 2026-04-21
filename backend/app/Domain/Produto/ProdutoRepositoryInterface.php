<?php

namespace App\Domain\Produto;

interface ProdutoRepositoryInterface
{
    /**
     * @return Produto[]
     */
    public function listar(?string $filtro = null): array;

    public function buscarPorId(int $id): ?Produto;

    public function salvar(Produto $produto): int;

    public function ativarDesativar(int $id, bool $ativo): bool;
}
