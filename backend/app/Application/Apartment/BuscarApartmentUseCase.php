<?php

namespace App\Application\Apartment;

use App\Domain\Apartment\ApartmentRepositoryInterface;

class BuscarApartmentUseCase
{
    public function __construct(private ApartmentRepositoryInterface $repository)
    {
    }

    public function execute(int $id): ?\App\Domain\Apartment\Apartment
    {
        return $this->repository->buscarPorId($id);
    }
}