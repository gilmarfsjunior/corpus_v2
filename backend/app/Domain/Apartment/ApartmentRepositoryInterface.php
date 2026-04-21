<?php

namespace App\Domain\Apartment;

interface ApartmentRepositoryInterface
{
    /**
     * @return Apartment[]
     */
    public function listarAtivos(): array;

    public function buscarPorId(int $id): ?Apartment;

    public function buscarPorNumero(int $numero): ?Apartment;

    /**
     * @return Apartment[]
     */
    public function listarPorTipo(int $tipoId): array;

    public function atualizarStatus(int $id, string $status): bool;
}