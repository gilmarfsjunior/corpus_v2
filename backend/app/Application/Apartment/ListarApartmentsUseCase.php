<?php

namespace App\Application\Apartment;

use App\Domain\Apartment\ApartmentRepositoryInterface;

class ListarApartmentsUseCase
{
    public function __construct(private ApartmentRepositoryInterface $repository)
    {
    }

    public function execute(): array
    {
        return $this->repository->listarAtivos();
    }
}